<?php
header('Content-Type: application/json');

// DB config
require "dbconn.php";

// DB connection error
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Check POST data
if (isset($_POST['studentid'])) {
    $studentid = intval($_POST['studentid']);

    // Step 1: Fetch student details
    $sql = "SELECT * FROM studentprofile WHERE studentid = $studentid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        // Get class and section
        $class = $conn->real_escape_string($student['class']);
        $sec = $conn->real_escape_string($student['sec']);

        // Step 2: Fetch incharge details using class and sec
        $incharge_sql = "SELECT incharge, inchargeno FROM incharge WHERE class = '$class' AND sec = '$sec'";
        $incharge_result = $conn->query($incharge_sql);

        if ($incharge_result->num_rows > 0) {
            $incharge = $incharge_result->fetch_assoc();
            $student['incharge'] = $incharge['incharge'];
            $student['inchargeno'] = $incharge['inchargeno'];
        } else {
            $student['incharge'] = null;
            $student['inchargeno'] = null;
        }

        // If photo is binary, encode it; otherwise keep as is
        // $student['photo'] = base64_encode($student['photo']); // Uncomment if needed

        echo json_encode($student);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Student not found"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Missing studentid parameter"]);
}

$conn->close();
?>
