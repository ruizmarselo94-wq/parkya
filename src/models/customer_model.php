<?php

function findCustomerByPhone(PDO $pdo, string $phone): ?array {
    $stmt = $pdo->prepare('SELECT * FROM customers WHERE phone = ?');
    $stmt->execute([$phone]);
    return $stmt->fetch() ?: null;
}

function createCustomer(PDO $pdo, array $data): int {
    $stmt = $pdo->prepare(
        'INSERT INTO customers (full_name, phone, email, document) VALUES (?, ?, ?, ?) RETURNING id'
    );
    $stmt->execute([
        $data['full_name'],
        $data['phone'],
        $data['email'] ?: null,
        $data['document'] ?: null,
    ]);
    return (int) $stmt->fetchColumn();
}
