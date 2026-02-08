package com.exemple.grecrutement;

import entities.RenduMission;
import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.*;
import services.RenduMissionService;
import services.MissionService;
import entities.Mission;

import java.net.URL;
import java.util.ResourceBundle;

public class RenduAddController implements Initializable {

    @FXML private TextField txtCandidatId;
    @FXML private TextField txtMissionId;
    @FXML private TextArea txtCode;
    @FXML private ComboBox<String> comboMissionType;
    @FXML private ProgressIndicator progress;
    @FXML private Label lblResultat;
    @FXML private TextArea lblMissionDescription;

    private RenduMissionService service;
    private MissionService missionService;

    @Override
    public void initialize(URL url, ResourceBundle rb) {
        service = new RenduMissionService();
        missionService = new MissionService();

        comboMissionType.getItems().addAll(
                "ADDITION", "FACTORIAL", "FIBONACCI", "PRIME_CHECK"
        );
        comboMissionType.setValue("ADDITION");

        // Clear any preset mission ID on initialization
        txtMissionId.clear();
        lblMissionDescription.setText("Select a mission to see its description");

        // Make the mission description area non-editable
        lblMissionDescription.setEditable(false);
        lblMissionDescription.setWrapText(true);
    }

    // Update this method to also set mission description
    public void setMissionId(int missionId) {
        Platform.runLater(() -> {
            txtMissionId.setText(String.valueOf(missionId));

            // Load and display mission description
            try {
                Mission mission = missionService.getById(missionId); // Use existing method
                if (mission != null) {
                    String description = mission.getDescription();
                    int minScore = mission.getScore_min();

                    StringBuilder missionInfo = new StringBuilder();
                    missionInfo.append("📋 Mission Details:\n");
                    missionInfo.append("────────────────────\n");
                    missionInfo.append("ID: ").append(missionId).append("\n");
                    missionInfo.append("Description:\n").append(description != null ? description : "No description").append("\n\n");
                    missionInfo.append("📊 Minimum Score: ").append(minScore).append("%");

                    lblMissionDescription.setText(missionInfo.toString());
                } else {
                    lblMissionDescription.setText("❌ Mission not found (ID: " + missionId + ")");
                }
            } catch (Exception e) {
                lblMissionDescription.setText("⚠️ Error loading mission details: " + e.getMessage());
                e.printStackTrace();
            }
        });
    }

    @FXML
    private void evaluer() {
        if (txtCode.getText().isEmpty()) {
            alert("Erreur", "Please enter your Python code");
            return;
        }

        int missionId, candidatId;
        try {
            missionId = Integer.parseInt(txtMissionId.getText());
            candidatId = Integer.parseInt(txtCandidatId.getText());
        } catch (Exception e) {
            alert("Erreur", "Invalid IDs. Please enter valid numbers for Mission ID and Candidate ID");
            return;
        }

        progress.setVisible(true);
        lblResultat.setText("Evaluating code...");

        new Thread(() -> {
            try {
                RenduMission r = service.evaluerCodePython(
                        txtCode.getText(), missionId, candidatId);

                Platform.runLater(() -> {
                    progress.setVisible(false);
                    String resultText = "🎯 Score: " + r.getScore() + "% - " + r.getResultat();

                    if (r.isAccepted()) {
                        resultText += "\n✅ Code Accepted!";
                        lblResultat.setStyle("-fx-text-fill: #10b981; -fx-font-weight: bold; -fx-font-size: 14px;");
                    } else {
                        resultText += "\n❌ Code Rejected";
                        lblResultat.setStyle("-fx-text-fill: #ef4444; -fx-font-weight: bold; -fx-font-size: 14px;");
                    }

                    lblResultat.setText(resultText);
                });
            } catch (Exception e) {
                Platform.runLater(() -> {
                    progress.setVisible(false);
                    lblResultat.setText("❌ Error: " + e.getMessage());
                    lblResultat.setStyle("-fx-text-fill: #f59e0b; -fx-font-weight: bold;");
                });
            }
        }).start();
    }

    private void alert(String title, String message) {
        Alert alert = new Alert(Alert.AlertType.WARNING);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }
}