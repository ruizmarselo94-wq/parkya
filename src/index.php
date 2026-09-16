<?php
require_once __DIR__ . '/includes/bootstrap.php';

redirect(currentUser() ? '/controllers/dashboard.php' : '/controllers/login.php');
