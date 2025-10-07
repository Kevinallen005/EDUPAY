<?php
require "dbconn.php";

if (isset($_POST["studentid"]) && isset($_POST["username"]) && isset($_POST["name"]) && isset($_POST["feeamt"]) && isset($_POST["feetype"])
&& isset($_POST["feename"])  && isset($_POST["duedate"]) && isset($_POST["status"]) && isset($_POST["schawarded"])) {
    $studentid = $_POST["studentid"];
    $username = $_POST["username"];
    $name = $_POST["name"];
    $feeamt = $_POST["feeamt"];
    $feetype = $_POST["feetype"];
    $feename = $_POST["feename"];
    $duedate = $_POST["duedate"];
    $status = $_POST["status"];
    $schawarded= $_POST["schawarded"];
    $stmt = $conn->prepare("INSERT INTO payment (studentid,username,name,feeamt,feetype,feename,duedate,status,schawarded) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('sssssssss',$studentid,$username,$name,$feeamt,$feetype,$feename,$duedate,$status,$schawarded);
if($stmt->execute()){
 echo "Inserted successfully<br>";
}
else{
    echo "error" . $stmt->error ;
}
} else {
    echo "Please provide all required fields.";
}
 include "accounts.php";

?>