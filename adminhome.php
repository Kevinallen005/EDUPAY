<?php
header('Content-Type: application/json');
require "dbconn.php";

// Fixed admin studentid
$studentid = 100;

$response = [];

// Get school name from auth_user
$stmtSchool = $conn->prepare("SELECT name FROM auth_user WHERE studentid = ?");
$stmtSchool->bind_param("i", $studentid);
$stmtSchool->execute();
$resultSchool = $stmtSchool->get_result();

if ($resultSchool->num_rows === 1) {
    $row = $resultSchool->fetch_assoc();
    $response['school_name'] = $row['name'];
} else {
    $response['school_name'] = "Unknown School";
}

// Get fee stats from accounts table
$feeQuery = "SELECT totalfeeimposed, totalfeecollected, scholarshipsawarded, totalfeedue FROM accounts";
$feeResult = $conn->query($feeQuery);

if ($feeResult && $feeResult->num_rows === 1) {
    $feeRow = $feeResult->fetch_assoc();
    $response['fee'] = [
        "imposed" => (int)$feeRow['totalfeeimposed'],
        "collected" => (int)$feeRow['totalfeecollected'],
        "scholarship" => (int)$feeRow['scholarshipsawarded'],
        "due" => (int)$feeRow['totalfeedue']
    ];
} else {
    $response['fee'] = [
        "imposed" => 0,
        "collected" => 0,
        "scholarship" => 0,
        "due" => 0
    ];
}

// Get total number of students (excluding admin)
$studentCountQuery = "SELECT COUNT(*) as count FROM auth_user WHERE role = 'student'";
$resultCount = $conn->query($studentCountQuery);

if ($resultCount && $row = $resultCount->fetch_assoc()) {
    $response['total_students'] = (int)$row['count'];
} else {
    $response['total_students'] = 0;
}

// Final response
$response['status'] = "success";

echo json_encode($response);

// Close DB resources
$stmtSchool->close();
$conn->close();
?>
