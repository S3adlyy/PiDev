package com.exemple.grecrutement;

import entities.RenduMission;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import services.RenduMissionService;

import java.net.URL;
import java.util.ResourceBundle;

public class RenduListController implements Initializable {

    @FXML private TableView<RenduMission> table;
    @FXML private TableColumn<RenduMission, Integer> colId;
    @FXML private TableColumn<RenduMission, Integer> colScore;
    @FXML private TableColumn<RenduMission, String> colResultat;
    @FXML private TableColumn<RenduMission, Integer> colMission;
    @FXML private TableColumn<RenduMission, Integer> colCandidat;

    private RenduMissionService service;

    @Override
    public void initialize(URL url, ResourceBundle rb) {
        service = new RenduMissionService();

        colId.setCellValueFactory(new PropertyValueFactory<>("id"));
        colScore.setCellValueFactory(new PropertyValueFactory<>("score"));
        colResultat.setCellValueFactory(new PropertyValueFactory<>("resultat"));
        colMission.setCellValueFactory(new PropertyValueFactory<>("missionId"));
        colCandidat.setCellValueFactory(new PropertyValueFactory<>("candidatId"));

        load();
    }

    @FXML
    private void load() {
        table.getItems().setAll(service.afficherRenduMissions());
    }
}
