<?php

include "dbconn.php";

$studentid = $_POST["studentid"];
$username = $_POST['username'];
$name = $_POST['name'];
$class = $_POST['class'];
$sec = $_POST['sec'];
$fathername = $_POST['fathername'];
$fatherno = $_POST['fatherno'];
$mothername = $_POST['mothername']; 
$motherno = $_POST['motherno'];
$bloodgroup = $_POST['bloodgroup'];
$email = $_POST['email'];

if (empty($studentid) || empty($username) || empty($name) || empty($class) || empty($sec) || empty($fathername) || empty($fatherno) || 
empty($mothername) || empty($motherno) || empty($bloodgroup) || empty($email)){
    echo "All fields are required.";
} else {

if (
    isset($_FILES['photo']) && isset($_POST["studentid"]) && isset($_POST['username']) &&
    isset($_POST['name']) && isset($_POST['class']) && isset($_POST['sec']) &&
    isset($_POST['fathername']) && isset($_POST['fatherno']) && isset($_POST['mothername']) &&
    isset($_POST['motherno']) && isset($_POST['bloodgroup']) && isset($_POST['email'])
) {
    // Upload photo to 'uploads/' folder
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $photo_name = basename($_FILES["photo"]["name"]);
    $target_file = $upload_dir . time() . "_" . $photo_name;

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $photo = $target_file;
    } else {
        die("Photo upload failed.");
    } 

    $sql = "INSERT INTO studentprofile (photo, studentid, username, name, class, sec, fathername, fatherno, mothername, motherno, bloodgroup, email)
            VALUES ('$photo', '$studentid', '$username', '$name', '$class', '$sec', '$fathername', '$fatherno', '$mothername', '$motherno', '$bloodgroup', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Record inserted successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
}
$conn->close();
?>
