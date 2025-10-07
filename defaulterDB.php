<?php
require "dbconn.php";

$class = $_POST['class']; 

// Fetch only students who have at least one due fee
$sql = "
SELECT DISTINCT sp.studentid, sp.name, sp.class, sp.sec
FROM studentprofile sp
JOIN payment p ON sp.studentid = p.studentid
WHERE sp.class = ? AND p.status = 'due'
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $class);
$stmt->execute();
$result = $stmt->get_result();

$students = [];

while ($row = $result->fetch_assoc()) {
    $students[] = [
        "studentid" => $row['studentid'],
        "name"      => $row['name'],
        "class"     => $row['class'],
        "sec"       => $row['sec'],
        "status"    => "due"
    ];
}

echo json_encode([
    "status" => "success",
    "class" => $class,
    "data" => $students
]);

$stmt->close();
$conn->close();
?>
