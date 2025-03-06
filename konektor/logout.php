<?php
session_start();
session_destroy(); // Hapus semua sesi
header("Location: ../index.html"); // Kembali ke halaman login
exit();
