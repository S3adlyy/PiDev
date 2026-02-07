package utils;

import java.util.UUID;

public class S3KeyUtil {

    public static String profilePicKey(String extNoDot) {
        return "users/profile-pics/" + UUID.randomUUID() + "." + extNoDot;
    }

    public static String orgLogoKey(String extNoDot) {
        return "org/logos/" + UUID.randomUUID() + "." + extNoDot;
    }

    public static String extNoDotOrDefault(String filename, String def) {
        if (filename == null) return def;
        int i = filename.lastIndexOf('.');
        if (i < 0 || i == filename.length() - 1) return def;
        return filename.substring(i + 1).toLowerCase();
    }

    public static String contentTypeFromExt(String extNoDot) {
        return switch (extNoDot.toLowerCase()) {
            case "jpg", "jpeg" -> "image/jpeg";
            case "png" -> "image/png";
            case "webp" -> "image/webp";
            default -> "application/octet-stream";
        };
    }
}
