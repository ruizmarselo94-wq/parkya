<?php
# Funciones de uso general en vistas y controladores

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void {
    header("Location: $path");
    exit;
}

// Mensaje de una sola lectura entre requests (patrón flash de sesión)
function flash(string $key, ?string $message = null): ?string {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $value;
}

function money(float|string $amount): string {
    return number_format((float) $amount, 0, ',', '.');
}
