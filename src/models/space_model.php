<?php

// Vista space_occupancy: cada lugar con su sesión activa (si tiene)
function listSpacesByLot(PDO $pdo, int $lotId): array {
    $stmt = $pdo->prepare('SELECT * FROM space_occupancy WHERE lot_id = ? ORDER BY code');
    $stmt->execute([$lotId]);
    return $stmt->fetchAll();
}

function listAvailableSpaces(PDO $pdo): array {
    $sql = "SELECT so.*, pl.name AS lot_name
            FROM space_occupancy so
            JOIN parking_lots pl ON pl.id = so.lot_id
            WHERE so.is_occupied = false AND so.status = 'available'
            ORDER BY pl.name, so.code";
    return $pdo->query($sql)->fetchAll();
}

function findSpace(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare('SELECT * FROM spaces WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function createSpace(PDO $pdo, array $data): void {
    $stmt = $pdo->prepare('INSERT INTO spaces (lot_id, code, type) VALUES (?, ?, ?)');
    $stmt->execute([$data['lot_id'], $data['code'], $data['type']]);
}
