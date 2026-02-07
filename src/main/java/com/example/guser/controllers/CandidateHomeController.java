package com.example.guser.controllers;

import com.example.guser.SceneManager;
import javafx.fxml.FXML;
import javafx.scene.shape.Circle;
import session.SessionContext;

public class CandidateHomeController {
    @FXML
    private void logout() {
        SessionContext.clear();
        SceneManager.switchTo("/com/example/guser/login.fxml", "Carrieri • Sign in");
    }

    //method hedhi trajaali image mel s3key najem nestaamalha fel profiles wala kol manheb nwari taswira circle
    //kima haka: setCircleImageFromS3Key(profileAvatarCircle, user.getProfilepic());
    //setCircleImageFromS3Key(orgLogoCircle, user.getLogourl());
    private void setCircleImageFromS3Key(Circle circle, String s3Key) {
        try {
            if (s3Key == null || s3Key.isBlank()) {
                circle.setFill(javafx.scene.paint.Paint.valueOf("rgba(255,255,255,0.18)"));
                return;
            }

            try (utils.S3StorageService s3 = new utils.S3StorageService("carrieri-storage-dev-islem", "eu-west-3")) {
                String url = s3.presignedGetUrl(s3Key, java.time.Duration.ofMinutes(10)); // expires automatically [web:168][web:9]
                javafx.scene.image.Image img = new javafx.scene.image.Image(url, true);
                circle.setFill(new javafx.scene.paint.ImagePattern(img));
            }
        } catch (Exception e) {
            circle.setFill(javafx.scene.paint.Paint.valueOf("rgba(255,255,255,0.18)"));
        }
    }

}
