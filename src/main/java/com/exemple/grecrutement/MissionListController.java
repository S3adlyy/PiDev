package com.exemple.grecrutement;

import entities.Mission;
import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.*;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.util.Callback;
import services.MissionService;

import java.net.URL;
import java.util.ResourceBundle;

public class MissionListController implements Initializable {

    @FXML
    private TableView<Mission> missionTable;

    @FXML
    private TableColumn<Mission, Integer> idColumn;

    @FXML
    private TableColumn<Mission, String> descriptionColumn;

    @FXML
    private TableColumn<Mission, Integer> scoreColumn;

    @FXML
    private TableColumn<Mission, Integer> creatorColumn;

    private final MissionService missionService = new MissionService();

    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        // Remove ALL CSS from the table first
        missionTable.getStyleClass().clear();
        missionTable.setStyle("");

        configureTable();
        loadMissions();
        setupDoubleClickHandler();

        // Force apply styling after table is populated
        Platform.runLater(this::forceBlackText);
    }

    private void configureTable() {
        // Remove any existing styles
        idColumn.setStyle("");
        descriptionColumn.setStyle("");
        scoreColumn.setStyle("");
        creatorColumn.setStyle("");

        // Configure columns with proper property names
        idColumn.setCellValueFactory(new PropertyValueFactory<>("id"));
        descriptionColumn.setCellValueFactory(new PropertyValueFactory<>("description"));
        scoreColumn.setCellValueFactory(new PropertyValueFactory<>("score_min"));
        creatorColumn.setCellValueFactory(new PropertyValueFactory<>("created_by_id"));

        // Set simple cell factories that force black text
        setSimpleCellFactories();

        // Apply table styling
        missionTable.setStyle(
                "-fx-background-color: white;" +
                        "-fx-border-color: #ddd;" +
                        "-fx-border-radius: 8;" +
                        "-fx-background-radius: 8;" +
                        "-fx-padding: 0;" +
                        "-fx-control-inner-background: white;" +
                        "-fx-text-fill: black;" +
                        "-fx-font-size: 14px;"
        );

        // Style column headers
        String headerStyle =
                "-fx-background-color: #4CAF50;" +
                        "-fx-text-fill: white;" +
                        "-fx-font-weight: bold;" +
                        "-fx-font-size: 14px;" +
                        "-fx-padding: 12 15;" +
                        "-fx-alignment: CENTER;";

        idColumn.setStyle(headerStyle);
        descriptionColumn.setStyle(headerStyle);
        scoreColumn.setStyle(headerStyle);
        creatorColumn.setStyle(headerStyle);
    }

    private void setSimpleCellFactories() {
        // ID Column
        idColumn.setCellFactory(col -> new TableCell<Mission, Integer>() {
            @Override
            protected void updateItem(Integer item, boolean empty) {
                super.updateItem(item, empty);
                if (empty || item == null) {
                    setText(null);
                    setStyle("");
                } else {
                    setText(String.valueOf(item));
                    setStyle("-fx-text-fill: black; -fx-alignment: CENTER; -fx-font-weight: bold;");
                }
            }
        });

        // Description Column
        descriptionColumn.setCellFactory(col -> new TableCell<Mission, String>() {
            @Override
            protected void updateItem(String item, boolean empty) {
                super.updateItem(item, empty);
                if (empty || item == null) {
                    setText(null);
                    setStyle("");
                } else {
                    setText(item);
                    setStyle("-fx-text-fill: black; -fx-alignment: CENTER_LEFT;");
                }
            }
        });

        // Score Column
        scoreColumn.setCellFactory(col -> new TableCell<Mission, Integer>() {
            @Override
            protected void updateItem(Integer item, boolean empty) {
                super.updateItem(item, empty);
                if (empty || item == null) {
                    setText(null);
                    setStyle("");
                } else {
                    setText(String.valueOf(item));

                    // Color based on score
                    String color;
                    if (item >= 80) {
                        color = "#10b981";
                    } else if (item >= 60) {
                        color = "#f59e0b";
                    } else if (item >= 40) {
                        color = "#f97316";
                    } else {
                        color = "#ef4444";
                    }

                    setStyle("-fx-text-fill: " + color + "; -fx-alignment: CENTER; -fx-font-weight: bold;");
                }
            }
        });

        // Creator Column
        creatorColumn.setCellFactory(col -> new TableCell<Mission, Integer>() {
            @Override
            protected void updateItem(Integer item, boolean empty) {
                super.updateItem(item, empty);
                if (empty || item == null) {
                    setText("N/A");
                    setStyle("-fx-text-fill: #666; -fx-alignment: CENTER; -fx-font-style: italic;");
                } else {
                    setText("User #" + item);
                    setStyle("-fx-text-fill: #3b82f6; -fx-alignment: CENTER; -fx-font-weight: bold;");
                }
            }
        });
    }

    private void forceBlackText() {
        // Force black text on all existing cells
        missionTable.refresh();

        // Also style the table rows
        missionTable.setRowFactory(tv -> {
            TableRow<Mission> row = new TableRow<Mission>() {
                @Override
                protected void updateItem(Mission item, boolean empty) {
                    super.updateItem(item, empty);
                    if (empty || item == null) {
                        setStyle("");
                    } else {
                        // Alternate row colors for better readability
                        if (getIndex() % 2 == 0) {
                            setStyle("-fx-background-color: white;");
                        } else {
                            setStyle("-fx-background-color: #f8f9fa;");
                        }
                    }
                }
            };

            // Double click handler
            row.setOnMouseClicked(event -> {
                if (event.getClickCount() == 2 && !row.isEmpty()) {
                    Mission selectedMission = row.getItem();
                    navigateToRenduAdd(selectedMission);
                }
            });

            return row;
        });
    }

    private void loadMissions() {
        try {
            missionTable.setItems(
                    FXCollections.observableArrayList(missionService.read())
            );
            System.out.println("Loaded " + missionService.read().size() + " missions");

            // Debug: print what's loaded
            debugMissionData();

        } catch (Exception e) {
            showAlert(Alert.AlertType.ERROR, "Load Error", e.getMessage());
            e.printStackTrace();
        }
    }

    private void debugMissionData() {
        try {
            System.out.println("=== DEBUG MISSION DATA ===");
            System.out.println("Table items count: " + (missionTable.getItems() != null ? missionTable.getItems().size() : "null"));

            if (missionTable.getItems() != null) {
                for (Mission m : missionTable.getItems()) {
                    System.out.println("Mission: ID=" + m.getId() +
                            ", Desc=" + m.getDescription() +
                            ", Score=" + m.getScore_min() +
                            ", Creator=" + m.getCreated_by_id());
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void setupDoubleClickHandler() {
        missionTable.setOnMouseClicked(event -> {
            if (event.getClickCount() == 2) {
                Mission selected = missionTable.getSelectionModel().getSelectedItem();
                if (selected != null) {
                    navigateToRenduAdd(selected);
                }
            }
        });
    }

    private void navigateToRenduAdd(Mission mission) {
        try {
            System.out.println("🚀 Navigating to RenduAdd with Mission:");
            System.out.println("   ID: " + mission.getId());
            System.out.println("   Description: " + mission.getDescription());
            System.out.println("   Min Score: " + mission.getScore_min());

            // Pass the mission ID to RenduAddController
            MissionShellController.getInstance().showRenduAddWithMissionId(mission.getId());

        } catch (Exception e) {
            e.printStackTrace();
            showAlert(Alert.AlertType.ERROR, "Navigation Error",
                    "Could not load submission form: " + e.getMessage());
        }
    }

    @FXML
    private void deleteMission() {
        Mission selected = missionTable.getSelectionModel().getSelectedItem();

        if (selected == null) {
            showAlert(Alert.AlertType.WARNING, "No Selection", "Please select a mission.");
            return;
        }

        Alert confirm = new Alert(Alert.AlertType.CONFIRMATION);
        confirm.setTitle("Confirm Deletion");
        confirm.setHeaderText("Delete Mission ID " + selected.getId());
        confirm.setContentText("This action cannot be undone.");

        if (confirm.showAndWait().orElse(ButtonType.CANCEL) == ButtonType.OK) {
            try {
                missionService.supprimer(selected.getId());
                loadMissions();
                showAlert(Alert.AlertType.INFORMATION, "Success", "Mission deleted successfully!");
            } catch (Exception e) {
                showAlert(Alert.AlertType.ERROR, "Delete Error", e.getMessage());
            }
        }
    }

    @FXML
    private void editMission() {
        Mission selected = missionTable.getSelectionModel().getSelectedItem();

        if (selected == null) {
            showAlert(Alert.AlertType.WARNING, "No Selection", "Please select a mission.");
            return;
        }

        MissionShellController.getInstance().showEditMission(selected);
    }

    private void showAlert(Alert.AlertType type, String title, String message) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.show();
    }


}