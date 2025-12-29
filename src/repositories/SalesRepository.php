<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

class SalesRepository
{
    private ?PDO $db = null;

    public function __construct()
    {
        if (!USE_DUMMY_DATA) {
            $this->db = Database::connection();
        }
    }

    private function getDummySummary(): array
    {
        return [
            'open_orders'    => 1250000,
            'invoiced_today' => 45000,
            'month_to_date'  => 850000,
        ];
    }

    private function getDummyRolling12Months(): array
    {
        $months = [];
        $baseDate = new DateTime();
        $baseDate->modify('first day of this month');
        $baseDate->modify('-11 months');

        $baseInvoiced = 750000;
        $baseBudget = 900000;
        $baseTarget = 1000000;

        for ($i = 0; $i < 12; $i++) {
            $monthDate = clone $baseDate;
            $monthDate->modify("+$i months");

            $variance = ($i % 3 === 0) ? 1.15 : (($i % 3 === 1) ? 0.95 : 1.05);
            $trend = 1 + ($i * 0.02);

            $invoiced = (int)($baseInvoiced * $variance * $trend);
            $budget = (int)($baseBudget * $trend);
            $target = (int)($baseTarget * $trend);

            $months[] = [
                'month_label' => $monthDate->format('M Y'),
                'invoiced' => $invoiced,
                'budget' => $budget,
                'target' => $target,
            ];
        }

        return array_reverse($months);
    }

    public function getSummary(): array
    {
        if (USE_DUMMY_DATA) {
            return $this->getDummySummary();
        }

        $sql = "
            SELECT
                (SELECT SUM(amount) FROM orders WHERE status = 'OPEN') AS open_orders,
                (SELECT SUM(amount) FROM orders
                 WHERE CAST(order_date AS DATE) = CAST(GETDATE() AS DATE)) AS invoiced_today,
                (SELECT SUM(amount) FROM orders
                 WHERE MONTH(order_date) = MONTH(GETDATE())
                   AND YEAR(order_date) = YEAR(GETDATE())) AS month_to_date
        ";

        return $this->db->query($sql)->fetch() ?: [
            'open_orders'    => 0,
            'invoiced_today' => 0,
            'month_to_date'  => 0,
        ];
    }

    public function getRolling12Months(): array
    {
        if (USE_DUMMY_DATA) {
            return $this->getDummyRolling12Months();
        }

        $sql = "
            SELECT
                FORMAT(month_end, 'MMM yyyy') AS month_label,
                SUM(invoiced) AS invoiced,
                SUM(budget)   AS budget,
                SUM(target)   AS target
            FROM monthly_sales
            WHERE month_end >= DATEADD(MONTH, -11, EOMONTH(GETDATE()))
            GROUP BY FORMAT(month_end, 'MMM yyyy')
            ORDER BY MIN(month_end) DESC
        ";

        return $this->db->query($sql)->fetchAll();
    }
}
