package com.example.guser.controllers;

import com.example.guser.SceneManager;
import entities.User;
import session.ProfileViewContext;
import session.SessionContext;
import utils.S3StorageService;

import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.image.Image;
import javafx.scene.paint.ImagePattern;
import javafx.scene.shape.Circle;

import java.time.Duration;

public class AppNavController {

    public enum Route { PROFILE, PEOPLE, JOBS }

    private static final String S3_BUCKET = "carrieri-storage-dev-islem";
    private static final String S3_REGION = "eu-west-3";

    @FXML private Button backBtn;
    @FXML private TextField searchField;

    @FXML private Button profileBtn;
    @FXML private Button peopleBtn;
    @FXML private Button jobsBtn;

    @FXML private Circle navAvatar;
    @FXML private Label navNameLabel;
    @FXML private Button logoutBtn;

    private Runnable backAction; // customizable per page

    @FXML
    public void initialize() {
        // Default back action (safe everywhere)
        backAction = this::goToMyProfile;

        if (!SessionContext.isLoggedIn()) return;

        User me = SessionContext.getCurrentUser();
        navNameLabel.setText((safe(me.getFirstname()) + " " + safe(me.getLastname())).trim());

        String key = "RECRUITER".equals(me.getRoles()) ? me.getLogourl() : me.getProfilepic();
        Platform.runLater(() -> renderCircleFromS3Key(navAvatar, key));

        setActive(Route.PROFILE);
    }

    // ===== Customization API (call from page controller) =====
    public void setActive(Route route) {
        setActiveBtn(profileBtn, route == Route.PROFILE);
        setActiveBtn(peopleBtn, route == Route.PEOPLE);
        setActiveBtn(jobsBtn, route == Route.JOBS);
    }

    public void setBackAction(Runnable action) {
        this.backAction = (action == null) ? this::goToMyProfile : action;
    }

    public void setBackVisible(boolean visible) {
        backBtn.setVisible(visible);
        backBtn.setManaged(visible);
    }

    public void setSearchVisible(boolean visible) {
        searchField.setVisible(visible);
        searchField.setManaged(visible);
    }

    // ===== UI handlers =====
    @FXML
    private void onBack() {
        if (backAction != null) backAction.run();
    }

    @FXML
    private void onProfile() {
        goToMyProfile();
    }

    @FXML
    private void onPeople() {
        // later: switch to candidate directory / recruiter directory
        SceneManager.switchTo("/com/example/guser/profile.fxml", "Carrieri • Profile");
    }

    @FXML
    private void onJobs() {
        // placeholder
    }

    @FXML
    private void onLogout() {
        SessionContext.setCurrentUser(null);
        ProfileViewContext.viewUser(null);
        SceneManager.switchTo("/com/example/guser/login.fxml", "Carrieri • Sign in");
    }

    // ===== helpers =====
    private void goToMyProfile() {
        if (!SessionContext.isLoggedIn()) return;
        int meId = SessionContext.getCurrentUser().getId();
        ProfileViewContext.viewUser(meId);
        SceneManager.switchTo("/com/example/guser/profile.fxml", "Carrieri • Profile");
    }

    private static void setActiveBtn(Button btn, boolean active) {
        btn.getStyleClass().remove("navLinkBtnActive");
        if (active) btn.getStyleClass().add("navLinkBtnActive");
    }

    private void renderCircleFromS3Key(Circle circle, String s3Key) {
        try (S3StorageService s3 = new S3StorageService(S3_BUCKET, S3_REGION)) {
            if (s3Key == null || s3Key.isBlank()) return;
            String url = s3.presignedGetUrl(s3Key, Duration.ofMinutes(10));
            Image img = new Image(url, false);
            if (!img.isError()) circle.setFill(new ImagePattern(img));
        } catch (Exception ignored) {}
    }

    private static String safe(String s) { return s == null ? "" : s; }
}
