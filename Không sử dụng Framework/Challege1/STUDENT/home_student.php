<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: /login.php");
    exit;
}

if ($_SESSION["user"]["isteacher"] != 0) {
    header("Location: /login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Student Dashboard</title>

<style>
body {
    margin:0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    color: white;
}

.navbar {
    display:flex;
    justify-content:center;
    gap:15px;
    flex-wrap:wrap;
    padding:20px;
    background: rgba(0,0,0,0.2);
    backdrop-filter: blur(10px);
}

.navbar a {
    padding:10px 18px;
    background:white;
    color:#2a5298;
    text-decoration:none;
    border-radius:8px;
    font-weight:bold;
    transition:0.3s;
}

.navbar a:hover {
    background:#ffd700;
    color:#1e3c72;
}

.container {
    display:flex;
    justify-content:center;
    align-items:center;
    height:calc(100vh - 100px);
}

.card {
    background: rgba(255,255,255,0.1);
    padding:40px;
    border-radius:15px;
    text-align:center;
    backdrop-filter: blur(10px);
    width:450px;
}

h1 { margin-bottom:20px; }
p { margin:8px 0; }

</style>
</head>

<body>

<div class="navbar">
    <a href="/CHALLEGE1/users.php">Người dùng</a>
    <a href="profile.php">Thông tin cá nhân</a>
    <a href="assignment_list_student.php">Bài tập</a>
    <a href="challenge_list.php">Challenge</a>
    <a href="../logout.php">Logout</a>
</div>

<div class="container">
    <div class="card">
        <h1>Student Dashboard</h1>
        <p>Xin chào: <b><?= htmlspecialchars($_SESSION["user"]["hoten"]) ?></b></p>
        <p>Email: <?= htmlspecialchars($_SESSION["user"]["email"]) ?></p>
    </div>
</div>

</body>
</html>