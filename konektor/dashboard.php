<?php
session_start();
include "db.php"; // Koneksi ke database

// Cek apakah user sudah login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$search = "";

// Ambil daftar tugas pengguna dari database, dengan pencarian jika ada input
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM tasks WHERE user_id = ? AND (title LIKE ? OR task LIKE ?) ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $likeSearch = "%$search%";
    $stmt->bind_param("iss", $user_id, $likeSearch, $likeSearch);
} else {
    $sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ToDo List</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;

        }

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

        .dashboard-container {
            display: flex;
            width: 100%;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .main-content a {
            width: 200px;
            height: 200px;
            text-decoration: none;
        }

        .main-content a span {
            position: relative;
            top: 85px;

        }

        .btn {
            display: inline-block;
            padding: 55px 15px;
            background: rgb(198, 137, 255);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .btn:hover {
            background: rgb(215, 155, 255);
        }

        /* Styling untuk task-card */
        .task-card {
            padding: 15px;
            border-radius: 8px;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2);
            width: 200px;
            height: 280px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .task-card:hover {
            transform: translateY(-5px);
            /* Move up on hover */
            box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.1);
            /* Enhance shadow on hover */
        }

        .task-card h3 {
            margin: 0 0 10px;
            text-align: center;
        }

        .task-card p {
            margin: 5px 0;
            max-height: 50px;
            text-align: left;
            flex-grow: 1;
            overflow-y: auto;
        }

        .task-meta {
            font-size: 12px;
            color: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 5px;
        }

        .task-actions a {
            margin-left: 10px;
            text-decoration: none;
            font-size: 18px;
        }

        /* Styling untuk scrollbar */
        .task-card p::-webkit-scrollbar {
            width: 4px;
            /* Ukuran scrollbar kecil */
        }

        .task-card p::-webkit-scrollbar-track {
            background: transparent;
            /* Transparan */
        }

        .task-card p::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.5);
            /* Putih transparan */
            border-radius: 2px;
        }

        .task-card p::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        /* Untuk browser lain */
        .task-card p {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.5) transparent;
        }

        .modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .modal button {
            margin: 10px;
            padding: 10px;
            cursor: pointer;
        }

        .search-bar {
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }

        .search-bar input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar button {
            padding: 10px;
            background: rgb(198, 137, 255);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background: rgb(215, 155, 255);
        }

        .notif-container {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: rgba(255, 0, 0, 0.8);
            color: white;
            padding: 15px;
            border-radius: 5px;
            display: none;
            z-index: 1000;
            font-size: 14px;
            animation: fadeOut 5s forwards;
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            80% {
                opacity: 1;
            }

            100% {
                opacity: 0;
                display: none;
            }
        }

        .add-subtask {
            background: rgba(98, 0, 234, 0);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            align-items: end;
        }

        .add-subtask:hover {
            background: rgba(222, 222, 222, 0.24);
        }

        .subtask-container {
            max-height: 80px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.3) rgba(255, 255, 255, 0.1);
        }

        .subtask-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .subtask-list li {
            text-align: left;
            font-size: 14px;
            padding: 5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .subtask-list li a {

            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .subtask-list li a:hover {
            color: darkred;
        }
    </style>
</head>

<body>
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <p>Apakah Anda yakin ingin menghapus tugas ini?</p>
            <button id="confirmDelete">Hapus</button>
            <button id="cancelDelete">Batal</button>
        </div>
    </div>
    <div class="dashboard-container">
        <nav class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="ds.php">🏠 Dashboard</a></li>
                <li><a href="dashboard.php">📝 My List</a></li>
                <li><a href="logout.php" class="logout">🚪 Logout</a></li>
            </ul>
        </nav>
        <main class="main-content">
            <div id="notif" class="notif-container"></div>
            <form class="search-bar" method="GET" action="dashboard.php">
                <input type="text" id="searchTask" placeholder="Cari tugas..." class="search-input">

            </form>
            <a href="crud/addtask.php" class="btn"><span>+ Tambah Tugas</span></a>
            <?php

            $urgentTasks = [];
            $today = new DateTime();
            while ($row = $result->fetch_assoc()) {
                $task_id = $row["id"];

                // Ambil subtask dari database
                $subtask_sql = "SELECT * FROM subtasks WHERE task_id = ?";
                $subtask_stmt = $conn->prepare($subtask_sql);
                $subtask_stmt->bind_param("i", $task_id);
                $subtask_stmt->execute();
                $subtask_result = $subtask_stmt->get_result();
                $deadline = new DateTime($row["deadline"]);
                $interval = $today->diff($deadline)->days;

                // Hanya tambahkan tugas ke daftar notifikasi jika belum selesai
                if ($deadline >= $today && $interval <= 3 && $row["status"] === "belum selesai") {
                    $urgentTasks[] = $row["title"];
                }


                $colors = ['#ffeb3b', '#ff9800', '#f44336', '#4caf50', '#03a9f4', '#9c27b0'];
                $randomColor = $colors[array_rand($colors)];

                echo "<div class='task-card' style='background-color: $randomColor;'>";
                echo "<div>";
                echo "<h3>" . $row["title"] . "</h3>";
                echo "<p>" . $row["task"] . "</p>";
                echo "</div>";
                echo "<div class='subtask-container'>";
                echo "<ul class='subtask-list'>";
                while ($subtask = $subtask_result->fetch_assoc()) {
                    if ($subtask["status"] === "belum selesai") { // Tampilkan hanya yang belum selesai
                        echo "<li> - 
                            " . htmlspecialchars($subtask["subtask"]) . "
                            <a href='crud/completesubtask.php?id={$subtask['id']}' class='complete-subtask'>✔️</a>
                            <a href='crud/deletesubtask.php?id={$subtask['id']}' class='delete-subtask'  onclick='return confirmDelete()'>❌</a>
                          </li>";
                    }
                }
                echo "</ul>";
                echo "</div>";

                echo "<div style='text-align:left;'>";
                echo "<span style='text-align:left; font-size:15px;'>Prioritas: <strong>" . ($row["priority"] ?: "biasa") . "</strong></span>";
                echo "<div class='task-meta'>";
                echo "<span>" . $row["created_at"] . "</span> | <span style='color:brown;'>" . ($row["deadline"] ?: "-") . "</span>";
                echo "<div class='task-actions'>";
                echo "<a href='crud/edittask.php?id=" . $row['id'] . "'>✏️</a>";
                echo "<a href='crud/deletetask.php?id=" . $row['id'] . "' class='delete-task'>❌</a>";
                echo "</div>";
                echo "</div>";

                echo "<div class='task-container' data-task-id='" . $row["id"] . "' style='display: flex; justify-content: space-between; align-items: center;'>";
                echo "<span class='task-status' data-task-id='" . $row["id"] . "' style='cursor: pointer; color: blue; font-size: 12px;'>";
                echo $row["status"] === "belum selesai" ? "belum selesai" : "✅ Selesai";
                echo "</span>";

                // Tombol Add Subtask di luar task-status
                echo "<button class='add-subtask' data-task-id='" . $row['id'] . "' style='margin-left: 10px;'>➕ Subtask</button>";
                echo "</div>";

                echo "</div>";
                echo "</div>";
            }

            $jsonUrgentTasks = json_encode($urgentTasks);
            ?>
            <div id="confirmCompleteModal" class="modal">
                <div class="modal-content">
                    <p>Apakah Anda yakin ingin menyelesaikan tugas ini?</p>
                    <button id="confirmComplete">Selesai</button>
                    <button id="cancelComplete">Batal</button>
                </div>
            </div>

        </main>
    </div>
    <div id="confirmSubtaskModal" class="modal">
        <div class="modal-content">
            <p>Apakah Anda yakin ingin menyelesaikan subtask ini?</p>
            <button id="confirmSubtask">Selesai</button>
            <button id="cancelSubtask">Batal</button>
        </div>
    </div>


</body>

</html>
<script>
    function confirmDelete() {
        return confirm("Apakah Anda yakin ingin menghapus subtask ini?");
    }
    document.addEventListener("DOMContentLoaded", function() {
        const subtaskCompleteButtons = document.querySelectorAll(".complete-subtask");
        const modal = document.getElementById("confirmSubtaskModal");
        const confirmSubtask = document.getElementById("confirmSubtask");
        const cancelSubtask = document.getElementById("cancelSubtask");
        let subtaskUrl = "";

        subtaskCompleteButtons.forEach(button => {
            button.addEventListener("click", function(event) {
                event.preventDefault();
                subtaskUrl = this.getAttribute("href");
                modal.style.display = "flex";
            });
        });

        confirmSubtask.addEventListener("click", function() {
            window.location.href = subtaskUrl;
        });

        cancelSubtask.addEventListener("click", function() {
            modal.style.display = "none";
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchTask");
        const taskCards = document.querySelectorAll(".task-card");

        searchInput.addEventListener("input", function() {
            const searchText = searchInput.value.toLowerCase();

            taskCards.forEach(card => {
                const title = card.querySelector("h3").textContent.toLowerCase();
                const taskText = card.querySelector("p").textContent.toLowerCase();

                if (title.includes(searchText) || taskText.includes(searchText)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const taskCards = document.querySelectorAll(".task-card");
        taskCards.forEach(card => {
            const colors = ["#FAD2E1", "#A2D2FF", "#BDE0FE", "#CDEAC0", "#FFDAC1", "#E2C2FF", "#F9E2AF"];

            const randomColor = colors[Math.floor(Math.random() * colors.length)];
            card.style.backgroundColor = randomColor;
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll(".delete-task");
        const modal = document.getElementById("confirmModal");
        const confirmDelete = document.getElementById("confirmDelete");
        const cancelDelete = document.getElementById("cancelDelete");
        let deleteUrl = "";

        deleteButtons.forEach(button => {
            button.addEventListener("click", function(event) {
                event.preventDefault();
                deleteUrl = this.getAttribute("href");
                modal.style.display = "flex";
            });
        });

        confirmDelete.addEventListener("click", function() {
            window.location.href = deleteUrl;
        });

        cancelDelete.addEventListener("click", function() {
            modal.style.display = "none";
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        const completeButtons = document.querySelectorAll(".task-status");
        const modal = document.getElementById("confirmCompleteModal");
        const confirmComplete = document.getElementById("confirmComplete");
        const cancelComplete = document.getElementById("cancelComplete");
        let taskId = "";

        completeButtons.forEach(button => {
            button.addEventListener("click", function() {
                taskId = this.getAttribute("data-task-id");
                modal.style.display = "flex";
            });
        });

        confirmComplete.addEventListener("click", function() {
            window.location.href = "updatestatus.php?id=" + taskId;
        });

        cancelComplete.addEventListener("click", function() {
            modal.style.display = "none";
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        let urgentTasks = <?php echo $jsonUrgentTasks; ?>;

        if (urgentTasks.length > 0) {
            let notifBox = document.getElementById("notif");
            notifBox.innerHTML = "⚠️ Tugas berikut mendekati deadline:<br>- " + urgentTasks.join("<br> - ");
            notifBox.style.display = "block";

            setTimeout(() => {
                notifBox.style.display = "none";
            }, 5000);
        }
    });
    document.addEventListener("DOMContentLoaded", function() {
        const subtaskButtons = document.querySelectorAll(".add-subtask");

        subtaskButtons.forEach(button => {
            button.addEventListener("click", function() {
                const taskId = this.getAttribute("data-task-id");
                const subtaskTitle = prompt("Masukkan subtask:");
                if (subtaskTitle) {
                    window.location.href = `crud/addsubtask.php?task_id=${taskId}&subtask=${encodeURIComponent(subtaskTitle)}`;
                }
            });
        });
    });
</script>

<?php
$stmt->close();
$conn->close();
?>