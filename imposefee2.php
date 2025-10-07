<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'dbconn.php';

// Get IDs
$ids = [];
if (isset($_POST['id'])) {  // Accept id[] from POST
    $ids = $_POST['id'];
} elseif (isset($_GET['id'])) {
    $ids = explode(',', $_GET['id']);
}

if (empty($ids) || !is_array($ids)) {
    echo json_encode(["status" => "error", "message" => "No student IDs provided"]);
    exit;
}

$ids = array_map('intval', $ids);
$id_placeholders = implode(',', array_fill(0, count($ids), '?'));

$sql = "SELECT studentid, name FROM studentprofile WHERE studentid IN ($id_placeholders)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(["status" => "error", "message" => "SQL prepare failed"]);
    exit;
}

$types = str_repeat('i', count($ids));
$stmt->bind_param($types, ...$ids);

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "studentid" => $row['studentid'],
        "name" => $row['name']
    ];
}

if (!empty($data)) {
    echo json_encode(["status" => "success", "data" => $data]);
} else {
    echo json_encode(["status" => "error", "message" => "No records found"]);
}

$stmt->close();
$conn->close();
?>
