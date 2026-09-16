-- ============================================================
-- PARKYA - Sistema de Estacionamiento
-- PostgreSQL 14+
-- ============================================================
-- REFERENCIA: en Neon la base "parkya" se creó desde la consola
-- web (owner parkya_owner), no con este script.

CREATE DATABASE parkya
    WITH OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'en_US.UTF-8'
    LC_CTYPE = 'en_US.UTF-8'
    TEMPLATE = template0
    CONNECTION LIMIT = -1;