<?php
header('Content-Type: application/json');

// Include DB connection
require 'dbconn.php';

// First try to get POST parameters normally
$status = isset($_POST['status']) ? $_POST['status'] : null;
$studentid = isset($_POST['studentid']) ? $_POST['studentid'] : null;
$routename = isset($_POST['routename']) ? $_POST['routename'] : null;

// If they are still null, try reading raw input as fallback (in case of Content-Type issues)
if (is_null($status) || is_null($studentid) || is_null($routename)) {
    parse_str(file_get_contents("php://input"), $post_vars);
    $status = $post_vars['status'] ?? $status;
    $studentid = $post_vars['studentid'] ?? $studentid;
    $routename = $post_vars['routename'] ?? $routename;
}

// Validate required fields
if (empty($status) || empty($studentid) || empty($routename)) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required parameters'
    ]);
    exit;
}

// Prepare and execute update query
$sql = "UPDATE bus_requests SET status = ? WHERE studentid = ? AND routename = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sis", $status, $studentid, $routename);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Status updated successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update status'
    ]);
}

$stmt->close();
$conn->close();
?>
