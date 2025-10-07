<?php
require "dbconn.php"; // DB connection

// If you want to filter by studentid/feename like before, add POST vars
// Otherwise, this will fetch ALL rows from your table
// Example: $studentid = $_POST['studentid'];

$sql = "SELECT routename, type, via, amount,km,seats FROM bus_routes"; 
// Change `routes` to your actual table name

$stmt = $conn->prepare($sql);
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
        "message" => "No records found"
    ]);
}

$stmt->close();
$conn->close();
?>
