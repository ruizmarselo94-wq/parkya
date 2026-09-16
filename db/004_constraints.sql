-- ============================================================
-- 004 - Integridad de datos (v1.1)
-- Ejecutado en Neon (SQL Editor). Todo en una transacción:
-- si algo falla, no se aplica nada.
-- ============================================================

BEGIN;

-- ------------------------------------------------------------
-- 1. Renombrar sessions -> parking_sessions
--    (evita confusión con las sesiones de login de PHP)
-- ------------------------------------------------------------
ALTER TABLE sessions RENAME TO parking_sessions;
ALTER SEQUENCE sessions_id_seq RENAME TO parking_sessions_id_seq;
ALTER INDEX sessions_pkey        RENAME TO parking_sessions_pkey;
ALTER INDEX idx_sessions_space   RENAME TO idx_parking_sessions_space;
ALTER INDEX idx_sessions_vehicle RENAME TO idx_parking_sessions_vehicle;
ALTER INDEX idx_sessions_status  RENAME TO idx_parking_sessions_status;
ALTER INDEX idx_sessions_entry   RENAME TO idx_parking_sessions_entry;
ALTER TABLE parking_sessions RENAME CONSTRAINT sessions_space_id_fkey    TO parking_sessions_space_id_fkey;
ALTER TABLE parking_sessions RENAME CONSTRAINT sessions_vehicle_id_fkey  TO parking_sessions_vehicle_id_fkey;
ALTER TABLE parking_sessions RENAME CONSTRAINT sessions_operator_id_fkey TO parking_sessions_operator_id_fkey;

-- ------------------------------------------------------------
-- 2. Regla principal: un lugar y un vehículo solo pueden tener
--    UNA sesión activa a la vez
-- ------------------------------------------------------------
CREATE UNIQUE INDEX uq_parking_sessions_active_space
    ON parking_sessions (space_id) WHERE status = 'active';
CREATE UNIQUE INDEX uq_parking_sessions_active_vehicle
    ON parking_sessions (vehicle_id) WHERE status = 'active';

-- ------------------------------------------------------------
-- 3. Los pagos no se borran en cascada (registro contable)
-- ------------------------------------------------------------
ALTER TABLE payments DROP CONSTRAINT payments_session_id_fkey;
ALTER TABLE payments ADD CONSTRAINT payments_session_id_fkey
    FOREIGN KEY (session_id) REFERENCES parking_sessions(id) ON DELETE RESTRICT;

-- ------------------------------------------------------------
-- 4. Valores permitidos (antes solo estaban en comentarios)
-- ------------------------------------------------------------
ALTER TABLE users ADD CONSTRAINT chk_users_role
    CHECK (role IN ('admin', 'operator', 'cashier'));

ALTER TABLE vehicles ADD CONSTRAINT chk_vehicles_type
    CHECK (type IN ('car', 'motorcycle', 'van'));
-- Placa siempre en mayúsculas y sin espacios en los extremos,
-- para que "abc 123" y "ABC 123" no se registren como distintas
ALTER TABLE vehicles ADD CONSTRAINT chk_vehicles_plate_format
    CHECK (plate = UPPER(BTRIM(plate)));

ALTER TABLE spaces ADD CONSTRAINT chk_spaces_type
    CHECK (type IN ('standard', 'handicap', 'compact'));
-- "occupied" ya no se guarda: se calcula desde parking_sessions
-- (ver vista space_occupancy). status es solo el estado administrativo.
ALTER TABLE spaces ADD CONSTRAINT chk_spaces_status
    CHECK (status IN ('available', 'reserved', 'out_of_service'));

ALTER TABLE parking_sessions ADD CONSTRAINT chk_parking_sessions_status
    CHECK (status IN ('active', 'closed'));
-- Activa = sin salida; cerrada = con salida y costo
ALTER TABLE parking_sessions ADD CONSTRAINT chk_parking_sessions_closed
    CHECK (
        (status = 'active' AND exit_time IS NULL)
        OR (status = 'closed' AND exit_time IS NOT NULL AND total_cost IS NOT NULL)
    );
ALTER TABLE parking_sessions ADD CONSTRAINT chk_parking_sessions_times
    CHECK (exit_time IS NULL OR exit_time >= entry_time);
ALTER TABLE parking_sessions ADD CONSTRAINT chk_parking_sessions_cost
    CHECK (total_cost IS NULL OR total_cost >= 0);

ALTER TABLE payments ADD CONSTRAINT chk_payments_method
    CHECK (method IN ('cash', 'card', 'pos', 'transfer'));
ALTER TABLE payments ADD CONSTRAINT chk_payments_amount
    CHECK (amount > 0);

ALTER TABLE rates ADD CONSTRAINT chk_rates_type
    CHECK (type IN ('hourly', 'daily', 'monthly'));
ALTER TABLE rates ADD CONSTRAINT chk_rates_amount
    CHECK (amount > 0);
ALTER TABLE rates ADD CONSTRAINT chk_rates_validity
    CHECK (valid_to IS NULL OR valid_to >= valid_from);

-- ------------------------------------------------------------
-- 5. Quitar datos redundantes
-- ------------------------------------------------------------
-- UNIQUE (plate) ya crea su propio índice
DROP INDEX idx_vehicles_plate;

-- total_spaces se desincroniza de la tabla spaces (había 50 vs 3 reales)
ALTER TABLE parking_lots DROP COLUMN total_spaces;

-- ------------------------------------------------------------
-- 6. Vistas para consultar ocupación sin duplicar datos
-- ------------------------------------------------------------
CREATE VIEW space_occupancy AS
SELECT
    s.id,
    s.lot_id,
    s.code,
    s.type,
    s.status,
    ps.id              AS active_session_id,
    (ps.id IS NOT NULL) AS is_occupied
FROM spaces s
LEFT JOIN parking_sessions ps
       ON ps.space_id = s.id AND ps.status = 'active';

CREATE VIEW parking_lot_summary AS
SELECT
    pl.id,
    pl.name,
    pl.address,
    pl.is_active,
    COUNT(s.id)                                                  AS total_spaces,
    COUNT(ps.id)                                                 AS occupied_spaces,
    COUNT(s.id) FILTER (WHERE s.status = 'available' AND ps.id IS NULL) AS free_spaces
FROM parking_lots pl
LEFT JOIN spaces s            ON s.lot_id = pl.id
LEFT JOIN parking_sessions ps ON ps.space_id = s.id AND ps.status = 'active'
GROUP BY pl.id;

-- ------------------------------------------------------------
-- 7. Hash válido para el admin de ejemplo
--    Contraseña de desarrollo: admin123 (cambiar antes de producción)
--    Solo reemplaza el hash roto de 003 (11 caracteres), no uno real.
-- ------------------------------------------------------------
UPDATE users
   SET password = '$2y$10$3ajM8N3V0hmJ7pz4iM.SAuCVSIcj98gbKqLOBWHKa2nLWTUgBdVZa'
 WHERE username = 'admin' AND LENGTH(password) < 60;

COMMIT;
