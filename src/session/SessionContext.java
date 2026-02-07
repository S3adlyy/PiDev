package session;
import entities.User;

public final class SessionContext {
    private static User currentUser;

    private SessionContext() {}

    public static User getCurrentUser() { return currentUser; }
    public static void setCurrentUser(User u) { currentUser = u; }
    public static void clear() { currentUser = null; }

    public static boolean isLoggedIn() { return currentUser != null; }
}

