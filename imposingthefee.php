<?php
require "dbconn.php"; // Your DB connection

// Get POST values
$feename    = $_POST['feename'];
$feeamt     = $_POST['feeamt'];
$duedate    = $_POST['duedate'];
$studentids = json_decode($_POST['studentids']); // Expecting JSON array

if (empty($feename) || empty($feeamt) || empty($duedate) || empty($studentids)) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields"
    ]);
    exit;
}

// Prepare statement for inserting payment
$sql = "INSERT INTO payment (studentid, username, name, feename, feeamt, feetype, duedate, status, schawarded) 
        VALUES (?, ?, ?, ?, ?, 'Full', ?, 'due', 'no')";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Query preparation failed: " . $conn->error
    ]);
    exit;
}

// Loop through each studentid
foreach ($studentids as $sid) {
    // Fetch student details first
    $q = $conn->prepare("SELECT username, name FROM studentprofile WHERE studentid = ?");
    $q->bind_param("i", $sid);
    $q->execute();
    $res = $q->get_result();

    if ($res->num_rows > 0) {
        $student = $res->fetch_assoc();
        $username = $student['username'];
        $name     = $student['name'];

        // Insert payment with student details
        $stmt->bind_param("isssis", $sid, $username, $name, $feename, $feeamt, $duedate);
        $stmt->execute();
    }
    $q->close();
}

echo json_encode([
    "status" => "success",
    "message" => "Fee Imposed successfully"
]);



include "accounts.php";

 
$stmt->close();
$conn->close();
?>
