<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../models/user_model.php';

requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        createUser($pdo, [
            'username'  => trim($_POST['username'] ?? ''),
            'password'  => $_POST['password'] ?? '',
            'full_name' => trim($_POST['full_name'] ?? ''),
            'email'     => trim($_POST['email'] ?? ''),
            'role'      => $_POST['role'] ?? 'operator',
            'phone'     => trim($_POST['phone'] ?? ''),
        ]);
        flash('success', 'Usuario creado.');
    } catch (PDOException $e) {
        flash('error', $e->getCode() === '23505' ? 'Ese usuario o email ya existe.' : 'No se pudo crear el usuario.');
    }
    redirect('users.php');
}

$users = listUsers($pdo);

$title = 'Usuarios';
require __DIR__ . '/../views/layout/header.php';
require __DIR__ . '/../views/users/index.php';
require __DIR__ . '/../views/layout/footer.php';
