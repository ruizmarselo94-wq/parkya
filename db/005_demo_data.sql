-- ============================================================
-- 005 - Datos de demostración
-- Ejecutado en Neon. Deja el sistema con movimiento real para
-- la presentación: dos estacionamientos, sesiones cerradas con
-- sus cobros del día y sesiones activas en curso.
--
-- Los tiempos son relativos a NOW(), así las sesiones activas
-- muestran una duración creíble y el cobro se calcula en vivo.
-- Los importes respetan la regla del sistema: hora empezada se
-- cobra completa (ceil), mínimo 1 hora, por la tarifa del lugar.
-- ============================================================

BEGIN;

-- ------------------------------------------------------------
-- 1. Segundo estacionamiento (el selector de tarifas y el
--    listado dejan de tener una sola opción)
-- ------------------------------------------------------------
INSERT INTO parking_lots (name, address)
VALUES ('Villa Morra', 'Av. Mcal. López esq. San Martín');

-- ------------------------------------------------------------
-- 2. Más lugares
-- ------------------------------------------------------------
INSERT INTO spaces (lot_id, code, type) VALUES
    ((SELECT id FROM parking_lots WHERE name = 'Microcentro'), 'A-04', 'standard'),
    ((SELECT id FROM parking_lots WHERE name = 'Microcentro'), 'A-05', 'standard'),
    ((SELECT id FROM parking_lots WHERE name = 'Microcentro'), 'A-06', 'compact'),
    ((SELECT id FROM parking_lots WHERE name = 'Villa Morra'), 'B-01', 'standard'),
    ((SELECT id FROM parking_lots WHERE name = 'Villa Morra'), 'B-02', 'standard'),
    ((SELECT id FROM parking_lots WHERE name = 'Villa Morra'), 'B-03', 'handicap'),
    ((SELECT id FROM parking_lots WHERE name = 'Villa Morra'), 'B-04', 'compact');

-- ------------------------------------------------------------
-- 3. Tarifas de Villa Morra (distintas a Microcentro, para que
--    se vea que la tarifa es por estacionamiento)
-- ------------------------------------------------------------
INSERT INTO rates (lot_id, type, amount) VALUES
    ((SELECT id FROM parking_lots WHERE name = 'Villa Morra'), 'hourly', 7000),
    ((SELECT id FROM parking_lots WHERE name = 'Villa Morra'), 'daily', 40000);

-- ------------------------------------------------------------
-- 4. Clientes y vehículos
-- ------------------------------------------------------------
INSERT INTO customers (full_name, phone, email, document) VALUES
    ('María González',  '0981555201', 'maria.gonzalez@example.com', '3456789'),
    ('Carlos Benítez',  '0982117340', NULL,                          '2874511'),
    ('Lucía Ramírez',   '0971400882', 'lucia.ramirez@example.com',  '4120336'),
    ('Roberto Ayala',   '0985220417', NULL,                          '1998204'),
    ('Patricia Duarte', '0976311925', NULL,                          '5033178');

INSERT INTO vehicles (customer_id, plate, brand, model, color, type) VALUES
    ((SELECT id FROM customers WHERE full_name = 'María González'),  'AABB 456', 'Fiat',       'Cronos', 'Gris',  'car'),
    ((SELECT id FROM customers WHERE full_name = 'Carlos Benítez'),  'CDE 789',  'Chevrolet',  'Onix',   'Rojo',  'car'),
    ((SELECT id FROM customers WHERE full_name = 'Lucía Ramírez'),   'AAFG 112', 'Toyota',     'Etios',  'Azul',  'car'),
    ((SELECT id FROM customers WHERE full_name = 'Roberto Ayala'),   'HIJ 334',  'Volkswagen', 'Gol',    'Negro', 'van'),
    ((SELECT id FROM customers WHERE full_name = 'Patricia Duarte'), 'AAKL 556', 'Honda',      'CG 150', 'Negro', 'motorcycle');

