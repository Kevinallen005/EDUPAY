
<?php
require "dbconn.php";

$studentid = $_POST['studentid'];
$feename = $_POST['feename'];
$remarks = isset($_POST['remarks']) && !empty(trim($_POST['remarks'])) ? $_POST['remarks'] : "ONLINE";

// Fetch username from auth_user
$userSQL = "SELECT username FROM auth_user WHERE studentid = ?";
$userStmt = $conn->prepare($userSQL);
$userStmt->bind_param("i", $studentid);
$userStmt->execute();
$userResult = $userStmt->get_result();
$username = ($userResult->num_rows > 0) ? $userResult->fetch_assoc()['username'] : '';

if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "Username not found"]);
    exit;
}

// Fetch payment record
$sql = "SELECT * FROM payment WHERE studentid = ? AND feename = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $studentid, $feename);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if (isset($row['status']) && strtolower($row['status']) === 'paid') {
        echo json_encode(["status" => "info", "message" => "Already paid"]);
        exit;
    }

    // 1. Update Payment Status First
    $updateSQL = "UPDATE payment SET status = 'paid' WHERE studentid = ? AND feename = ?";
    $updateStmt = $conn->prepare($updateSQL);
    $updateStmt->bind_param("is", $studentid, $feename);

    if ($updateStmt->execute()) {
        // 2. Insert into History
        $referenceid = uniqid("REF");
        $paydate = date("Y-m-d");

        $insertSQL = "INSERT INTO history 
            (studentid, username, feename, feetype, feeamt, duedate, paydate, referenceid, remarks)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSQL);
        $insertStmt->bind_param("isssissss",
            $row['studentid'],
            $username,
            $row['feename'],
            $row['feetype'],
            $row['feeamt'],
            $row['duedate'],
            $paydate,
            $referenceid,
            $remarks
        );

        if ($insertStmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Payment updated and history logged"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Payment updated but history insert failed"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Payment update failed"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No matching record found"]);
}

include "accounts.php";

?>
