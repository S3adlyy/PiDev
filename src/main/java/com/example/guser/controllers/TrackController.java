package com.example.guser.controllers;

import entities.Artifact;
import entities.FileObject;
import entities.Snapshot;
import entities.Track;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.control.*;
import javafx.scene.layout.HBox;
import javafx.scene.layout.StackPane;
import javafx.scene.layout.VBox;
import services.*;

import java.nio.file.Path;
import java.sql.SQLException;
import java.time.format.DateTimeFormatter;
import java.util.*;
import java.util.stream.Collectors;

import javafx.scene.layout.GridPane;
import javafx.stage.DirectoryChooser;
import javafx.stage.FileChooser;

import java.io.*;
import java.nio.file.*;
import java.util.zip.ZipEntry;
import java.util.zip.ZipOutputStream;

import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.stage.FileChooser;

import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.time.Duration;



public class TrackController {

    @FXML private VBox trackDetailPane;

    @FXML private Button backToTracksBtn;
    @FXML private Label trackTitleLabel;
    @FXML private ComboBox<String> trackVisibilityCombo;
    @FXML private Button createSnapshotBtn;

    @FXML private ListView<Snapshot> snapshotsListView;
    @FXML private ListView<ArtifactRow> artifactsListView;

    @FXML private StackPane artifactViewerHost;
    @FXML private Label viewerPlaceholderLabel;

    @FXML private Label trackErrorLabel;

    // Services (your exact constructors)
    private final TrackService trackService = new TrackService();
    private final ArtifactService artifactService = new ArtifactService();
    private final SnapshotItemService snapshotItemService = new SnapshotItemService();

    private final String bucket = "carrieri-storage-dev-islem";   // TODO: centralize config
    private final String region = "eu-west-3";   // TODO: centralize config

    private final SnapshotService snapshotService = new SnapshotService(bucket, region);
    private final FileObjectService fileObjectService = new FileObjectService(bucket, region);
    private final CodeBrowseService codeBrowseService = new CodeBrowseService(bucket, region);

    private final ObservableList<Snapshot> snapshots = FXCollections.observableArrayList();
    private final ObservableList<ArtifactRow> artifactRows = FXCollections.observableArrayList();

    private int candidateId;
    private int viewerUserId;
    private boolean ownerMode;
    private Track track;
    private Runnable onBack;

    private Snapshot selectedSnapshot;

    private static final long MAX_UPLOAD_BYTES = 50L * 1024 * 1024; // 50MB


    @FXML
    private void initialize() {
        trackVisibilityCombo.setItems(FXCollections.observableArrayList("PUBLIC", "PRIVATE"));

        snapshotsListView.setItems(snapshots);
        artifactsListView.setItems(artifactRows);

        installSnapshotCells();
        installArtifactCells();

        snapshotsListView.getSelectionModel().selectedItemProperty().addListener((obs, o, s) -> {
            if (s != null) selectSnapshot(s);
        });

        artifactsListView.getSelectionModel().selectedItemProperty().addListener((obs, o, row) -> {
            if (row == null || row.kind != ArtifactRowKind.ITEM) return;
            openArtifact(row);
        });

        hide();
        setError(null);
        showPlaceholder("Select a snapshot.");
    }

    public void initContext(int candidateId, int viewerUserId, boolean ownerMode, Track track, Runnable onBack) {
        this.candidateId = candidateId;
        this.viewerUserId = viewerUserId;
        this.ownerMode = ownerMode;
        this.track = track;
        this.onBack = onBack;

        trackTitleLabel.setText(track.getTitle());

        trackVisibilityCombo.getSelectionModel().select(track.getVisibility());
        trackVisibilityCombo.setDisable(!ownerMode);

        createSnapshotBtn.setVisible(ownerMode);
        createSnapshotBtn.setManaged(ownerMode);

        refreshSnapshots();
    }

    public void show() {
        trackDetailPane.setVisible(true);
        trackDetailPane.setManaged(true);
    }

    public void hide() {
        trackDetailPane.setVisible(false);
        trackDetailPane.setManaged(false);
    }

