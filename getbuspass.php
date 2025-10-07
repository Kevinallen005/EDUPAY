<?php
header('Content-Type: application/json');

require 'dbconn.php';  // This will provide $mysqli

$studentid = isset($_POST['studentid']) ? intval($_POST['studentid']) : 0;

if ($studentid <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid student ID"
    ]);
    exit;
}

// Fetch data from bus_requests table
$stmt1 = $conn->prepare("SELECT routename, boarding_point, amount, status, via FROM bus_requests WHERE studentid = ?");
$stmt1->bind_param('i', $studentid);
$stmt1->execute();
$result1 = $stmt1->get_result();
$bisRequest = $result1->fetch_assoc();

if (!$bisRequest) {
    echo json_encode([
        "success" => false,
        "message" => "No bus request found for the given student ID"
    ]);
    exit;
}

// Fetch data from studentprofile table
$stmt2 = $conn->prepare("SELECT name, photo FROM studentprofile WHERE studentid = ?");
$stmt2->bind_param('i', $studentid);
$stmt2->execute();
$result2 = $stmt2->get_result();
$studentProfile = $result2->fetch_assoc();

if (!$studentProfile) {
    echo json_encode([
        "success" => false,
        "message" => "No student profile found for the given student ID"
    ]);
    exit;
}

// Prepare and send response
$response = [
    "success" => true,
    "data" => [
        "studentid"       => strval($studentid),
        "name"            => $studentProfile['name'],
        "photo"           => $studentProfile['photo'],
        "routename"       => $bisRequest['routename'],
        "boarding_point"  => $bisRequest['boarding_point'],
        "amount"          => $bisRequest['amount'],
        "status"          => $bisRequest['status'],
        "valid_from"      => "2025-05-31",
        "valid_until"     => "2026-05-31",
        "via"             => $bisRequest['via'],
    ]
];

echo json_encode($response);
?>
