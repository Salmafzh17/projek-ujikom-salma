<?php
session_start();
include "../db.php"; // Koneksi ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET["id"])) {
    $task_id = $_GET["id"];

    // Hapus tugas dari database
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $task_id);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php"); // Kembali ke dashboard setelah menghapus
        exit();
    } else {
        echo "Gagal menghapus tugas!";
    }

    $stmt->close();
}
$conn->close();
header("Location: ../dashboard.php?task_deleted=1");
exit();
