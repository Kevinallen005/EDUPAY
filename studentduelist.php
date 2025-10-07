<?php
require "dbconn.php"; // Include your DB connection file

$studentid = $_POST['studentid'];

// Query to get due payments for the given student ID
$sql = "SELECT feename, feeamt, duedate FROM payment WHERE studentid = ? AND status = 'due'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $duelist = [];
    while ($row = $result->fetch_assoc()) {
        $duelist[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $duelist]);
} else {
    echo json_encode(["status" => "empty", "message" => "No DUE found"]);
}

$stmt->close();
$conn->close();
?>
