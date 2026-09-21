# Changelog - ParkYa DB

Historial de consultas ejecutadas directamente en Neon (SQL Editor).
Los archivos `.sql` de esta carpeta son respaldo, en orden de ejecución.

## v1.2 - 21/09/2026 — `005_demo_data.sql`

- Datos de demostración: segundo estacionamiento (Villa Morra) con sus
  tarifas propias, más lugares en ambos, clientes y vehículos
- Movimiento del día: 4 sesiones cerradas con su cobro (efectivo, tarjeta,
  POS, transferencia) y 2 sesiones activas en curso
- Tiempos relativos a `NOW()`: las sesiones activas muestran duración real
  y el cobro se calcula en vivo al registrar la salida

## v1.1 - 15/09/2026 — `004_constraints.sql`

- Renombrada `sessions` → `parking_sessions` (no confundir con sesiones de login PHP)
- Índices únicos parciales: un lugar y un vehículo con una sola sesión activa
- `payments.session_id`: `ON DELETE CASCADE` → `RESTRICT` (no se pierden cobros)
- `CHECK` en valores permitidos: `users.role`, `vehicles.type`, `spaces.type`,
  `spaces.status`, `parking_sessions.status`, `payments.method`, `rates.type`
- `CHECK` de coherencia: sesión cerrada con salida y costo, salida ≥ entrada,
  montos positivos, vigencia de tarifas, placa en mayúsculas
- `spaces.status` ya no usa `occupied`: se calcula desde `parking_sessions`
- Eliminado `parking_lots.total_spaces` (redundante) e `idx_vehicles_plate` (duplicado)
- Vistas nuevas: `space_occupancy`, `parking_lot_summary`
- Hash bcrypt válido para el usuario `admin` de ejemplo

## v1.0 - 14/09/2026 — `001` a `003`

- Base `parkya` creada desde la consola de Neon (`001` queda como referencia)
- Creación inicial: 8 tablas (users, customers, vehicles,
  parking_lots, spaces, sessions, payments, rates)
- Índices en vehicles.plate, sessions.entry_time, spaces.status
- Datos de ejemplo
