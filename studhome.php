<?php
header('Content-Type: application/json');
require "dbconn.php";

$studentid = isset($_POST['studentid']) ? $_POST['studentid'] : null;

if (!$studentid) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing studentid in POST data"
    ]);
    exit;
}

$response = [];

$stmt = $conn->prepare("
    SELECT 
        sp.name,
        sp.class,
        sp.sec,
        sp.photo,
        i.incharge
    FROM studentprofile sp
    JOIN incharge i ON sp.class = i.class AND sp.sec = i.sec
    WHERE sp.studentid = ?
");

$stmt->bind_param("i", $studentid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    $response['status'] = "success";
    $response['profile'] = [
        "name" => $row['name'],
        "class" => $row['class'],
        "sec" => $row['sec'],
        "photo" => $row['photo'],
        "incharge" => $row['incharge']
    ];
} else {
    $response['status'] = "error";
    $response['message'] = "Profile not found";
}

echo json_encode($response);
$stmt->close();
$conn->close();
?>
