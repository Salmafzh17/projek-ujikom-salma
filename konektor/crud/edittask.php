<?php
session_start();
include("../db.php");

// Pastikan pengguna login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../index.php");
    exit();
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $title = strtoupper(trim($_POST["title"])); // Judul otomatis kapital
    $task = trim($_POST["task"]);
    $deadline = !empty($_POST["deadline"]) ? $_POST["deadline"] : NULL;
    $priority = $_POST["priority"]; // Ambil nilai prioritas dari input form tanpa default "Biasa"

    $stmt = $conn->prepare("UPDATE tasks SET title = ?, task = ?, deadline = ?, priority = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $task, $deadline, $priority, $id);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php");
        exit();
    } else {
        echo "Gagal mengupdate tugas!";
    }

    $stmt->close();
}

// Ambil ID tugas dari URL
$id = $_GET["id"];
$result = $conn->query("SELECT * FROM tasks WHERE id = $id");
$row = $result->fetch_assoc();

// Pastikan hanya user yang berhak yang bisa mengedit
if ($row["user_id"] != $_SESSION["user_id"]) {
    die("Akses ditolak!");
}

// Ambil nilai prioritas dari database
$priorityFromDB = $row['priority'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }

        /* Dashboard Layout */
        .dashboard-container {
            display: flex;
            width: 100%;
        }

        .sidebar {
            width: 200px;
            background: linear-gradient(135deg, rgba(255, 75, 162, 0.51), rgba(255, 129, 205, 0.77));
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 0px 20px 0px 0px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            color: whitesmoke;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            transition: background 0.3s, width 0.3s;
        }

        .sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.2);
            display: block;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 40px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            text-align: center;
        }

        h2 {
            font-size: 22px;
            margin-bottom: 20px;
            color: #333;
        }

        input,
        button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 16px;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #74b9ff;
            box-shadow: 0px 0px 5px rgba(116, 185, 255, 0.5);
        }

        button {
            background: rgb(255, 116, 250);
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: rgb(249, 158, 255);
        }

        .form-container a {
            color: rgb(255, 150, 241);
        }

        a:hover {
            color: rgb(231, 95, 195);
        }

        label {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <nav class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="../ds.php">🏠 Dashboard</a></li>
                <li><a href="../dashboard.php">📝 My List</a></li>
                <li><a href="../logout.php">🚪 Logout</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <div class="form-container">
                <h2>Edit Tugas</h2>
                <form action="edittask.php" method="POST">
                    <input type="hidden" name="id" value="<?= $row['id']; ?>">

                    <label>Judul Tugas:</label>
                    <input type="text" name="title" value="<?= strtoupper($row['title']); ?>" required>

                    <label>Deskripsi Tugas:</label>
                    <input type="text" name="task" value="<?= $row['task']; ?>" required>

                    <label>Deadline:</label>
                    <input type="date" name="deadline" id="deadline" value="<?= $row['deadline']; ?>">

                    <label>Prioritas:</label>
                    <select name="priority" required>
                        <option value="Biasa" <?= ($priorityFromDB == 'biasa') ? 'selected' : ''; ?>>Biasa</option>
                        <option value="Sedang" <?= ($priorityFromDB == 'sedang') ? 'selected' : ''; ?>>Sedang</option>
                        <option value="Penting" <?= ($priorityFromDB == 'penting') ? 'selected' : ''; ?>>Penting</option>
                    </select>

                    <button type="submit">Simpan Perubahan</button>
                </form>

                <a href="../dashboard.php">⬅ Kembali</a>
            </div>
        </main>
    </div>
</body>

</html>