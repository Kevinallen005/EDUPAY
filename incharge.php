<?php
require "dbconn.php"; // Your DB connection

$sql = "SELECT incharge FROM incharge"; // change column name if different
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $incharges = [];
    while ($row = $result->fetch_assoc()) {
        $incharges[] = $row;
    }
    echo json_encode([
        "status" => "success",
        "data" => $incharges
    ]);
} else {
    echo json_encode([
        "status" => "empty",
        "message" => "No incharge data found"
    ]);
}

$conn->close();
?>
