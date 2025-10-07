<?php
require 'dbconn.php'; // your DB connection file
$studentid = $_POST['studentid'];
$feename = $_POST['feename'];

$response = array();

// Query
$sql = "
SELECT 
    s.name,
    s.class,
    s.sec,
    h.feename,
    h.feeamt,
    h.paydate,
    h.duedate,
    h.referenceid,
    h.remarks,
    CASE 
        WHEN p.schawarded = 'yes' THEN 
            COALESCE(sc.amountreduced, 'NO')
        ELSE 'NO'
    END AS ScholarshipAmount
FROM 
    studentprofile s
LEFT JOIN 
    history h ON s.studentid = h.studentid
LEFT JOIN 
    payment p ON h.studentid = p.studentid AND h.feename = p.feename
LEFT JOIN 
    scholarship sc ON h.studentid = sc.studentid AND h.feename = sc.feename
WHERE 
    h.studentid = ? AND h.feename = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $studentid, $feename);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $response = $result->fetch_assoc();
} else {
    $response['error'] = "No data found.";
}

echo json_encode($response);

?>
