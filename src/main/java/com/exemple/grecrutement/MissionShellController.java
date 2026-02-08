package com.exemple.grecrutement;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.layout.StackPane;

public class MissionShellController {

    @FXML
    private StackPane contentPane;

    @FXML
    public void initialize() {
        showMissionList(); // default view
    }

    @FXML
    private void showAddMission() {
        loadView("mission-add.fxml");
    }

    @FXML
    private void showMissionList() {
        loadView("mission-list.fxml");
    }

    @FXML
    private void showRenduAdd() {
        loadView("rendu-add.fxml");
    }

    @FXML
    private void showRenduList() {
        loadView("rendu-list.fxml");
    }

    private void loadView(String fxml) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource(fxml));
            Node view = loader.load();
            contentPane.getChildren().setAll(view);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
