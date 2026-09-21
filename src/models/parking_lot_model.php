<?php

function listActiveLots(PDO $pdo): array {
    return $pdo->query('SELECT * FROM parking_lots WHERE is_active = true ORDER BY name')->fetchAll();
}

function listAllLots(PDO $pdo): array {
    return $pdo->query('SELECT * FROM parking_lots ORDER BY name')->fetchAll();
}

function findLot(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare('SELECT * FROM parking_lots WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function createLot(PDO $pdo, array $data): int {
    $stmt = $pdo->prepare('INSERT INTO parking_lots (name, address) VALUES (?, ?) RETURNING id');
    $stmt->execute([$data['name'], $data['address']]);
    return (int) $stmt->fetchColumn();
}

function updateLot(PDO $pdo, int $id, array $data): void {
    $stmt = $pdo->prepare('UPDATE parking_lots SET name = ?, address = ? WHERE id = ?');
    $stmt->execute([$data['name'], $data['address'], $id]);
}

function deleteLot(PDO $pdo, int $id): void {
    $stmt = $pdo->prepare('DELETE FROM parking_lots WHERE id = ?');
    $stmt->execute([$id]);
}

// Vista parking_lot_summary: total, ocupados y libres por estacionamiento
function lotOccupancySummary(PDO $pdo): array {
    return $pdo->query('SELECT * FROM parking_lot_summary ORDER BY name')->fetchAll();
}
