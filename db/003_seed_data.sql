-- Datos de ejemplo
INSERT INTO users (username, password, full_name, email, role)
VALUES ('admin', '$2b$12$hash', 'Admin ParkYa', 'admin@parkya.com', 'admin');

INSERT INTO parking_lots (name, address, total_spaces)
VALUES ('Microcentro', 'Chile esq. Paraguarí', 50);

INSERT INTO spaces (lot_id, code, type)
VALUES (1, 'A-01', 'standard'), (1, 'A-02', 'standard'), (1, 'A-03', 'handicap');

INSERT INTO customers (full_name, phone, document)
VALUES ('Juan Pérez', '0981234567', '1234567');

INSERT INTO vehicles (customer_id, plate, brand, model, color, type)
VALUES (1, 'ABC 123', 'Toyota', 'Corolla', 'Blanco', 'car');

INSERT INTO rates (lot_id, type, amount)
VALUES (1, 'hourly', 5000), (1, 'daily', 30000);   