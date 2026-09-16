<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../models/space_model.php';
require_once __DIR__ . '/../models/vehicle_model.php';
require_once __DIR__ . '/../models/customer_model.php';
require_once __DIR__ . '/../models/session_model.php';

requireRole('admin', 'operator');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plate = strtoupper(trim($_POST['plate'] ?? ''));
    $spaceId = (int) ($_POST['space_id'] ?? 0);

    if ($plate === '' || $spaceId === 0) {
        flash('error', 'Completá la placa y elegí un lugar disponible.');
        redirect('checkin.php');
    }

    try {
        $vehicle = findVehicleByPlate($pdo, $plate);

        if ($vehicle === null) {
            $phone = trim($_POST['customer_phone'] ?? '');
            $customer = $phone !== '' ? findCustomerByPhone($pdo, $phone) : null;

            $customerId = $customer['id'] ?? createCustomer($pdo, [
                'full_name' => trim($_POST['customer_full_name'] ?? ''),
                'phone'     => $phone,
                'email'     => trim($_POST['customer_email'] ?? ''),
                'document'  => trim($_POST['customer_document'] ?? ''),
            ]);

            $vehicleId = createVehicle($pdo, [
                'customer_id' => $customerId,
                'plate'       => $plate,
                'brand'       => trim($_POST['brand'] ?? ''),
                'model'       => trim($_POST['model'] ?? ''),
                'color'       => trim($_POST['color'] ?? ''),
                'type'        => $_POST['type'] ?? 'car',
            ]);
        } else {
            $vehicleId = $vehicle['id'];
        }

        openSession($pdo, $spaceId, $vehicleId, currentUser()['id']);
        flash('success', "Entrada registrada para $plate.");
        redirect('dashboard.php');
    } catch (PDOException $e) {
        // 23505 = violación de índice único: el lugar o el vehículo ya tienen sesión activa
        flash('error', $e->getCode() === '23505'
            ? 'Ese lugar o ese vehículo ya tienen una entrada activa.'
            : 'No se pudo registrar la entrada.');
        redirect('checkin.php');
    }
}

$spaces = listAvailableSpaces($pdo);

$title = 'Registrar entrada';
require __DIR__ . '/../views/layout/header.php';
require __DIR__ . '/../views/sessions/checkin.php';
require __DIR__ . '/../views/layout/footer.php';
