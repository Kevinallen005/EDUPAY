<?php
require "dbconn.php";

$studentid = $_POST['studentid'];
$feename   = $_POST['feename'];
$months    = intval($_POST['months']);   // number of installments

if ($months < 2) {
    echo json_encode(["status" => "error", "message" => "Minimum 2 months required"]);
    exit;
}

// Fetch original payment row
$sql  = "SELECT * FROM payment WHERE studentid = ? AND feename = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $studentid, $feename);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "No matching record found"]);
    exit;
}

$row          = $result->fetch_assoc();
$originalAmt  = $row['feeamt'];
$originalDue  = $row['duedate']; // format: YYYY-mm-dd

// --------------------
// Check if months fit between now and due date
// --------------------
$currentDate = new DateTime();
$dueDate     = new DateTime($originalDue);

// Calculate difference in months
$interval    = $currentDate->diff($dueDate);
$diffMonths  = ($interval->y * 12) + $interval->m;

// If user asks more months than available until due date -> reject
if ($months > $diffMonths) {
    echo json_encode([
        "status"  => "error",
        "message" => "Only $diffMonths month(s) possible until due date"
    ]);
    exit;
}

// --------------------
// Fixed interest 2%
// --------------------
$interest           = 2; 
$totalWithInterest  = $originalAmt + ($originalAmt * $interest / 100);
$perInstallmentAmt  = round($totalWithInterest / $months, 2);

// --------------------
// Loop through installments
// --------------------
for ($i = $months; $i >= 1; $i--) {
    $installmentDate = clone $dueDate;

    if ($i < $months) {
        $daysBefore = ($months - $i) * 30; // keeping your 30-day spacing
        $installmentDate->modify("-$daysBefore days");
    }

    $newFeeName        = $row['feename'] . " I" . $i;
    $dueDateFormatted  = $installmentDate->format("Y-m-d"); // ✅ Fix: assign to variable

    $insertSQL = "INSERT INTO payment 
        (studentid, username, name, feeamt, feetype, feename, duedate, status, schawarded) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'due', ?)";
    $insertStmt = $conn->prepare($insertSQL);
    $partial    = "Partial";
    $insertStmt->bind_param(
        "issdssss",
        $row['studentid'],
        $row['username'],
        $row['name'],
        $perInstallmentAmt,
        $partial,
        $newFeeName,
        $dueDateFormatted,
        $row['schawarded']
    );
    $insertStmt->execute();
}

// --------------------
// Delete original record
// --------------------
$deleteSQL  = "DELETE FROM payment WHERE studentid = ? AND feename = ?";
$deleteStmt = $conn->prepare($deleteSQL);
$deleteStmt->bind_param("is", $studentid, $feename);
$deleteStmt->execute();

echo json_encode([
    "status"  => "success", 
    "message" => "$months installments created successfully with fixed 2% interest"
]);
?>
