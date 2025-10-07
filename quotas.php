<?php
require "dbconn.php"; // Your DB connection

$sql = "SELECT quota, percentage FROM quotas"; // Change quota_table to your table name
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $quotas = [];
    while ($row = $result->fetch_assoc()) {
        $quotas[] = $row;
    }
    echo json_encode([
        "status" => "success",
        "data" => $quotas
    ]);
} else {
    echo json_encode([
        "status" => "empty",
        "message" => "No quota data found"
    ]);
}

$conn->close();
?>
