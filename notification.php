<?php
header("Content-Type: application/json");
include 'dbconn.php';

// Check if studentid is provided via POST
if (!isset($_POST['studentid'])) {
    echo json_encode(["status" => "error", "message" => "Student ID is required"]);
    exit;
}

$studentId = intval($_POST['studentid']);
$today = date("Y-m-d");

$notifications = [];

/* 1. Fee Due Notifications */
$sql_fees = "SELECT feename, duedate 
             FROM payment 
             WHERE studentid = ? 
             AND status = 'due'
             AND DATEDIFF(duedate, ?) <= 7 
             AND DATEDIFF(duedate, ?) >= 0";

$stmt = $conn->prepare($sql_fees);
$stmt->bind_param("iss", $studentId, $today, $today);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $daysLeft = (strtotime($row['duedate']) - strtotime($today)) / (60 * 60 * 24);
    $daysLeft = intval($daysLeft); // To avoid decimal days in message
    $notifications[] = [
        "title" => "Fee Reminder",
        "message" => "$daysLeft day" . ($daysLeft == 1 ? "" : "s") . " left to pay your " . $row['feename']
    ];
}
$stmt->close();

/* 2. Random General Notification */
$sql_general = "SELECT Notification FROM notifications ORDER BY RAND() LIMIT 2";
$result_general = $conn->query($sql_general);
while ($row = $result_general->fetch_assoc()) {
    $notifications[] = [
        "title" => "Information",
        "message" => $row['Notification']
    ];
}

echo json_encode([
    "status" => "success",
    "notifications" => $notifications
]);

$conn->close();
?>
