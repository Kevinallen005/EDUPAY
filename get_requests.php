<?php
require "dbconn.php";

$studentid = $_POST['studentid'];

$sql = "SELECT routename, amount, status, via, boarding_point
        FROM bus_requests 
        WHERE studentid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentid);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

if (!empty($rows)) {
    echo json_encode([
        "status" => "success",
        "data" => $rows
    ]);
} else {
    echo json_encode([
        "status" => "empty",
        "message" => "No requests found"
    ]);
}

$stmt->close();
$conn->close();
?>
