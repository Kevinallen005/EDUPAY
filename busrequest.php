<?php
header('Content-Type: application/json');
require "dbconn.php"; // make sure this sets $conn (mysqli) and uses InnoDB

$studentid      = isset($_POST['studentid']) ? intval($_POST['studentid']) : null;
$routename      = $_POST['routename'] ?? null;
$amount         = isset($_POST['amount']) ? intval($_POST['amount']) : null;
$via            = $_POST['via'] ?? null;
$boarding_point = $_POST['boarding_point'] ?? null;
$status         = "pending";

if (!$studentid || !$routename || !$amount || !$via || !$boarding_point) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit();
}

try {
    // start transaction
    $conn->begin_transaction();

    // 1) Lock the route row to prevent concurrent seat races
    $sqlLock = "SELECT seats FROM bus_routes WHERE routename = ? FOR UPDATE";
    $stmtLock = $conn->prepare($sqlLock);
    $stmtLock->bind_param("s", $routename);
    $stmtLock->execute();
    $resLock = $stmtLock->get_result();

    if ($resLock->num_rows === 0) {
        // route doesn't exist
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Route not found"]);
        exit();
    }

    $row = $resLock->fetch_assoc();
    $availableSeats = (int)$row['seats'];

    // 2) Prevent duplicate pending request by same student for the same route
    $sqlDup = "SELECT COUNT(*) AS cnt FROM bus_requests WHERE studentid = ? AND routename = ? AND status = 'pending'";
    $stmtDup = $conn->prepare($sqlDup);
    $stmtDup->bind_param("is", $studentid, $routename);
    $stmtDup->execute();
    $resDup = $stmtDup->get_result();
    $cnt = (int)$resDup->fetch_assoc()['cnt'];

    if ($cnt > 0) {
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "You already have a pending request for this route"]);
        exit();
    }

    if ($availableSeats <= 0) {
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "No seat available"]);
        exit();
    }

    // 3) Insert request
    $sqlInsert = "INSERT INTO bus_requests (studentid, routename, amount, status, via, boarding_point) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("isisss", $studentid, $routename, $amount, $status, $via, $boarding_point);

    if (!$stmtInsert->execute()) {
        throw new Exception("Insert failed: " . $stmtInsert->error);
    }

    // 4) Reduce seat count (we already have the row locked)
    $sqlUpdateSeats = "UPDATE bus_routes SET seats = seats - 1 WHERE routename = ?";
    $stmtUpdate = $conn->prepare($sqlUpdateSeats);
    $stmtUpdate->bind_param("s", $routename);
    if (!$stmtUpdate->execute()) {
        throw new Exception("Seat update failed: " . $stmtUpdate->error);
    }

    // compute remaining seats (we fetched before decrement)
    $remainingSeats = $availableSeats - 1;

    // commit
    $conn->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Request placed and seat reserved (status=pending).",
        "remaining_seats" => $remainingSeats,
        "boarding_point" => $boarding_point
    ]);

    // close stmts
    $stmtLock->close();
    $stmtDup->close();
    $stmtInsert->close();
    $stmtUpdate->close();

} catch (Exception $e) {
    if ($conn->errno) $conn->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
$conn->close();
?>
