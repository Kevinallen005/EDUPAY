<?php
require "dbconn.php";

if (isset($_POST["studentid"]) && isset($_POST["username"]) && isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["password"])) {
    $studentid = $_POST["studentid"];
    $username = $_POST["username"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
        echo "Invalid Email Format";
        exit();
    }
    $stmt = $conn->prepare("INSERT INTO auth_user (studentid,username,name,email,password) VALUES (?,?,?,?,?)");
    $stmt->bind_param('sssss',$studentid,$username,$name,$email,$password);
if($stmt->execute()){
 echo "Inserted successfully";
}
else{
    echo "error" . $stmt->error ;
}
} else {
    echo "Please provide all required fields.";
}

mysqli_close($conn);
?>

