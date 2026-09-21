<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../models/user_model.php';

requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';

    if ($action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        try {
            updateUser($pdo, $id, [
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email'     => trim($_POST['email'] ?? ''),
                'role'      => $_POST['role'] ?? 'operator',
                'phone'     => trim($_POST['phone'] ?? ''),
                'password'  => $_POST['password'] ?? '',
            ]);
            flash('success', 'Usuario actualizado.');
        } catch (PDOException $e) {
            flash('error', $e->getCode() === '23505' ? 'Ese email ya está en uso.' : 'No se pudo actualizar el usuario.');
        }
        redirect('users.php');
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        try {
            deleteUser($pdo, $id);
            flash('success', 'Usuario eliminado.');
        } catch (PDOException $e) {
            flash('error', $e->getCode() === '23503'
                ? 'No se puede eliminar: este usuario tiene sesiones o pagos registrados.'
                : 'No se pudo eliminar el usuario.');
        }
        redirect('users.php');
    }

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

$editId = (int) ($_GET['edit_id'] ?? 0);
$editUser = $editId > 0 ? findUser($pdo, $editId) : null;

$users = listUsers($pdo);

$title = 'Usuarios';
require __DIR__ . '/../views/layout/header.php';
require __DIR__ . '/../views/users/index.php';
require __DIR__ . '/../views/layout/footer.php';