    @FXML
    private void onBackToTracks() {
        if (onBack != null) onBack.run();
    }

    @FXML
    private void onVisibilityChanged() {
        if (!ownerMode || track == null) return;
        String v = trackVisibilityCombo.getSelectionModel().getSelectedItem();
        if (v == null) return;

        try {
            trackService.updateVisibility(track.getId(), v);
            track.setVisibility(v);
            setError(null);
        } catch (SQLException e) {
            setError(e.getMessage());
        }
    }

    @FXML
    private void onCreateSnapshot() {
        if (!ownerMode) return;

        TextInputDialog d = new TextInputDialog("Snapshot message");
        d.setHeaderText("Create Snapshot");
        d.setContentText("Message:");
        Optional<String> res = d.showAndWait();
        if (res.isEmpty()) return;

        try {
            snapshotService.createSnapshot(candidateId, track.getId(), viewerUserId,
                    "Snapshot", res.get(), false);

            refreshSnapshots();
            setError(null);
        } catch (Exception e) {
            setError(e.getMessage());
        }
    }

    @FXML
    private void onAddArtifact() {
        if (!ownerMode) return;

        Dialog<ArtifactDraft> dialog = new Dialog<>();
        dialog.setTitle("Add Artifact");
        dialog.setHeaderText(null);
        dialog.getDialogPane().getButtonTypes().addAll(ButtonType.CANCEL, ButtonType.OK);

        TextField nameField = new TextField();
        nameField.setPromptText("Artifact name (e.g., repo, report, demo)");

        TextArea descArea = new TextArea();
        descArea.setPromptText("Description (optional)");
        descArea.setPrefRowCount(3);

        ComboBox<String> typeBox = new ComboBox<>(FXCollections.observableArrayList(
                "CODE", "DOCUMENT", "IMAGE", "VIDEO", "TEXT", "LINK"
        ));
        typeBox.getSelectionModel().select("CODE");

        TextField languageField = new TextField();
        languageField.setPromptText("Language (optional, for CODE)");

        TextArea textArea = new TextArea();
        textArea.setPromptText("Text / URL (for TEXT/LINK)");
        textArea.setPrefRowCount(4);

        Label uploadHint = new Label("For CODE/DOCUMENT/IMAGE/VIDEO you will upload a file (or folder→zip for CODE) after creating.");
        uploadHint.getStyleClass().add("prf-muted");

        GridPane grid = new GridPane();
        grid.setHgap(10);
        grid.setVgap(10);

        int r = 0;
        grid.addRow(r++, new Label("Name"), nameField);
        grid.addRow(r++, new Label("Type"), typeBox);
        grid.addRow(r++, new Label("Language"), languageField);
        grid.addRow(r++, new Label("Text/URL"), textArea);
        grid.addRow(r++, new Label("Description"), descArea);
        grid.add(uploadHint, 0, r++, 2, 1);

        dialog.getDialogPane().setContent(grid);

        // Dynamic fields based on type
        Runnable refreshFields = () -> {
            String type = safeUpper(typeBox.getValue());
            boolean isCode = type.equals("CODE");
            boolean isTextLike = type.equals("TEXT") || type.equals("LINK");

            languageField.setDisable(!isCode);
            textArea.setDisable(!isTextLike);

            if (!isTextLike) textArea.clear();
            if (!isCode) languageField.clear();
        };
        typeBox.valueProperty().addListener((obs, o, v) -> refreshFields.run());
        refreshFields.run();

        Node okBtn = dialog.getDialogPane().lookupButton(ButtonType.OK);
        okBtn.setDisable(true);
        nameField.textProperty().addListener((obs, o, v) -> okBtn.setDisable(v == null || v.trim().isEmpty()));

        dialog.setResultConverter(bt -> {
            if (bt != ButtonType.OK) return null;
            ArtifactDraft d = new ArtifactDraft();
            d.name = nameField.getText().trim();
            d.description = descArea.getText();
            d.type = safeUpper(typeBox.getValue());
            d.language = languageField.getText() == null ? null : languageField.getText().trim();
            d.textContent = textArea.getText();
            return d;
        });

        Optional<ArtifactDraft> res = dialog.showAndWait();
        if (res.isEmpty()) return;

        ArtifactDraft d = res.get();

        try {
            // 1) Create artifact row in DB
            Artifact created = artifactService.create(
                    track.getId(),
                    d.name,
                    d.description,
                    d.type,
                    (d.type.equals("CODE") ? emptyToNull(d.language) : null),
                    ((d.type.equals("TEXT") || d.type.equals("LINK")) ? emptyToNull(d.textContent) : null)
            );

            // 2) If TEXT/LINK: also update snapshot behavior is already handled by SnapshotService (it snapshots to file_object)
            // so nothing else is required now.

            // 3) If file-based: immediately ask user to upload a file/version
            if (d.type.equals("CODE")) {
                promptUploadForCode(created);
            } else if (d.type.equals("DOCUMENT") || d.type.equals("IMAGE") || d.type.equals("VIDEO")) {
                promptUploadSingleFile(created, d.type);
            }
            // ---- Single refresh path (no duplicates, no relying on selection listener) ----
            if (selectedSnapshot != null) {
                // refresh the list for the currently selected snapshot so mapping appears
                refreshArtifactsForSnapshot(selectedSnapshot);
            } else if (!snapshots.isEmpty()) {
                // if snapshots exist but selectedSnapshot is null for any reason, pick newest and force refresh
                snapshotsListView.getSelectionModel().select(0);
                selectSnapshot(snapshots.get(0)); // selectSnapshot() calls refreshArtifactsForSnapshot(...)
            } else {
                // no snapshots yet: still show artifacts (without snapshot mapping)
                refreshArtifactsUI();
                showInfo("Artifact created. Create a snapshot to freeze versions.");
            }


            Alert a = new Alert(Alert.AlertType.CONFIRMATION);
            a.setTitle("Create snapshot?");
            a.setHeaderText("Artifact added");
            a.setContentText("Do you want to create a snapshot now to freeze this version?");
            ButtonType now = new ButtonType("Create snapshot");
            ButtonType later = new ButtonType("Later", ButtonBar.ButtonData.CANCEL_CLOSE);
            a.getButtonTypes().setAll(now, later);

            Optional<ButtonType> res2 = a.showAndWait();
            if (res2.isPresent() && res2.get() == now) {
                onCreateSnapshot();
                return; // onCreateSnapshot will refresh snapshots + select newest
            }

// If user chose later: show draft artifacts (no snapshot) so they still “see” it
            refreshArtifactsUI();



            setError(null);

        } catch (Exception e) {
            setError(e.getMessage());
        }
    }

