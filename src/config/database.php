<?php

# Lógica de conexión PDO

require_once __DIR__ . '/env.php';

// El .env vive en la raíz del proyecto, fuera de src/ (no accesible desde el navegador)
loadEnv(dirname(__DIR__, 2) . '/.env');

function getDBConnection(): PDO {
    // DSN específico para PostgreSQL en Neon con SSL obligatorio.
    $dsn = sprintf(
        'pgsql:host=%s;port=%s;dbname=%s;sslmode=%s',
        env('DB_HOST'),
        env('DB_PORT'),
        env('DB_NAME'),
        getenv('DB_SSLMODE') ?: 'require'
    );

    // EMULATE_PREPARES false exige conectar al endpoint directo de Neon:
    // con el pooler (-pooler) las transacciones quedan abortadas (25P02).
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, env('DB_USER'), env('DB_PASS'), $options);
    } catch (PDOException $e) {
        throw new RuntimeException('No se pudo conectar a la base de datos.', 0, $e);
    }
}
