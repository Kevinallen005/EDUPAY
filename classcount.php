<?php
require "dbconn.php"; // Your DB connection

// Prepare SQL to count number of students in each class (1 to 12)
$sql = "
    SELECT class, COUNT(*) AS count
    FROM studentprofile
    WHERE class IN (1,2,3,4,5,6,7,8,9,10,11,12)
    GROUP BY class
    ORDER BY class ASC
";

$result = $conn->query($sql);

$classCounts = [];

// Initialize counts for all classes to 0
for ($i = 1; $i <= 12; $i++) {
    $classCounts[(string)$i] = 0;
}

// Fill in the actual counts from DB
while ($row = $result->fetch_assoc()) {
    $class = (string)$row['class'];
    $classCounts[$class] = (int)$row['count'];
}

echo json_encode([
    "status" => "success",
    "data" => $classCounts
]);

$conn->close();
?>