    private static class ArtifactDraft {
        String name;
        String description;
        String type;
        String language;
        String textContent;
    }
    private void refreshArtifactsUI() {
        try {
            if (selectedSnapshot != null) {
                refreshArtifactsForSnapshot(selectedSnapshot);
            } else {
                // Show artifacts without snapshot mapping (fileObjectId unknown)
                List<Artifact> artifacts = artifactService.listActiveByTrack(track.getId());
                artifactRows.setAll(buildGroupedRows(artifacts));
                showPlaceholder("Create a snapshot to freeze versions.");
            }
            setError(null);
        } catch (Exception e) {
            setError(e.getMessage());
        }
    }


    private static String emptyToNull(String s) {
        if (s == null) return null;
        String t = s.trim();
        return t.isEmpty() ? null : t;
    }


    private void refreshSnapshots() {
        try {
            List<Snapshot> list = snapshotService.listByTrack(track.getId());
            snapshots.setAll(list);

            if (!snapshots.isEmpty()) {
                snapshotsListView.getSelectionModel().select(0); // newest first
                // Force call even if listener doesn't trigger:
                selectSnapshot(snapshots.get(0));
            } else {
                selectedSnapshot = null;
                artifactRows.clear();
                showPlaceholder("No snapshots yet. Create one.");
            }

            setError(null);
        } catch (SQLException e) {
            setError(e.getMessage());
        }
    }


    private void selectSnapshot(Snapshot s) {
        selectedSnapshot = s;
        refreshArtifactsForSnapshot(s);
    }

