<?php
$user = currentUser();
$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? '');
$navClass = static fn (string $page): string => $page === $currentPage ? 'active' : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'ParkYa') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/app.js" defer></script>
</head>
<body>
<?php if ($user): ?>
<header class="navbar">
    <a class="brand" href="dashboard.php"><span class="logo-mark">P</span>ParkYa</a>
    <nav>
        <a class="<?= $navClass('dashboard.php') ?>" href="dashboard.php">Dashboard</a>
        <?php if (in_array($user['role'], ['admin', 'operator'], true)): ?>
            <a class="<?= $navClass('checkin.php') ?>" href="checkin.php">Entrada</a>
        <?php endif; ?>
        <a class="<?= $navClass('checkout.php') ?>" href="checkout.php">Salida</a>
        <?php if (in_array($user['role'], ['admin', 'cashier'], true)): ?>
            <a class="<?= $navClass('reports.php') ?>" href="reports.php">Reportes</a>
        <?php endif; ?>
        <?php if ($user['role'] === 'admin'): ?>
            <a class="<?= $navClass('lots.php') ?>" href="lots.php">Estacionamientos</a>
            <a class="<?= $navClass('rates.php') ?>" href="rates.php">Tarifas</a>
            <a class="<?= $navClass('users.php') ?>" href="users.php">Usuarios</a>
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
