<?php
$host = "localhost"; // Server database
$user = "root"; // Username default XAMPP
$password = ""; // Password default kosong
$database = "todo_list"; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