    private void refreshArtifactsForSnapshot(Snapshot s) {
        try {
            List<SnapshotItemService.SnapshotArtifactRow> rowsInSnap =
                    snapshotItemService.listArtifactsInSnapshot(s.getId());

            // Build grouped headers from ONLY snapshot artifacts
            List<Artifact> artifacts = rowsInSnap.stream().map(r -> r.artifact).toList();
            List<ArtifactRow> rows = buildGroupedRows(artifacts);

            // Map artifactId -> fileObjectId (guaranteed for your snapshots)
            Map<Integer, Integer> fileIdByArtifactId = new HashMap<>();
            for (var r : rowsInSnap) fileIdByArtifactId.put(r.artifact.getId(), r.fileObjectId);

            for (ArtifactRow r : rows) {
                if (r.kind == ArtifactRowKind.ITEM) {
                    r.fileObjectId = fileIdByArtifactId.get(r.artifact.getId());
                }
            }

            artifactRows.setAll(rows);

            ArtifactRow first = artifactRows.stream()
                    .filter(x -> x.kind == ArtifactRowKind.ITEM)
                    .findFirst()
                    .orElse(null);

            if (first != null) artifactsListView.getSelectionModel().select(first);
            else showPlaceholder("No artifacts in this snapshot.");

            setError(null);
        } catch (SQLException e) {
            setError(e.getMessage());
        }
    }




    private List<ArtifactRow> buildGroupedRows(List<Artifact> artifacts) {
        List<String> order = List.of("CODE", "DOCUMENT", "IMAGE", "VIDEO", "LINK", "TEXT");
        Map<String, List<Artifact>> byType = artifacts.stream()
                .collect(Collectors.groupingBy(a -> safeUpper(a.getArtifactType()), LinkedHashMap::new, Collectors.toList()));

        List<ArtifactRow> out = new ArrayList<>();
        for (String t : order) {
            List<Artifact> list = byType.get(t);
            if (list == null || list.isEmpty()) continue;
            out.add(ArtifactRow.header(typeLabel(t)));
            for (Artifact a : list) out.add(ArtifactRow.item(a));
        }
        for (Map.Entry<String, List<Artifact>> e : byType.entrySet()) {
            if (order.contains(e.getKey())) continue;
            out.add(ArtifactRow.header(typeLabel(e.getKey())));
            for (Artifact a : e.getValue()) out.add(ArtifactRow.item(a));
        }
        return out;
    }

    private void openArtifact(ArtifactRow row) {
        if (row.fileObjectId == null) {
            showPlaceholder("This artifact has no file in this snapshot.");
            return;
        }

        String type = safeUpper(row.artifact.getArtifactType());
        switch (type) {
            case "CODE" -> openCodeArtifact(row.fileObjectId);

            case "TEXT"-> {
                TextArea ta = new TextArea(row.artifact.getTextContent() == null ? "" : row.artifact.getTextContent());
                ta.setEditable(false);
                ta.setWrapText(true);

                Button dl = new Button("Download snapshot file");
                dl.getStyleClass().add("prf-outlineBtn");
                dl.setOnAction(e -> {
                    try {
                        downloadToDisk(row.fileObjectId, safeFileName(row.artifact.getArtifactName(), "txt"));
                        setError(null);
                    } catch (Exception ex) {
                        setError(ex.getMessage());
                    }
                });

                VBox box = new VBox(10, ta, dl);
                box.getStyleClass().add("wsp-viewBox");
                artifactViewerHost.getChildren().setAll(box);
            }

            case "IMAGE" -> openImageArtifact(row.artifact, row.fileObjectId);

            case "DOCUMENT" -> showDownloadPanel(
                    row.artifact.getArtifactName() + " (Document)",
                    row.fileObjectId,
                    safeFileName(row.artifact.getArtifactName(), "pdf")
            );

            case "VIDEO" -> showDownloadPanel(
                    row.artifact.getArtifactName() + " (Video)",
                    row.fileObjectId,
                    safeFileName(row.artifact.getArtifactName(), "mp4")
            );
            case "LINK" -> {
                String urlText = row.artifact.getTextContent() == null ? "" : row.artifact.getTextContent().trim();

                Hyperlink link = new Hyperlink(urlText.isBlank() ? "(empty link)" : urlText);
                link.getStyleClass().add("wsp-link");
                link.setOnAction(e -> {
                    try {
                        if (!urlText.isBlank()) com.example.guser.AppHostServices.get().showDocument(urlText);
                    } catch (Exception ex) {
                        setError(ex.getMessage());
                    }
                });

                Button dl = new Button("Download snapshot file");
                dl.getStyleClass().add("prf-outlineBtn");
                dl.setOnAction(e -> {
                    try {
                        downloadToDisk(row.fileObjectId, row.artifact.getArtifactName());
                        setError(null);
                    } catch (Exception ex) {
                        setError(ex.getMessage());
                    }
                });

                VBox box = new VBox(10, link, dl);
                box.getStyleClass().add("wsp-viewBox");
                artifactViewerHost.getChildren().setAll(box);
            }


            default -> showDownloadPanel(
                    row.artifact.getArtifactName(),
                    row.fileObjectId,
                    safeFileName(row.artifact.getArtifactName(), "bin")
            );
        }
    }

