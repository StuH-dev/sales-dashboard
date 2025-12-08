<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/controllers/DashboardController.php';

// Simple router via query string
$page = $_GET['page'] ?? 'dashboard';

switch ($page) {
    case 'dashboard':
    default:
        (new DashboardController())->index();
        break;
}
