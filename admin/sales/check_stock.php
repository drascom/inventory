<?php
require_once('../../config.php');

$item_id = $_POST['item_id'];
$response = array('available' => 0);

if(isset($item_id)) {
    // Calculate available stock
    $query = "SELECT 
                COALESCE(SUM(CASE WHEN type = 1 THEN quantity ELSE 0 END), 0) as stock_in,
                COALESCE(SUM(CASE WHEN type = 2 THEN quantity ELSE 0 END), 0) as stock_out
              FROM stock_list 
              WHERE item_id = '$item_id'";
    
    $result = $conn->query($query);
    if($result) {
        $row = $result->fetch_assoc();
        $available = $row['stock_in'] - $row['stock_out'];
        $response['available'] = $available;
    }
}

echo json_encode($response);
?>