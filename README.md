# ParkYa

Sistema de gestión de estacionamientos desarrollado en PHP (sin frameworks) como parte del curso **Programación Web Backend con PHP**.

## Descripción

ParkYa permite gestionar la entrada y salida de vehículos en estacionamientos, controlando espacios, tarifas, sesiones y pagos.

## Características

- Registro de usuarios (admin, operador, cajero)
- Gestión de estacionamientos y espacios
- Control de entrada/salida de vehículos
- Cálculo automático de tarifas por tiempo
- Registro de pagos
- Reportes de ocupación e ingresos

## Tecnologías

| Capa          | Tecnología                    |
| ------------- | ----------------------------- |
| Backend       | PHP 8.x (sin frameworks)      |
| Base de datos | PostgreSQL 17 en Neon (nube)  |
| Frontend      | HTML, CSS, JavaScript         |
| Cliente BD    | Neon SQL Editor / ext. VS Code |

## Estructura del proyecto

```
parkya/
├── db/                        # Respaldo de consultas ejecutadas en Neon
│   ├── 001_create_db.sql      # referencia (la base se creó desde Neon)
│   ├── 002_create_tables.sql
│   ├── 003_seed_data.sql
│   ├── 004_constraints.sql
│   └── CHANGELOG.md
├── src/                       # Raíz pública del servidor web
│   ├── config/
│   │   ├── env.php            # carga del .env
│   │   └── database.php       # conexión PDO
│   ├── includes/              # bootstrap.php, auth.php, helpers.php
│   ├── controllers/           # una página por archivo (login, dashboard, checkin, checkout, lots, rates, users, reports)
│   ├── models/                # funciones de acceso a datos, una por tabla
│   ├── views/                 # plantillas incluidas por los controladores
│   ├── assets/css/            # estilos
│   └── index.php              # redirige a /controllers/dashboard.php o /controllers/login.php
├── Dockerfile                 # imagen para desplegar en Render (PHP 8.3 + pdo_pgsql)
├── .env                       # credenciales (no se sube)
├── .env.example
└── README.md
```

## Requisitos

- PHP 8.0+ con la extensión `pdo_pgsql`
- Una base PostgreSQL en [Neon](https://neon.tech)

## Instalación

1. Clonar el proyecto.

2. Base de datos: en el SQL Editor de Neon, ejecutar en orden los scripts de `db/`
   desde `002` en adelante (la base `parkya` se crea desde la consola de Neon).

3. Configurar credenciales:

   ```bash
   cp .env.example .env
   ```

   Completar `.env` con los datos de conexión de Neon. Usar el endpoint **directo**
   (sin `-pooler`): a través del pooler, las transacciones fallan con los
   prepared statements nativos de PDO (`SQLSTATE[25P02]`).

4. Ejecutar:

   ```bash
   php -S localhost:8000 -t src
   ```

   Abrir http://localhost:8000.

Usuario de desarrollo: `admin` / `admin123` — cambiar antes de producción.

## Despliegue

Arquitectura: **Render** corre la aplicación PHP (vía Docker), **Neon** sigue siendo la
base de datos (gratis, sin fecha de expiración — a diferencia de la Postgres gratis
de Render, que se borra a los 30 días).

1. Repo conectado en Render como **Web Service**, build con el `Dockerfile` de la raíz.
2. Variables de entorno del `.env.example` cargadas como *Environment Variables* del
   servicio en Render (no como archivo: el `.env` nunca se sube al repo).
3. Cada push a la rama configurada dispara un build y deploy automático.

## Modelo de datos

| Tabla            | Descripción                                    |
| ---------------- | ---------------------------------------------- |
| users            | Personal del sistema (admin, operator, cashier) |
| customers        | Conductores                                    |
| vehicles         | Vehículos (placa única, en mayúsculas)         |
| parking_lots     | Estacionamientos físicos                       |
| spaces           | Lugares individuales                           |
| parking_sessions | Entradas/salidas (transacción principal)       |
| payments         | Cobros realizados                              |
| rates            | Tarifas por estacionamiento                    |

| Vista               | Descripción                                   |
| ------------------- | --------------------------------------------- |
| space_occupancy     | Cada lugar con su sesión activa (si tiene)    |
| parking_lot_summary | Total, ocupados y libres por estacionamiento  |

Historial de cambios del esquema: [db/CHANGELOG.md](db/CHANGELOG.md).

## Autor

Marcelo Ruiz Diaz — Curso: Programación Web Backend con PHP

Fecha: Septiembre 2026
