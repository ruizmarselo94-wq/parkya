<?php $user = currentUser(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'ParkYa') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<?php if ($user): ?>
<header class="navbar">
    <div class="brand">ParkYa</div>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <?php if (in_array($user['role'], ['admin', 'operator'], true)): ?>
            <a href="checkin.php">Entrada</a>
        <?php endif; ?>
        <a href="checkout.php">Salida</a>
        <?php if (in_array($user['role'], ['admin', 'cashier'], true)): ?>
            <a href="reports.php">Reportes</a>
        <?php endif; ?>
        <?php if ($user['role'] === 'admin'): ?>
            <a href="lots.php">Estacionamientos</a>
            <a href="rates.php">Tarifas</a>
            <a href="users.php">Usuarios</a>
        <?php endif; ?>
    </nav>
    <div class="session">
        <?= e($user['full_name']) ?> (<?= e($user['role']) ?>)
        <a href="logout.php">Salir</a>
    </div>
</header>
<?php endif; ?>
<main class="container">
    <?php if ($error = flash('error')): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>
    <?php if ($success = flash('success')): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>
