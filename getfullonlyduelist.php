<?php
require "dbconn.php"; // Your DB connection

$studentid = $_POST['studentid'];

$sql = "SELECT feename, feeamt 
        FROM payment 
        WHERE studentid = ? AND status = 'due' AND feetype = 'Full' AND feeamt>=1000 ";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dues = [];
    while ($row = $result->fetch_assoc()) {
        $dues[] = $row;
    }
    echo json_encode([
        "status" => "success",
        "data" => $dues
    ]);
} else {
    echo json_encode([
        "status" => "empty",
        "message" => "No due fees found"
    ]);
}

$stmt->close();
$conn->close();
?>
