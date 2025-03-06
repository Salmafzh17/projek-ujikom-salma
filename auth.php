<?php
session_start();
include "konektor/db.php"; // Koneksi ke database

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            header("Location: konektor/ds.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (strlen($password) < 8) {
        $error = "Password harus minimal 8 karakter!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $error = "Email sudah digunakan!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: auth.php" );
                exit();
            } else {
                $error = "Pendaftaran gagal, coba lagi!";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login & Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Notifikasi -->
    <?php if (!empty($error)) : ?>
        <div class="notification">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="auth-wrapper">
        <div class="auth-container">
            <!-- Login -->
            <div class="login-container">
                <div class="login-form">
                    <h2>Login</h2>
                    <form method="POST">
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit" name="login">Login</button>
                    </form>
                    <p>Belum punya akun? <a href="#" class="toggle-form" style="color: rgb(251, 124, 255);">Daftar di sini</a></p>
                </div>
                <div class="login-image">
                    <img src="assets/login-image.jpg" alt="Login Image">
                </div>
            </div>

            <!-- Register -->
            <div class="register-container">
                <div class="register-image">
                    <img src="assets/login-image.jpg" alt="Register Image">
                </div>
                <div class="register-form">
                    <h2>Register</h2>
                    <form method="POST">
                        <input type="text" name="username" placeholder="Username" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit" name="register">Daftar</button>
                    </form>
                    <p>Sudah punya akun? <a href="#" class="toggle-form" style="color: rgb(251, 124, 255);">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        setTimeout(function() {
            let notif = document.querySelector(".notification");
            if (notif) {
                notif.style.opacity = "0";
                setTimeout(() => notif.remove(), 500);
            }
        }, 3000);
    </script>

</body>

</html>