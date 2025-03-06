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

        .main-content {
            margin-left: 220px;
            padding: 20px;
            width: 100%;
            text-align: center;
        }

        .main-content h1 {
            color: #333;
        }

        .description {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 15px 25px;
            background: rgb(198, 137, 255);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: rgb(215, 155, 255);
        }
    </style>
</head>

<body>
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
            <h1>Selamat Datang di Aplikasi To-Do List</h1>
            <p class="description">Aplikasi ini digunakan untuk mencatat daftar tugas Anda agar lebih terorganisir dan produktif.</p>
            <a href="crud/addtask.php" class="btn">+ Tambah Tugas Baru</a>
        </main>
    </div>
</body>

</html>