    private static String safeFileName(String base, String extNoDot) {
        String b = (base == null || base.isBlank()) ? "artifact" : base.trim();
        b = b.replaceAll("[\\\\/:*?\"<>|]", "_");
        if (extNoDot != null && !extNoDot.isBlank()) {
            String ext = extNoDot.toLowerCase();
            if (!b.toLowerCase().endsWith("." + ext)) b = b + "." + ext;
        }
        return b;
    }

    private void showDownloadPanel(String title, int fileObjectId, String suggestedName) {
        Label t = new Label(title);
        t.getStyleClass().add("wsp-viewTitle");

        Button dl = new Button("Download");
        dl.getStyleClass().add("prf-outlineBtn");
        dl.setOnAction(e -> {
            try {
                downloadToDisk(fileObjectId, suggestedName);
                setError(null);
            } catch (Exception ex) {
                setError(ex.getMessage());
            }
        });

        VBox box = new VBox(10, t, dl);
        box.getStyleClass().add("wsp-viewBox");
        artifactViewerHost.getChildren().setAll(box);
    }

    private void openImageArtifact(Artifact artifact, int fileObjectId) {
        try {
            FileObject fo = fileObjectService.findById(fileObjectId);
            if (fo == null) { showPlaceholder("Missing file_object record."); return; }

            String url = fileObjectService.presignedDownloadUrl(fo.getStorageKey(), Duration.ofMinutes(10));

            ImageView iv = new ImageView(new Image(url, true));
            iv.setPreserveRatio(true);
            iv.setSmooth(true);
            // fit inside viewer host, with padding
            iv.fitWidthProperty().bind(artifactViewerHost.widthProperty().subtract(40));
            iv.fitHeightProperty().bind(artifactViewerHost.heightProperty().subtract(90));
            iv.setPickOnBounds(true);


            Button dl = new Button("Download");
            dl.getStyleClass().add("prf-outlineBtn");
            dl.setOnAction(e -> {
                try {
                    downloadToDisk(fileObjectId, safeFileName(artifact.getArtifactName(), "png"));
                    setError(null);
                } catch (Exception ex) {
                    setError(ex.getMessage());
                }
            });

            VBox box = new VBox(10, iv, dl);
            box.getStyleClass().add("wsp-viewBox");
            artifactViewerHost.getChildren().setAll(box);
            setError(null);
        } catch (Exception e) {
            setError(e.getMessage());
        }
    }



