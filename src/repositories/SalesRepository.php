<?php

require_once __DIR__ . '/../../config/database.php';

class SalesRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function getSummary(): array
    {
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
        $sql = "
            SELECT
                FORMAT(month_end, 'MMM yyyy') AS month_label,
                SUM(invoiced) AS invoiced,
                SUM(budget)   AS budget,
                SUM(target)   AS target
            FROM monthly_sales
            WHERE month_end >= DATEADD(MONTH, -11, EOMONTH(GETDATE()))
            GROUP BY FORMAT(month_end, 'MMM yyyy')
            ORDER BY MIN(month_end)
        ";

        return $this->db->query($sql)->fetchAll();
    }
}
