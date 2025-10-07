<?php
header('Content-Type: application/json');
require "dbconn.php";

$username = $_POST['username'];
$password = $_POST['password'];


$stmt = $conn->prepare("SELECT studentid, username, name, email , role FROM auth_user WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "status" => "success",
        "message" => "Login successful",
        "user" => [
            "studentid" => $row['studentid'],
            "username" => $row['username'],
            "name"     => $row['name'],
            "email"    => $row['email'],
            "role"     => $row['role']
            
        ]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid credentials"
    ]);
}
$stmt->close();
$conn->close();
?>
