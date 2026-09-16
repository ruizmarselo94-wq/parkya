<?php

# Carga de variables desde el archivo .env (sin librerías externas)

function loadEnv(string $path): void {
    // En producción (Render, etc.) las variables ya vienen del entorno de la
    // plataforma y no hay .env físico — solo es obligatorio en local.
    if (!is_readable($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);

        // Ignorar comentarios y líneas sin "="
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $value = trim($value, "\"'");

        // Una variable ya definida en el sistema tiene prioridad sobre el .env
        if (getenv($key) === false) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

function env(string $key): string {
    $value = getenv($key);
    if ($value === false || $value === '') {
        throw new RuntimeException("Falta la variable de entorno: $key");
    }
    return $value;
}
