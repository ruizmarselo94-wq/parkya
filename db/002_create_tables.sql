-- 1. USERS (admin, operador, cajero)
CREATE TABLE users (
    id            SERIAL PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password      VARCHAR(255) NOT NULL,          -- hashear (bcrypt)
    full_name     VARCHAR(100) NOT NULL,
    email         VARCHAR(100) NOT NULL UNIQUE,
    role          VARCHAR(20)  NOT NULL DEFAULT 'operator',  -- admin, operator, cashier
    phone         VARCHAR(20),
    is_active     BOOLEAN      NOT NULL DEFAULT TRUE,
    created_at    TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- 2. CUSTOMERS (conductores que estacionan)
CREATE TABLE customers (
    id            SERIAL PRIMARY KEY,
    full_name     VARCHAR(100) NOT NULL,
    email         VARCHAR(100),
    phone         VARCHAR(20)  NOT NULL,
    document      VARCHAR(20),                    -- CI en Paraguay
    created_at    TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- 3. VEHICLES
CREATE TABLE vehicles (
    id            SERIAL PRIMARY KEY,
    customer_id   INT          REFERENCES customers(id) ON DELETE SET NULL,
    plate         VARCHAR(10)  NOT NULL UNIQUE,   -- ej: "ABC 123"
    brand         VARCHAR(50),                    -- Toyota, Chevrolet...
    model         VARCHAR(50),
    color         VARCHAR(30),
    type          VARCHAR(20)  NOT NULL DEFAULT 'car',  -- car, motorcycle, van
    created_at    TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_vehicles_plate ON vehicles (plate);

-- 4. PARKING_LOTS (estacionamientos físicos)
CREATE TABLE parking_lots (
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(100) NOT NULL,
    address       VARCHAR(255) NOT NULL,
    total_spaces  INT          NOT NULL,
    is_active     BOOLEAN      NOT NULL DEFAULT TRUE,
    created_at    TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- 5. SPACES (lugares individuales)
CREATE TABLE spaces (
    id            SERIAL PRIMARY KEY,
    lot_id        INT          NOT NULL REFERENCES parking_lots(id) ON DELETE CASCADE,
    code          VARCHAR(10)  NOT NULL,          -- "A-01", "B-15"
    type          VARCHAR(20)  NOT NULL DEFAULT 'standard',  -- standard, handicap, compact
    status        VARCHAR(20)  NOT NULL DEFAULT 'available', -- available, occupied, reserved
    UNIQUE (lot_id, code)
);

CREATE INDEX idx_spaces_lot ON spaces (lot_id);
CREATE INDEX idx_spaces_status ON spaces (status);

-- 6. SESSIONS (entrada/salida = transacción principal)
CREATE TABLE sessions (
    id            SERIAL PRIMARY KEY,
    space_id      INT          NOT NULL REFERENCES spaces(id),
    vehicle_id    INT          NOT NULL REFERENCES vehicles(id),
    operator_id   INT          NOT NULL REFERENCES users(id),
    entry_time    TIMESTAMPTZ  NOT NULL DEFAULT NOW(),
    exit_time     TIMESTAMPTZ,
    total_cost    NUMERIC(10,2),
    status        VARCHAR(20)  NOT NULL DEFAULT 'active',   -- active, closed
    notes         TEXT
);

CREATE INDEX idx_sessions_space ON sessions (space_id);
CREATE INDEX idx_sessions_vehicle ON sessions (vehicle_id);
CREATE INDEX idx_sessions_status ON sessions (status);
CREATE INDEX idx_sessions_entry ON sessions (entry_time);

-- 7. PAYMENTS
CREATE TABLE payments (
    id            SERIAL PRIMARY KEY,
    session_id    INT          NOT NULL REFERENCES sessions(id) ON DELETE CASCADE,
    amount        NUMERIC(10,2) NOT NULL,
    method        VARCHAR(30)  NOT NULL,          -- cash, card, pos, transfer
    reference     VARCHAR(100),                   -- nro. de operación
    paid_at       TIMESTAMPTZ  NOT NULL DEFAULT NOW(),
    operator_id   INT          NOT NULL REFERENCES users(id)
);

CREATE INDEX idx_payments_session ON payments (session_id);
CREATE INDEX idx_payments_date ON payments (paid_at);

-- 8. RATES (tarifas por estacionamiento)
CREATE TABLE rates (
    id            SERIAL PRIMARY KEY,
    lot_id        INT          NOT NULL REFERENCES parking_lots(id) ON DELETE CASCADE,
    type          VARCHAR(20)  NOT NULL,          -- hourly, daily, monthly
    amount        NUMERIC(10,2) NOT NULL,
    valid_from    DATE         NOT NULL DEFAULT CURRENT_DATE,
    valid_to      DATE
);   