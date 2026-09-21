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

// Traduce los valores de enums de la BD (guardados en inglés) para mostrarlos en español
function label(?string $key): string {
    static $labels = [
        // rates.type
        'hourly' => 'Por hora',
        'daily' => 'Por día',
        'monthly' => 'Mensual',
        // spaces.type
        'standard' => 'Estándar',
        'handicap' => 'Discapacidad',
        'compact' => 'Compacto',
        // spaces.status
        'available' => 'Disponible',
        'reserved' => 'Reservado',
        'out_of_service' => 'Fuera de servicio',
        // vehicles.type
        'car' => 'Auto',
        'motorcycle' => 'Moto',
        'van' => 'Camioneta',
        // users.role
        'admin' => 'Administrador',
        'operator' => 'Operador',
        'cashier' => 'Cajero',
        // payments.method
        'cash' => 'Efectivo',
        'card' => 'Tarjeta',
        'pos' => 'POS',
        'transfer' => 'Transferencia',
    ];
    return $labels[$key ?? ''] ?? (string) $key;
}
