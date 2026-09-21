<?php

function findActiveHourlyRate(PDO $pdo, int $lotId): ?array {
    $sql = "SELECT * FROM rates
            WHERE lot_id = ? AND type = 'hourly'
              AND valid_from <= CURRENT_DATE
              AND (valid_to IS NULL OR valid_to >= CURRENT_DATE)
            ORDER BY valid_from DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lotId]);
    return $stmt->fetch() ?: null;
}

function listRatesByLot(PDO $pdo, int $lotId): array {
    $stmt = $pdo->prepare('SELECT * FROM rates WHERE lot_id = ? ORDER BY valid_from DESC');
    $stmt->execute([$lotId]);
    return $stmt->fetchAll();
}

function createRate(PDO $pdo, array $data): void {
    $stmt = $pdo->prepare(
        'INSERT INTO rates (lot_id, type, amount, valid_from, valid_to) VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $data['lot_id'],
        $data['type'],
        $data['amount'],
        $data['valid_from'],
        $data['valid_to'] ?: null,
    ]);
}

function findRate(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare('SELECT * FROM rates WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function updateRate(PDO $pdo, int $id, array $data): void {
    $stmt = $pdo->prepare(
        'UPDATE rates SET type = ?, amount = ?, valid_from = ?, valid_to = ? WHERE id = ?'
    );
    $stmt->execute([
        $data['type'],
        $data['amount'],
        $data['valid_from'],
        $data['valid_to'] ?: null,
        $id,
    ]);
}

function deleteRate(PDO $pdo, int $id): void {
    $stmt = $pdo->prepare('DELETE FROM rates WHERE id = ?');
    $stmt->execute([$id]);
}
