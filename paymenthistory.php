<?php
require "dbconn.php";

// Get studentid from request (GET or POST)
$studentid = $_POST['studentid'] ?? $_GET['studentid'] ?? '';


if (empty($studentid)) {
    echo json_encode(["status" => "error", "message" => "Missing studentid"]);
    exit;
}

// Prepare and execute query
$sql = "SELECT feename, paydate, feeamt FROM history WHERE studentid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentid);
$stmt->execute();
$result = $stmt->get_result();

// Fetch data
if ($result->num_rows > 0) {
    $history = [];
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $history]);
} else {
    echo json_encode(["status" => "empty", "message" => "No payment history found"]);
}

$conn->close();
?>
