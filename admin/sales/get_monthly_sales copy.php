<?php
require_once('../../config.php');

// Get current year
$year = date('Y');

// Initialize arrays for months
$months = [];
$sales = [];

// Get monthly sales data from sales_list table using the correct column 'amount'
$query = $conn->query("
    SELECT 
        MONTH(date_created) as month,
        SUM(amount) as total    /* Changed from total_amount to amount */
    FROM 
        sales_list 
    WHERE 
        YEAR(date_created) = '{$year}'
    GROUP BY 
        MONTH(date_created)
    ORDER BY 
        MONTH(date_created)
");

// Initialize all months with 0
for ($i = 1; $i <= 12; $i++) {
    $months[] = date('F', mktime(0, 0, 0, $i, 1)); // Full month name
    $sales[$i] = 0;
}

// Fill in actual sales data
while ($row = $query->fetch_assoc()) {
    $sales[$row['month']] = floatval($row['total']);
}

// Return data in format expected by uPlot
$response = [
    'labels' => $months,  // Array of month names ["January", "February", ...]
    'values' => array_values($sales)  // Array of sales values [1000, 1500, ...]
];

header('Content-Type: application/json');
echo json_encode($response);