<?php
# Autenticación basada en sesión PHP (no confundir con parking_sessions)

require_once __DIR__ . '/../models/user_model.php';

function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function requireLogin(): void {
    if (currentUser() === null) {
        redirect('login.php');
    }
}

function requireRole(string ...$roles): void {
    requireLogin();
    if (!in_array(currentUser()['role'], $roles, true)) {
        http_response_code(403);
        exit('No autorizado para esta sección.');
    }
}

function attemptLogin(PDO $pdo, string $username, string $password): bool {
    $user = findUserByUsername($pdo, $username);

    if ($user === null || !$user['is_active'] || !password_verify($password, $user['password'])) {
        return false;
    }

    unset($user['password']);
    $_SESSION['user'] = $user;
    return true;
}

function logout(): void {
    $_SESSION = [];
    session_destroy();
}
