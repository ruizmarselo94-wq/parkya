<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../models/parking_lot_model.php';
require_once __DIR__ . '/../models/space_model.php';

requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['action'] ?? '') === 'create_lot') {
        createLot($pdo, [
            'name'    => trim($_POST['name'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
        ]);
        flash('success', 'Estacionamiento creado.');
        redirect('lots.php');
    }

    if (($_POST['action'] ?? '') === 'create_space') {
        $lotId = (int) ($_POST['lot_id'] ?? 0);
        try {
            createSpace($pdo, [
                'lot_id' => $lotId,
                'code'   => trim($_POST['code'] ?? ''),
                'type'   => $_POST['type'] ?? 'standard',
            ]);
            flash('success', 'Lugar agregado.');
        } catch (PDOException $e) {
            flash('error', $e->getCode() === '23505' ? 'Ese código de lugar ya existe en este estacionamiento.' : 'No se pudo crear el lugar.');
        }
        redirect("lots.php?lot_id=$lotId");
    }
}

$lots = listAllLots($pdo);
$selectedLotId = (int) ($_GET['lot_id'] ?? 0);
$spaces = $selectedLotId > 0 ? listSpacesByLot($pdo, $selectedLotId) : [];

$title = 'Estacionamientos';
require __DIR__ . '/../views/layout/header.php';
require __DIR__ . '/../views/lots/index.php';
require __DIR__ . '/../views/layout/footer.php';
