package com.exemple.grecrutement;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;

public class HelloApplication extends Application {
    @Override
    public void start(Stage primaryStage) throws Exception {
        // Load FXML
        FXMLLoader loader = new FXMLLoader(getClass().getResource("mission-shell.fxml"));
        Parent root = loader.load();

        Scene scene = new Scene(root);


        // Setup stage
        primaryStage.setTitle("Mission Management Platform");
        primaryStage.setScene(scene);
        primaryStage.setMaximized(true); // For full-screen experience
        primaryStage.show();
    }

    public static void main(String[] args) {
        launch(args);
    }
}