    private void openTextLikeArtifact(Artifact artifact, int fileObjectId) {
        String txt = artifact.getTextContent() == null ? "" : artifact.getTextContent();

        TextArea ta = new TextArea(txt);
        ta.setEditable(false);
        ta.setWrapText(true);

        VBox box = new VBox(10);
        box.getStyleClass().add("wsp-viewBox");
        box.getChildren().add(ta);

        if ("LINK".equalsIgnoreCase(artifact.getArtifactType())) {
            Button open = new Button("Open link");
            open.getStyleClass().add("prf-outlineBtn");
            open.setOnAction(e -> {
                try {
                    String url = txt.trim();
                    if (!url.isBlank()) com.example.guser.AppHostServices.get().showDocument(url);
                } catch (Exception ex) {
                    setError(ex.getMessage());
                }
            });
            box.getChildren().add(open);
        }

        Button dl = new Button("Download snapshot file");
        dl.getStyleClass().add("prf-outlineBtn");
        dl.setOnAction(e -> {
            try {
                downloadToDisk(fileObjectId, safeFileName(artifact.getArtifactName(), "txt"));
                setError(null);
            } catch (Exception ex) {
                setError(ex.getMessage());
            }
        });
        box.getChildren().add(dl);

        artifactViewerHost.getChildren().setAll(box);
    }


    private void openCodeArtifact(int fileObjectId) {
        try {
            FileObject fo = fileObjectService.findById(fileObjectId);
            if (fo == null) {
                showPlaceholder("Missing file_object record.");
                return;
            }

            FXMLLoader loader = new FXMLLoader(getClass().getResource("/com/example/guser/code_viewer.fxml"));
            if (loader.getLocation() == null) {
                showPlaceholder("code_viewer.fxml not found on classpath.");
                return;
            }

            Node node = loader.load();

            CodeViewerController c = loader.getController();
            c.init(codeBrowseService, fileObjectService, fo.getStorageKey());

            artifactViewerHost.getChildren().setAll(node);
            setError(null);
        } catch (Exception e) {
            setError(e.getMessage());
        }
    }

    private void showPlaceholder(String msg) {
        viewerPlaceholderLabel.setText(msg);
        artifactViewerHost.getChildren().setAll(viewerPlaceholderLabel);
    }

    private void installSnapshotCells() {
        DateTimeFormatter fmt = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm");

        snapshotsListView.setCellFactory(lv -> new ListCell<>() {
            @Override protected void updateItem(Snapshot s, boolean empty) {
                super.updateItem(s, empty);

                setText(null);
                setGraphic(null);

                if (empty || s == null) return;

                Label title = new Label(s.getTitle());
                title.getStyleClass().add("wsp-snapTitle");

                Label msg = new Label(nullToEmpty(s.getMessage()));
                msg.getStyleClass().add("wsp-snapMsg");

                Label date = new Label(s.getCreatedAt() == null ? "" : fmt.format(s.getCreatedAt()));
                date.getStyleClass().add("wsp-snapDate");

                Label badge = new Label(s.isFinal() ? "FINAL" : "SNAPSHOT");
                badge.getStyleClass().add(s.isFinal() ? "wsp-badgeFinal" : "wsp-badge");

                HBox top = new HBox(8, badge, date);
                top.getStyleClass().add("wsp-snapTop");

                VBox box = new VBox(6, top, title, msg);
                box.getStyleClass().add("wsp-snapCell");

                setGraphic(box);
            }
        });
    }



    private void installArtifactCells() {
        artifactsListView.setCellFactory(lv -> new ListCell<>() {
            @Override protected void updateItem(ArtifactRow row, boolean empty) {
                super.updateItem(row, empty);

                // Always reset cell state because cells are reused
                setText(null);
                setGraphic(null);
                setDisable(false);
                getStyleClass().remove("wsp-headerCell");

                if (empty || row == null) return;

                if (row.kind == ArtifactRowKind.HEADER) {
                    setText(row.headerTitle);
                    setDisable(true);
                    getStyleClass().add("wsp-headerCell");
                    return;
                }

                String lang = row.artifact.getLanguage() == null ? "" : (" • " + row.artifact.getLanguage());
                String availability = (row.fileObjectId == null ? " • (not in this snapshot)" : "");
                setText(row.artifact.getArtifactName() + "\n" + row.artifact.getArtifactType() + lang + availability);
            }
        });
    }


    private void setError(String msg) {
        boolean show = msg != null && !msg.isBlank();
        trackErrorLabel.setText(show ? msg : "");
        trackErrorLabel.setVisible(show);
        trackErrorLabel.setManaged(show);
    }

