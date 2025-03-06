<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $task_id = $_GET["id"];
    $user_id = $_SESSION["user_id"];

    // Ambil status saat ini
    $sql = "SELECT status FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $new_status = $row["status"] ? 0 : 1;

        // Update status
        $sql = "UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $new_status, $task_id, $user_id);
        $stmt->execute();
    }
}

header("Location: dashboard.php");
exit();
?>
