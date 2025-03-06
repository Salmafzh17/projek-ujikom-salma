<?php
session_start();
include "db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil status saat ini
    $query = "SELECT status FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    // Toggle status
    $new_status = ($task['status'] === "belum selesai") ? "selesai" : "belum selesai";

    // Update status
    $update_sql = "UPDATE tasks SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Status tugas berhasil diperbarui!";
    } else {
        $_SESSION['message'] = "Gagal memperbarui status tugas!";
    }

    $stmt->close();
    $conn->close();
}

header("Location:dashboard.php?task_completed=1");
exit();
