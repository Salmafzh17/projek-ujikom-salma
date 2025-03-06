<?php
require '../../konektor/db.php';

if (isset($_GET['id'])) {
    $subtask_id = $_GET['id'];

    $update_query = "UPDATE subtasks SET status='selesai' WHERE id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $subtask_id);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php"); // Redirect kembali ke halaman utama
    } else {
        echo "Gagal menyelesaikan subtask.";
    }

    $stmt->close();
}

$conn->close();
