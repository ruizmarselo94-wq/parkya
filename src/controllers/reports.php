<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../models/parking_lot_model.php';
require_once __DIR__ . '/../models/payment_model.php';

requireRole('admin', 'cashier');

$from = $_GET['from'] ?? date('Y-m-d', strtotime('-7 days'));
$to = $_GET['to'] ?? date('Y-m-d');

$lots = lotOccupancySummary($pdo);
$income = incomeByDateRange($pdo, $from, $to);
$totalIncome = totalIncomeByDateRange($pdo, $from, $to);

$title = 'Reportes';
require __DIR__ . '/../views/layout/header.php';
require __DIR__ . '/../views/reports/index.php';
require __DIR__ . '/../views/layout/footer.php';
