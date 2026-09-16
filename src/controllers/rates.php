<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../models/parking_lot_model.php';
require_once __DIR__ . '/../models/rate_model.php';

requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lotId = (int) ($_POST['lot_id'] ?? 0);
    try {
        createRate($pdo, [
            'lot_id'     => $lotId,
            'type'       => $_POST['type'] ?? 'hourly',
            'amount'     => $_POST['amount'] ?? 0,
            'valid_from' => $_POST['valid_from'] ?? date('Y-m-d'),
            'valid_to'   => $_POST['valid_to'] ?? '',
        ]);
        flash('success', 'Tarifa creada.');
    } catch (PDOException $e) {
        flash('error', 'No se pudo crear la tarifa. Revisá el monto y las fechas.');
    }
    redirect("rates.php?lot_id=$lotId");
}

$lots = listActiveLots($pdo);
$selectedLotId = (int) ($_GET['lot_id'] ?? ($lots[0]['id'] ?? 0));
$rates = $selectedLotId > 0 ? listRatesByLot($pdo, $selectedLotId) : [];

$title = 'Tarifas';
require __DIR__ . '/../views/layout/header.php';
require __DIR__ . '/../views/rates/index.php';
require __DIR__ . '/../views/layout/footer.php';
