<?php
require "dbconn.php";

// Query 1: total fee imposed = sum of feeamt + sum of scholarship reductions
$total_fee_query = "
    SELECT 
        (SELECT IFNULL(SUM(feeamt), 0) FROM payment) + 
        (SELECT IFNULL(SUM(amountreduced), 0) FROM scholarship) AS totalfeeimposed
";
$total_fee_result = $conn->query($total_fee_query);
$totalfeeimposed = ($total_fee_result->num_rows > 0) ? $total_fee_result->fetch_assoc()['totalfeeimposed'] : 0;

// Query 2: total fee collected = sum of feeamt where status = 'paid'
$collected_query = "SELECT IFNULL(SUM(feeamt), 0) AS totalfeecollected FROM payment WHERE status = 'paid'";
$collected_result = $conn->query($collected_query);
$totalfeecollected = ($collected_result->num_rows > 0) ? $collected_result->fetch_assoc()['totalfeecollected'] : 0;

// Query 3: total fee due = sum of feeamt where status = 'due'
$due_query = "SELECT IFNULL(SUM(feeamt), 0) AS totalfeedue FROM payment WHERE status = 'due'";
$due_result = $conn->query($due_query);
$totalfeedue = ($due_result->num_rows > 0) ? $due_result->fetch_assoc()['totalfeedue'] : 0;

// Query 4: scholarships awarded = sum of amountreduced
$sch_query = "SELECT IFNULL(SUM(amountreduced), 0) AS scholarshipsawarded FROM scholarship";
$sch_result = $conn->query($sch_query);
$scholarshipsawarded = ($sch_result->num_rows > 0) ? $sch_result->fetch_assoc()['scholarshipsawarded'] : 0;

// Update the accounts table
$update_stmt = $conn->prepare(
    "UPDATE accounts 
     SET totalfeeimposed = ?, 
         totalfeecollected = ?, 
         scholarshipsawarded = ?, 
         totalfeedue = ?"
);
$update_stmt->bind_param("dddd", $totalfeeimposed, $totalfeecollected, $scholarshipsawarded, $totalfeedue);
$update_stmt->execute();
?>
