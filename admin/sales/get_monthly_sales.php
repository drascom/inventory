<?php
require_once('../../config.php');

// Get current year
$year = date('Y');

// Initialize arrays
$months = [];
$sales = [];
$purchases = [];

// Initialize all months with 0
for ($i = 1; $i <= 12; $i++) {
    $months[] = date('F', mktime(0, 0, 0, $i, 1)); // Full month name
    $sales[$i] = 0;
    $purchases[$i] = 0;
}

// Get monthly sales data
$sales_query = $conn->query("
    SELECT 
        MONTH(date_created) as month,
        SUM(amount) as total
    FROM 
        sales_list 
    WHERE 
        YEAR(date_created) = '{$year}'
    GROUP BY 
        MONTH(date_created)
    ORDER BY 
        MONTH(date_created)
");

// Fill in actual sales data
while ($row = $sales_query->fetch_assoc()) {
    $sales[$row['month']] = floatval($row['total']);
}

// Get monthly purchase data
$purchase_query = $conn->query("
    SELECT 
        MONTH(date_created) as month,
        SUM(amount) as total
    FROM 
        purchase_order_list 
    WHERE 
        YEAR(date_created) = '{$year}'
        AND status = 2  -- Only count completed/received orders
    GROUP BY 
        MONTH(date_created)
    ORDER BY 
        MONTH(date_created)
");

// Fill in actual purchase data
while ($row = $purchase_query->fetch_assoc()) {
    $purchases[$row['month']] = floatval($row['total']);
}

// Return data in format expected by uPlot
$response = [
    'labels' => $months,
    'sales' => array_values($sales),
    'purchases' => array_values($purchases)
];

header('Content-Type: application/json');
echo json_encode($response);