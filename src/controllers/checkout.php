<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../models/session_model.php';
require_once __DIR__ . '/../models/rate_model.php';
require_once __DIR__ . '/../models/payment_model.php';

requireLogin();

// Se cobra por hora empezada: 61 minutos son 2 horas, no 1h y monedas.
function computeCost(PDO $pdo, array $session): float {
    $rate = findActiveHourlyRate($pdo, $session['lot_id']);
    if ($rate === null) {
        throw new RuntimeException("El estacionamiento \"{$session['lot_name']}\" no tiene una tarifa por hora vigente.");
    }

    $entry = new DateTime($session['entry_time']);
    $now = new DateTime();
    $seconds = max(0, $now->getTimestamp() - $entry->getTimestamp());
    $hours = max(1, (int) ceil($seconds / 3600));

    return $hours * (float) $rate['amount'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sessionId = (int) ($_POST['session_id'] ?? 0);
    $method = $_POST['method'] ?? '';
    $reference = trim($_POST['reference'] ?? '');

    $session = findActiveSessionDetail($pdo, $sessionId);
    if ($session === null) {
        flash('error', 'Esa sesión ya no está activa.');
        redirect('checkout.php');
    }

    try {
        $cost = computeCost($pdo, $session);

        $pdo->beginTransaction();
        closeSession($pdo, $sessionId, $cost);
        createPayment($pdo, [
            'session_id'  => $sessionId,
            'amount'      => $cost,
            'method'      => $method,
            'reference'   => $reference,
            'operator_id' => currentUser()['id'],
        ]);
        $pdo->commit();

        flash('success', "Salida registrada: {$session['plate']} — " . money($cost) . ' Gs.');
        redirect('dashboard.php');
    } catch (RuntimeException $e) {
        flash('error', $e->getMessage());
        redirect('checkout.php');
    } catch (PDOException $e) {
        $pdo->rollBack();
        flash('error', 'No se pudo registrar la salida.');
        redirect('checkout.php');
    }
}

// GET con session_id: muestra el detalle antes de confirmar el cobro
$selected = null;
$estimatedCost = null;
$costError = null;
$sessionIdParam = (int) ($_GET['session_id'] ?? 0);

if ($sessionIdParam > 0) {
    $selected = findActiveSessionDetail($pdo, $sessionIdParam);
    if ($selected !== null) {
        try {
            $estimatedCost = computeCost($pdo, $selected);
        } catch (RuntimeException $e) {
            $costError = $e->getMessage();
        }
    }
}

$activeSessions = listActiveSessions($pdo);

$title = 'Registrar salida';
require __DIR__ . '/../views/layout/header.php';
require __DIR__ . '/../views/sessions/checkout.php';
require __DIR__ . '/../views/layout/footer.php';
