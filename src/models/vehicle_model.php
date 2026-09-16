<?php

function findVehicleByPlate(PDO $pdo, string $plate): ?array {
    $stmt = $pdo->prepare('SELECT * FROM vehicles WHERE plate = ?');
    $stmt->execute([strtoupper(trim($plate))]);
    return $stmt->fetch() ?: null;
}

function createVehicle(PDO $pdo, array $data): int {
    $stmt = $pdo->prepare(
        'INSERT INTO vehicles (customer_id, plate, brand, model, color, type)
         VALUES (?, ?, ?, ?, ?, ?) RETURNING id'
    );
    $stmt->execute([
        $data['customer_id'] ?: null,
        strtoupper(trim($data['plate'])),
        $data['brand'] ?: null,
        $data['model'] ?: null,
        $data['color'] ?: null,
        $data['type'],
    ]);
    return (int) $stmt->fetchColumn();
}
