<?php
require "dbconn.php";
header('Content-Type: application/json');

$response = array();

if (
    isset($_POST["studentid"]) && 
    isset($_POST["schname"]) && 
    isset($_POST["percentage"]) && 
    isset($_POST["feename"]) && 
    isset($_POST["incharge"])
) {
    $studentid = $_POST["studentid"];
    $schname = $_POST["schname"];
    $percentage = floatval($_POST["percentage"]);
    $feename = $_POST["feename"];
    $incharge = $_POST["incharge"];

    // Step 1: Get the original fee amount from payment table
    $query = $conn->prepare("SELECT feeamt FROM payment WHERE studentid = ? AND feename = ?");
    $query->bind_param("ss", $studentid, $feename);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $original_fee = floatval($row["feeamt"]);

        // Step 2: Calculate the amount reduced
        $amountreduced = ($percentage / 100) * $original_fee;
        $new_feeamt = $original_fee - $amountreduced;

        // Step 3: Insert into scholarship table
        $insert_sch = $conn->prepare("
            INSERT INTO scholarship 
            (studentid, schname, percentage, amountreduced, incharge, feename) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $insert_sch->bind_param("ssddss", $studentid, $schname, $percentage, $amountreduced, $incharge, $feename);

        // Step 4 & 5: Update payment table with new feeamt and set schawarded = 'yes'
        $update_payment = $conn->prepare("UPDATE payment SET feeamt = ?, schawarded = 'yes' WHERE studentid = ? AND feename = ?");
        $update_payment->bind_param("dss", $new_feeamt, $studentid, $feename);

        if ($insert_sch->execute() && $update_payment->execute()) {
            $response["status"] = "success";
            $response["message"] = "Scholarship applied and payment updated successfully.";
        } else {
            $response["status"] = "error";
            $response["message"] = "Error while updating: " . $conn->error;
        }

    } else {
        $response["status"] = "error";
        $response["message"] = "Student or fee type not found in payment table.";
    }

} else {
    $response["status"] = "error";
    $response["message"] = "Please provide studentid, schname, percentage, feename, and incharge.";
}

echo json_encode($response);
ob_start();
include "accounts.php";
ob_end_clean();

?>
