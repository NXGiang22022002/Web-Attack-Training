<?php
session_start();
require __DIR__ . "/../config.php";

if (!isset($_SESSION["user"]) || (int)$_SESSION["user"]["isteacher"] !== 0) {
    header("Location: /CHALLEGE1/login.php");
    exit;
}

$user = $_SESSION["user"];
$error = "";
$success = "";

$stmt = $pdo->prepare("SELECT * FROM `user` WHERE tendangnhap = ? LIMIT 1");
$stmt->execute([$user["tendangnhap"]]);
$dbUser = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $matkhau = $_POST["matkhau"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $sodienthoai = trim($_POST["sodienthoai"] ?? "");

    try {
        if ($matkhau === "") {
            $stmt = $pdo->prepare("UPDATE `user` 
                                   SET email=?, sodienthoai=? 
                                   WHERE tendangnhap=?");
            $stmt->execute([$email, $sodienthoai, $user["tendangnhap"]]);
        } else {
            $stmt = $pdo->prepare("UPDATE `user` 
                                   SET matkhau=?, email=?, sodienthoai=? 
                                   WHERE tendangnhap=?");
            $stmt->execute([$matkhau, $email, $sodienthoai, $user["tendangnhap"]]);
        }

        $success = "Cập nhật thành công!";
        
        $_SESSION["user"]["email"] = $email;
        $_SESSION["user"]["sodienthoai"] = $sodienthoai;

    } catch (PDOException $e) {
        $error = "Có lỗi xảy ra!";
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Thông tin cá nhân</title>
<style>
body{
    font-family:Arial;
    margin:0;
    background:linear-gradient(135deg,#11998e,#38ef7d);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.card{
    background:white;
    padding:30px;
    border-radius:15px;
    width:420px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
}
h2{margin-top:0;text-align:center}
input{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    border-radius:8px;
    border:1px solid #ccc;
}
label{font-weight:bold}
button{
    width:100%;
    padding:10px;
    background:#2196F3;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
.success{
    background:#e8f5e9;
    padding:8px;
    border-radius:8px;
    color:#2e7d32;
    margin-bottom:10px;
}
.error{
    background:#ffebee;
    padding:8px;
    border-radius:8px;
    color:#c62828;
    margin-bottom:10px;
}
a{
    display:block;
    margin-top:15px;
    text-align:center;
    text-decoration:none;
}
</style>
</head>
<body>

<div class="card">
    <h2>Thông tin cá nhân</h2>

    <?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
    <?php if($error): ?><div class="error"><?= $error ?></div><?php endif; ?>

    <form method="post">
        <label>Tên đăng nhập (không được sửa)</label>
        <input value="<?= htmlspecialchars($dbUser["tendangnhap"]) ?>" disabled>

        <label>Họ tên (không được sửa)</label>
        <input value="<?= htmlspecialchars($dbUser["hoten"]) ?>" disabled>

        <label>Mật khẩu (để trống nếu không đổi)</label>
        <input type="password" name="matkhau">

        <label>Email</label>
        <input name="email" value="<?= htmlspecialchars($dbUser["email"]) ?>">

        <label>Số điện thoại</label>
        <input name="sodienthoai" value="<?= htmlspecialchars($dbUser["sodienthoai"]) ?>">

        <button type="submit">Cập nhật</button>
    </form>

    <a href="home_student.php">← Quay lại</a>
</div>

</body>
</html>