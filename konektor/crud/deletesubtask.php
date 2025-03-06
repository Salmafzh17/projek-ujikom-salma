<?php
require '../../konektor/db.php'; // Sesuaikan dengan lokasi file koneksi database

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $subtask_id = $_GET["id"];

    // Query untuk menghapus subtask berdasarkan ID
    $query = "DELETE FROM subtasks WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $subtask_id);

    if ($stmt->execute()) {
        // Redirect kembali ke halaman sebelumnya setelah penghapusan
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    } else {
        echo "Gagal menghapus subtask!";
    }

    $stmt->close();
}

$conn->close();
