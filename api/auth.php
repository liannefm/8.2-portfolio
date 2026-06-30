<?php
session_start();

define('ADMIN_USER', 'lianne');
define('ADMIN_PASS_HASH', '$2y$10$0Aoe0J6ZLzlcAu5x/4A98uZNuAUik7vuTwWsxBghYqW3hBsVQVnWy');

function isLoggedIn(): bool {
    return !empty($_SESSION['admin_logged_in']);
}

function requireAuth(): void {
    if (!isLoggedIn()) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'multipart')) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Niet ingelogd']);
            exit;
        }
        header('Location: admin.php?login=1');
        exit;
    }
}

function attemptLogin(string $user, string $pass): bool {
    if ($user === ADMIN_USER && password_verify($pass, ADMIN_PASS_HASH)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $user;
        return true;
    }
    return false;
}

function logout(): void {
    $_SESSION = [];
    session_destroy();
}
