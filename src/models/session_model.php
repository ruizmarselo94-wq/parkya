<?php

// Puede lanzar PDOException (SQLSTATE 23505) si el lugar o el vehículo
// ya tienen una sesión activa: lo impiden los índices únicos parciales.
function openSession(PDO $pdo, int $spaceId, int $vehicleId, int $operatorId): int {
    $stmt = $pdo->prepare(
        'INSERT INTO parking_sessions (space_id, vehicle_id, operator_id) VALUES (?, ?, ?) RETURNING id'
    );
    $stmt->execute([$spaceId, $vehicleId, $operatorId]);
    return (int) $stmt->fetchColumn();
}

function listActiveSessions(PDO $pdo): array {
    $sql = "SELECT ps.id, ps.entry_time, v.plate, v.type AS vehicle_type,
                   s.code AS space_code, pl.id AS lot_id, pl.name AS lot_name
            FROM parking_sessions ps
            JOIN vehicles v ON v.id = ps.vehicle_id
            JOIN spaces s ON s.id = ps.space_id
            JOIN parking_lots pl ON pl.id = s.lot_id
            WHERE ps.status = 'active'
            ORDER BY ps.entry_time";
    return $pdo->query($sql)->fetchAll();
}

function findActiveSessionDetail(PDO $pdo, int $sessionId): ?array {
    $sql = "SELECT ps.id, ps.entry_time, ps.space_id, v.plate,
                   s.code AS space_code, pl.id AS lot_id, pl.name AS lot_name
            FROM parking_sessions ps
            JOIN vehicles v ON v.id = ps.vehicle_id
            JOIN spaces s ON s.id = ps.space_id
            JOIN parking_lots pl ON pl.id = s.lot_id
            WHERE ps.id = ? AND ps.status = 'active'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$sessionId]);
    return $stmt->fetch() ?: null;
}

function closeSession(PDO $pdo, int $sessionId, float $totalCost): void {
    $stmt = $pdo->prepare(
        "UPDATE parking_sessions SET status = 'closed', exit_time = now(), total_cost = ? WHERE id = ?"
    );
    $stmt->execute([$totalCost, $sessionId]);
}
