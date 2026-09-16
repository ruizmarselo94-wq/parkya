<?php
# Incluido al principio de cada controlador

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';

$pdo = getDBConnection();
