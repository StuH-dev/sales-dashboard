<?php

require_once __DIR__ . '/../repositories/SalesRepository.php';

class DashboardController
{
    public function index(): void
    {
        $repo      = new SalesRepository();
        $summary   = $repo->getSummary();
        $rolling12 = $repo->getRolling12Months();

        $pageTitle  = 'Sales Dashboard';
        $activeMenu = 'dashboard';

        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/layout/sidebar.php';
        require __DIR__ . '/../views/dashboard.php';
        require __DIR__ . '/../views/layout/footer.php';
    }
}
