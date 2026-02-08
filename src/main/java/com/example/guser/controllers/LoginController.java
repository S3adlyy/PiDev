package com.example.guser.controllers;

import com.example.guser.SceneManager;
import entities.User;
import services.UserService;
import session.SessionContext;

import javafx.fxml.FXML;
import javafx.scene.control.*;

public class LoginController {

    @FXML private TextField emailField;
    @FXML private PasswordField passwordField;

    @FXML private TextField passwordVisibleField;
    @FXML private CheckBox showPassCheck;

    @FXML private Label errorLabel;

    private UserService userService;

    @FXML
    public void initialize() {
        userService = new UserService();
        hideError();

    }

    @FXML
    private void onTogglePassword() {
        boolean show = showPassCheck.isSelected();
        if (show) {
            passwordVisibleField.setText(passwordField.getText());
            passwordVisibleField.setVisible(true);
            passwordVisibleField.setManaged(true);
            passwordField.setVisible(false);
            passwordField.setManaged(false);
        } else {
            passwordField.setText(passwordVisibleField.getText());
            passwordField.setVisible(true);
            passwordField.setManaged(true);
            passwordVisibleField.setVisible(false);
            passwordVisibleField.setManaged(false);
        }
    }

    @FXML
    private void onLogin() {
        hideError();
        try {
            String email = emailField.getText().trim();
            String pass = showPassCheck.isSelected() ? passwordVisibleField.getText() : passwordField.getText();

            User u = userService.login(email, pass);
            SessionContext.setCurrentUser(u);

            switch (u.getRoles()) {
                case "CANDIDATE", "RECRUITER" -> {
                    session.ProfileViewContext.clear();
                    SceneManager.switchTo("/com/example/guser/profile.fxml", "Carrieri • Profile");
                }
                case "ADMIN" -> SceneManager.switchTo("/com/example/guser/admin_home.fxml", "Carrieri • Admin");
                default -> showError("Unknown role: " + u.getRoles());
            }

        } catch (Exception e) {
            showError(e.getMessage());
            e.printStackTrace();
        }
    }

    @FXML
    private void onGoSignup() {
        SceneManager.switchTo("/com/example/guser/signup.fxml", "Carrieri • Sign up");
    }

    private void showError(String msg) {
        errorLabel.setText(msg == null ? "Login failed." : msg);
        errorLabel.setVisible(true);
        errorLabel.setManaged(true);
    }

    private void hideError() {
        errorLabel.setText("");
        errorLabel.setVisible(false);
        errorLabel.setManaged(false);
    }
}
