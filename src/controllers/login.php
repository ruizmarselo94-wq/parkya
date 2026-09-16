<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (currentUser() !== null) {
    redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (attemptLogin($pdo, $username, $password)) {
        redirect('dashboard.php');
    }

    flash('error', 'Usuario o contraseña incorrectos.');
}

$title = 'Iniciar sesión';
require __DIR__ . '/../views/layout/header.php';
require __DIR__ . '/../views/auth/login.php';
require __DIR__ . '/../views/layout/footer.php';
