<?php
require "dbconn.php"; // DB connection

$studentid = $_POST['studentid'];
$feename = $_POST['feename'];

$sql = "SELECT name, feename, feeamt , duedate
        FROM payment 
        WHERE studentid = ? AND feename = ? AND status = 'due'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $studentid, $feename);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); 
    echo json_encode([
        "status" => "success",
        "data" => $row
    ]);
} else {
    echo json_encode([
        "status" => "empty",
        "message" => "No due fee found for the given student and feename"
    ]);
}

$stmt->close();
$conn->close();
?>
