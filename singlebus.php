<?php
require "dbconn.php"; // DB connection

// Get POST value
$routename = $_POST['routename'] ?? $_GET['routename'] ?? null;


if (!$routename) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing routename"
    ]);
    exit();
}

// Prepare SQL
$sql = "SELECT routename, type, via, amount, km 
        FROM bus_routes 
        WHERE routename = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $routename);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode([
        "status" => "success",
        "data" => $rows
    ]);
} else {
    echo json_encode([
        "status" => "empty",
        "message" => "No records found for this routename"
    ]);
}

$stmt->close();
$conn->close();
?>