-- ------------------------------------------------------------
-- 5. Sesiones cerradas de hoy, con su cobro
-- ------------------------------------------------------------
INSERT INTO parking_sessions (space_id, vehicle_id, operator_id, entry_time, exit_time, total_cost, status) VALUES
    -- 4 h exactas x 5.000 = 20.000
    ((SELECT s.id FROM spaces s JOIN parking_lots l ON l.id = s.lot_id WHERE l.name = 'Microcentro' AND s.code = 'A-02'),
     (SELECT id FROM vehicles WHERE plate = 'ABC 123'),
     (SELECT id FROM users WHERE username = 'admin'),
     NOW() - INTERVAL '7 hours', NOW() - INTERVAL '3 hours', 20000, 'closed'),
    -- 1 h 30 -> 2 h x 5.000 = 10.000
    ((SELECT s.id FROM spaces s JOIN parking_lots l ON l.id = s.lot_id WHERE l.name = 'Microcentro' AND s.code = 'A-03'),
     (SELECT id FROM vehicles WHERE plate = 'AAFG 112'),
     (SELECT id FROM users WHERE username = 'admin'),
     NOW() - INTERVAL '6 hours', NOW() - INTERVAL '4 hours 30 minutes', 10000, 'closed'),
    -- 4 h 25 -> 5 h x 7.000 = 35.000
    ((SELECT s.id FROM spaces s JOIN parking_lots l ON l.id = s.lot_id WHERE l.name = 'Villa Morra' AND s.code = 'B-02'),
     (SELECT id FROM vehicles WHERE plate = 'HIJ 334'),
     (SELECT id FROM users WHERE username = 'admin'),
     NOW() - INTERVAL '8 hours', NOW() - INTERVAL '3 hours 35 minutes', 35000, 'closed'),
    -- 1 h 10 -> 2 h x 5.000 = 10.000
    ((SELECT s.id FROM spaces s JOIN parking_lots l ON l.id = s.lot_id WHERE l.name = 'Microcentro' AND s.code = 'A-04'),
     (SELECT id FROM vehicles WHERE plate = 'AAKL 556'),
     (SELECT id FROM users WHERE username = 'admin'),
     NOW() - INTERVAL '2 hours 10 minutes', NOW() - INTERVAL '1 hour', 10000, 'closed');

INSERT INTO payments (session_id, amount, method, reference, paid_at, operator_id)
SELECT ps.id,
       ps.total_cost,
       m.method,
       m.reference,
       ps.exit_time,
       (SELECT id FROM users WHERE username = 'admin')
FROM parking_sessions ps
JOIN vehicles v ON v.id = ps.vehicle_id
JOIN (VALUES
        ('ABC 123',  'cash',     NULL),
        ('AAFG 112', 'card',     '4512'),
        ('HIJ 334',  'pos',      '008734'),
        ('AAKL 556', 'transfer', 'TRF-90211')
     ) AS m(plate, method, reference) ON m.plate = v.plate
WHERE ps.status = 'closed';

-- ------------------------------------------------------------
-- 6. Sesiones activas (el dashboard muestra ocupación y la
--    pantalla de salida tiene vehículos para cobrar en vivo)
-- ------------------------------------------------------------
INSERT INTO parking_sessions (space_id, vehicle_id, operator_id, entry_time, status) VALUES
    ((SELECT s.id FROM spaces s JOIN parking_lots l ON l.id = s.lot_id WHERE l.name = 'Microcentro' AND s.code = 'A-01'),
     (SELECT id FROM vehicles WHERE plate = 'AABB 456'),
     (SELECT id FROM users WHERE username = 'admin'),
     NOW() - INTERVAL '2 hours', 'active'),
    ((SELECT s.id FROM spaces s JOIN parking_lots l ON l.id = s.lot_id WHERE l.name = 'Villa Morra' AND s.code = 'B-01'),
     (SELECT id FROM vehicles WHERE plate = 'CDE 789'),
     (SELECT id FROM users WHERE username = 'admin'),
     NOW() - INTERVAL '45 minutes', 'active');

COMMIT;
