<?php

function findUserByUsername(PDO $pdo, string $username): ?array {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    return $stmt->fetch() ?: null;
}

function listUsers(PDO $pdo): array {
    return $pdo->query('SELECT id, username, full_name, email, role, phone, is_active FROM users ORDER BY full_name')->fetchAll();
}

function findUser(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function createUser(PDO $pdo, array $data): void {
    $stmt = $pdo->prepare(
        'INSERT INTO users (username, password, full_name, email, role, phone)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $data['username'],
        password_hash($data['password'], PASSWORD_DEFAULT),
        $data['full_name'],
        $data['email'],
        $data['role'],
        $data['phone'] ?: null,
    ]);
}

function updateUser(PDO $pdo, int $id, array $data): void {
    if (!empty($data['password'])) {
        $stmt = $pdo->prepare(
            'UPDATE users SET full_name = ?, email = ?, role = ?, phone = ?, password = ? WHERE id = ?'
        );
        $stmt->execute([
            $data['full_name'],
            $data['email'],
            $data['role'],
            $data['phone'] ?: null,
            password_hash($data['password'], PASSWORD_DEFAULT),
            $id,
        ]);
        return;
    }

    $stmt = $pdo->prepare(
        'UPDATE users SET full_name = ?, email = ?, role = ?, phone = ? WHERE id = ?'
    );
    $stmt->execute([
        $data['full_name'],
        $data['email'],
        $data['role'],
        $data['phone'] ?: null,
        $id,
    ]);
}

function deleteUser(PDO $pdo, int $id): void {
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$id]);
}