    private String typeLabel(String t) {
        return switch (t) {
            case "CODE" -> "Code";
            case "DOCUMENT" -> "Documents";
            case "IMAGE" -> "Images";
            case "VIDEO" -> "Videos";
            case "LINK" -> "Links";
            case "TEXT" -> "Text";
            default -> t;
        };
    }

    private static String safeUpper(String s) { return s == null ? "" : s.trim().toUpperCase(); }
    private static String nullToEmpty(String s) { return s == null ? "" : s; }

    // --- UI row type: no new VM files needed ---
    private enum ArtifactRowKind { HEADER, ITEM }

    private static class ArtifactRow {
        final ArtifactRowKind kind;
        final String headerTitle;
        final Artifact artifact;
        Integer fileObjectId;

        private ArtifactRow(ArtifactRowKind kind, String headerTitle, Artifact artifact) {
            this.kind = kind;
            this.headerTitle = headerTitle;
            this.artifact = artifact;
        }
        static ArtifactRow header(String title) { return new ArtifactRow(ArtifactRowKind.HEADER, title, null); }
        static ArtifactRow item(Artifact a) { return new ArtifactRow(ArtifactRowKind.ITEM, null, a); }
    }

    private boolean overLimit(File f) {
        return f != null && f.length() > MAX_UPLOAD_BYTES;
    }

    private void showInfo(String msg) {
        setError(msg); // reuse your error label area as info for now
    }

    private void promptUploadForCode(Artifact artifact) throws Exception {
        Alert choice = new Alert(Alert.AlertType.CONFIRMATION);
        choice.setTitle("Upload code");
        choice.setHeaderText("Choose upload method");
        ButtonType folderBtn = new ButtonType("Choose folder (zip)");
        ButtonType zipBtn = new ButtonType("Choose ZIP");
        ButtonType cancel = ButtonType.CANCEL;
        choice.getButtonTypes().setAll(folderBtn, zipBtn, cancel);

        Optional<ButtonType> res = choice.showAndWait();
        if (res.isEmpty() || res.get() == cancel) return;

        if (res.get() == folderBtn) {
            DirectoryChooser dc = new DirectoryChooser();
            dc.setTitle("Select project folder");
            File dir = dc.showDialog(trackDetailPane.getScene().getWindow());
            if (dir == null) return;

            File zipped = zipDirectoryToTemp(dir.toPath());
            if (overLimit(zipped)) {
                zipped.delete();
                throw new IllegalArgumentException("ZIP is larger than 50MB.");
            }

            fileObjectService.uploadNewVersion(candidateId, track.getId(), artifact.getId(), zipped);
            zipped.delete();

            showInfo("Code uploaded. Now create a snapshot to freeze this version.");
            return;
        }

        // ZIP upload
        FileChooser fc = new FileChooser();
        fc.setTitle("Select ZIP");
        fc.getExtensionFilters().add(new FileChooser.ExtensionFilter("ZIP", "*.zip"));
        File zip = fc.showOpenDialog(trackDetailPane.getScene().getWindow());
        if (zip == null) return;

        if (overLimit(zip)) throw new IllegalArgumentException("File is larger than 50MB.");

        fileObjectService.uploadNewVersion(candidateId, track.getId(), artifact.getId(), zip);
        showInfo("ZIP uploaded. Now create a snapshot to freeze this version.");
    }

