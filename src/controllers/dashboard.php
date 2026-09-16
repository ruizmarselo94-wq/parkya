<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../models/parking_lot_model.php';
require_once __DIR__ . '/../models/session_model.php';

requireLogin();

$lots = lotOccupancySummary($pdo);
$activeSessions = listActiveSessions($pdo);

$title = 'Dashboard';
require __DIR__ . '/../views/layout/header.php';
require __DIR__ . '/../views/dashboard/index.php';
require __DIR__ . '/../views/layout/footer.php';
