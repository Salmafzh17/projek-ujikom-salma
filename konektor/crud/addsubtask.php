<?php
include "../../konektor/db.php";


if (isset($_GET["task_id"]) && isset($_GET["subtask"])) {
    $task_id = $_GET["task_id"];
    $subtask = $_GET["subtask"];

    $sql = "INSERT INTO subtasks (task_id, subtask, status) VALUES (?, ?, 'belum selesai')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $task_id, $subtask);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php");
    } else {
        echo "Gagal menambah subtask.";
    }
    $stmt->close();
}
$conn->close();
