package com.example.guser.controllers;

import javafx.fxml.FXML;
import javafx.scene.control.*;
import services.CodeBrowseService;
import services.FileObjectService;

import java.nio.file.Files;
import java.nio.file.Path;
import java.time.Duration;
import java.util.Optional;

public class CodeViewerController {

    public record ZipEntryVM(String name, String fullPath, boolean directory) {
        @Override public String toString() { return name; } // default label if no cellFactory
    }

    @FXML private SplitPane codeSplitPane;
    @FXML private Button downloadZipBtn;

    @FXML private TreeView<ZipEntryVM> filesTreeView;

    @FXML private Label filePathLabel;
    @FXML private Button downloadFileBtn;
    @FXML private TextArea codeTextArea;

    private CodeBrowseService codeBrowseService;
    private FileObjectService fileObjectService;

    private String storageKey;
    private Path zipPath;
    private String selectedEntryPath;

    @FXML
    private void initialize() {
        // Render only the name in the tree
        filesTreeView.setCellFactory(tv -> new TreeCell<>() {
            @Override protected void updateItem(ZipEntryVM v, boolean empty) {
                super.updateItem(v, empty);
                if (empty || v == null) { setText(null); return; }
                setText(v.name());
                // Optional: you can style directories differently here later
            }
        });

        filesTreeView.getSelectionModel().selectedItemProperty().addListener((obs, o, item) -> {
            if (item == null || item.getValue() == null) return;
            ZipEntryVM v = item.getValue();
            if (v.directory()) return;
            openFile(v.fullPath());
        });

        filePathLabel.setText("—");
        codeTextArea.clear();
        downloadFileBtn.setDisable(true);
    }

    public void init(CodeBrowseService codeBrowseService, FileObjectService fileObjectService, String storageKey) throws Exception {
        this.codeBrowseService = codeBrowseService;
        this.fileObjectService = fileObjectService;
        this.storageKey = storageKey;

        this.zipPath = codeBrowseService.downloadZipToTemp(storageKey);
        CodeBrowseService.ZipNode rootNode = codeBrowseService.buildTree(zipPath);

        TreeItem<ZipEntryVM> root = new TreeItem<>(new ZipEntryVM("root", "", true));
        root.setExpanded(true);

        for (CodeBrowseService.ZipNode child : rootNode.getChildren()) {
            root.getChildren().add(toTreeItem(child));
        }

        filesTreeView.setRoot(root);
        filesTreeView.setShowRoot(false);

        // Default: README.md if at root, else first file (your requirement)
        Optional<String> readme = codeBrowseService.findReadmeAtRoot(rootNode);
        if (readme.isPresent()) {
            openFile(readme.get());
            return;
        }
        codeBrowseService.findFirstFile(rootNode).ifPresent(this::openFile);
    }

    private TreeItem<ZipEntryVM> toTreeItem(CodeBrowseService.ZipNode n) {
        TreeItem<ZipEntryVM> item = new TreeItem<>(new ZipEntryVM(n.getName(), n.getFullPath(), n.isDirectory()));
        for (CodeBrowseService.ZipNode c : n.getChildren()) {
            item.getChildren().add(toTreeItem(c));
        }
        return item;
    }

    private void openFile(String entryPath) {
        try {
            String content = codeBrowseService.readTextFile(zipPath, entryPath);
            selectedEntryPath = entryPath;

            filePathLabel.setText(entryPath);
            codeTextArea.setText(content);
            downloadFileBtn.setDisable(false);
        } catch (Exception e) {
            filePathLabel.setText(entryPath);
            codeTextArea.setText("Failed to open file: " + e.getMessage());
            downloadFileBtn.setDisable(true);
        }
    }

    @FXML
    private void onDownloadZip() {
        try {
            String url = fileObjectService.presignedDownloadUrl(storageKey, Duration.ofMinutes(10));
            // For now: show URL; later we open it in browser or download to disk
            codeTextArea.setText("Download URL:\n" + url);
        } catch (Exception e) {
            codeTextArea.setText("Failed: " + e.getMessage());
        }
    }

    @FXML
    private void onDownloadFile() {
        // Next iteration: choose save location and extract the selected entry to disk.
    }

    public void dispose() {
        try { if (zipPath != null) Files.deleteIfExists(zipPath); } catch (Exception ignored) {}
    }
}