    private void promptUploadSingleFile(Artifact artifact, String type) throws Exception {
        FileChooser fc = new FileChooser();
        fc.setTitle("Select file to upload");

        // Simple filters (you can expand later)
        if ("DOCUMENT".equals(type)) {
            fc.getExtensionFilters().addAll(
                    new FileChooser.ExtensionFilter("Documents", "*.pdf", "*.docx", "*.pptx", "*.txt"),
                    new FileChooser.ExtensionFilter("All files", "*.*")
            );
        } else if ("IMAGE".equals(type)) {
            fc.getExtensionFilters().addAll(
                    new FileChooser.ExtensionFilter("Images", "*.png", "*.jpg", "*.jpeg", "*.webp"),
                    new FileChooser.ExtensionFilter("All files", "*.*")
            );
        } else if ("VIDEO".equals(type)) {
            fc.getExtensionFilters().addAll(
                    new FileChooser.ExtensionFilter("Videos", "*.mp4", "*.mov"),
                    new FileChooser.ExtensionFilter("All files", "*.*")
            );
        }

        File f = fc.showOpenDialog(trackDetailPane.getScene().getWindow());
        if (f == null) return;

        if (overLimit(f)) throw new IllegalArgumentException("File is larger than 50MB.");

        fileObjectService.uploadNewVersion(candidateId, track.getId(), artifact.getId(), f);
        showInfo("File uploaded. Now create a snapshot to freeze this version.");
    }
    private File zipDirectoryToTemp(Path rootDir) throws IOException {
        // Excludes (simple): you can tweak
        List<String> excludedNames = List.of(".git", "node_modules", "target", "dist", "build", ".idea");

        Path zipPath = Files.createTempFile("carrieri-code-", ".zip");
        try (ZipOutputStream zos = new ZipOutputStream(Files.newOutputStream(zipPath))) {
            Files.walk(rootDir)
                    .filter(p -> !Files.isDirectory(p))
                    .forEach(p -> {
                        try {
                            Path rel = rootDir.relativize(p);
                            // skip excluded folders
                            for (Path part : rel) {
                                if (excludedNames.contains(part.toString())) return;
                            }

                            String entryName = rel.toString().replace("\\", "/");
                            zos.putNextEntry(new ZipEntry(entryName));
                            Files.copy(p, zos);
                            zos.closeEntry();
                        } catch (IOException ex) {
                            throw new UncheckedIOException(ex);
                        }
                    });
        } catch (UncheckedIOException ex) {
            try { Files.deleteIfExists(zipPath); } catch (Exception ignored) {}
            throw ex.getCause();
        }

        return zipPath.toFile();
    }

    private void downloadToDisk(int fileObjectId, String baseNameNoExt) throws Exception {
        FileObject fo = fileObjectService.findById(fileObjectId);
        if (fo == null) throw new IllegalStateException("Missing file_object record.");

        String ext = extFromMimeOrKey(fo);
        String suggested = safeFileName(baseNameNoExt, ext);

        String url = fileObjectService.presignedDownloadUrl(fo.getStorageKey(), Duration.ofMinutes(10));

        FileChooser fc = new FileChooser();
        fc.setTitle("Save file");
        if (suggested != null && !suggested.isBlank()) fc.setInitialFileName(suggested);

        var dest = fc.showSaveDialog(trackDetailPane.getScene().getWindow());
        if (dest == null) return;

        HttpClient client = HttpClient.newBuilder()
                .followRedirects(HttpClient.Redirect.NORMAL)
                .build();

        HttpRequest req = HttpRequest.newBuilder()
                .uri(URI.create(url))
                .GET()
                .build();

        HttpResponse<Path> res = client.send(req, HttpResponse.BodyHandlers.ofFile(dest.toPath()));
        if (res.statusCode() < 200 || res.statusCode() >= 300) {
            throw new IllegalStateException("Download failed (HTTP " + res.statusCode() + ")");
        }
    }

    private static String extFromMimeOrKey(FileObject fo) {
        String mt = fo.getMimeType() == null ? "" : fo.getMimeType().toLowerCase();
        if (mt.contains("pdf")) return "pdf";
        if (mt.contains("zip")) return "zip";
        if (mt.contains("png")) return "png";
        if (mt.contains("jpeg") || mt.contains("jpg")) return "jpg";
        if (mt.contains("webp")) return "webp";
        if (mt.contains("mp4")) return "mp4";
        if (mt.contains("quicktime")) return "mov";
        if (mt.contains("plain")) return "txt";

        String key = fo.getStorageKey() == null ? "" : fo.getStorageKey();
        int i = key.lastIndexOf('.');
        if (i > -1 && i < key.length() - 1) return key.substring(i + 1).toLowerCase();

        return "bin";
    }






}
