<?php

function createPayment(PDO $pdo, array $data): void {
    $stmt = $pdo->prepare(
        'INSERT INTO payments (session_id, amount, method, reference, operator_id)
         VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $data['session_id'],
        $data['amount'],
        $data['method'],
        $data['reference'] ?: null,
        $data['operator_id'],
    ]);
}

function incomeByDateRange(PDO $pdo, string $from, string $to): array {
    $sql = "SELECT date(paid_at) AS day, method, SUM(amount) AS total
            FROM payments
            WHERE paid_at::date BETWEEN ? AND ?
            GROUP BY day, method
            ORDER BY day DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$from, $to]);
    return $stmt->fetchAll();
}

function totalIncomeByDateRange(PDO $pdo, string $from, string $to): float {
    $stmt = $pdo->prepare('SELECT COALESCE(SUM(amount), 0) FROM payments WHERE paid_at::date BETWEEN ? AND ?');
    $stmt->execute([$from, $to]);
    return (float) $stmt->fetchColumn();
}
