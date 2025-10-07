<?php
require "dbconn.php";

$class = $_POST['class']; 

$sql = "SELECT studentid, name, class, sec FROM studentprofile WHERE class = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $class);
$stmt->execute();
$result = $stmt->get_result();

$students = [];

while ($row = $result->fetch_assoc()) {
    $studentid = $row['studentid'];

    // Check for due payments
    $dueQuery = "SELECT 1 FROM payment WHERE studentid = ? AND status = 'due' LIMIT 1";
    $dueStmt = $conn->prepare($dueQuery);
    $dueStmt->bind_param("i", $studentid);
    $dueStmt->execute();
    $dueResult = $dueStmt->get_result();

    $status = ($dueResult->num_rows > 0) ? 'due' : 'paid';

    $students[] = [
        "studentid" => $row['studentid'],
        "name"      => $row['name'],
        "class"     => $row['class'],
        "sec"       => $row['sec'],
        "status"    => $status
    ];

    $dueStmt->close();
}

echo json_encode([
    "status" => "success",
    "class" => $class,
    "data" => $students
]);

$stmt->close();
$conn->close();
?>
