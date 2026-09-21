<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../models/parking_lot_model.php';
require_once __DIR__ . '/../models/space_model.php';

requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create_lot') {
        try {
            createLot($pdo, [
                'name'    => trim($_POST['name'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
            ]);
            flash('success', 'Estacionamiento creado.');
        } catch (PDOException $e) {
            flash('error', 'No se pudo crear el estacionamiento.');
        }
        redirect('lots.php');
    }

    if ($action === 'update_lot') {
        $id = (int) ($_POST['id'] ?? 0);
        try {
            updateLot($pdo, $id, [
                'name'    => trim($_POST['name'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
            ]);
            flash('success', 'Estacionamiento actualizado.');
        } catch (PDOException $e) {
            flash('error', 'No se pudo actualizar el estacionamiento.');
        }
        redirect('lots.php');
    }

    if ($action === 'delete_lot') {
        $id = (int) ($_POST['id'] ?? 0);
        try {
            deleteLot($pdo, $id);
            flash('success', 'Estacionamiento eliminado.');
        } catch (PDOException $e) {
            flash('error', $e->getCode() === '23503'
                ? 'No se puede eliminar: tiene lugares con historial de uso.'
                : 'No se pudo eliminar el estacionamiento.');
        }
        redirect('lots.php');
    }

    if ($action === 'create_space') {
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

    if ($action === 'update_space') {
        $lotId = (int) ($_POST['lot_id'] ?? 0);
        $id = (int) ($_POST['id'] ?? 0);
        try {
            updateSpace($pdo, $id, [
                'code'   => trim($_POST['code'] ?? ''),
                'type'   => $_POST['type'] ?? 'standard',
                'status' => $_POST['status'] ?? 'available',
            ]);
            flash('success', 'Lugar actualizado.');
        } catch (PDOException $e) {
            flash('error', $e->getCode() === '23505' ? 'Ese código de lugar ya existe en este estacionamiento.' : 'No se pudo actualizar el lugar.');
        }
        redirect("lots.php?lot_id=$lotId");
    }

    if ($action === 'delete_space') {
        $lotId = (int) ($_POST['lot_id'] ?? 0);
        $id = (int) ($_POST['id'] ?? 0);
        try {
            deleteSpace($pdo, $id);
            flash('success', 'Lugar eliminado.');
        } catch (PDOException $e) {
            flash('error', $e->getCode() === '23503'
                ? 'No se puede eliminar: este lugar tiene sesiones registradas.'
                : 'No se pudo eliminar el lugar.');
        }
        redirect("lots.php?lot_id=$lotId");
    }
}

$lots = listAllLots($pdo);
$selectedLotId = (int) ($_GET['lot_id'] ?? 0);
$spaces = $selectedLotId > 0 ? listSpacesByLot($pdo, $selectedLotId) : [];

$editLotId = (int) ($_GET['edit_lot'] ?? 0);
$editLot = $editLotId > 0 ? findLot($pdo, $editLotId) : null;

$editSpaceId = (int) ($_GET['edit_space'] ?? 0);
$editSpace = $editSpaceId > 0 ? findSpace($pdo, $editSpaceId) : null;

$title = 'Estacionamientos';
require __DIR__ . '/../views/layout/header.php';
require __DIR__ . '/../views/lots/index.php';
require __DIR__ . '/../views/layout/footer.php';
