<?php
session_start();
require __DIR__ . "/../config.php";

if (!isset($_SESSION["user"]) || (int)$_SESSION["user"]["isteacher"] !== 1) {
    header("Location: /CHALLEGE1/login.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tendangnhap = trim($_POST["tendangnhap"] ?? "");
    $matkhau = $_POST["matkhau"] ?? "";
    $hoten = trim($_POST["hoten"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $sodienthoai = trim($_POST["sodienthoai"] ?? "");

    if ($tendangnhap === "" || $matkhau === "") {
        $error = "Tên đăng nhập và mật khẩu là bắt buộc.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO `user` (tendangnhap, matkhau, hoten, email, sodienthoai, isteacher)
                                   VALUES (?, ?, ?, ?, ?, 0)");
            $stmt->execute([$tendangnhap, $matkhau, $hoten, $email, $sodienthoai]);
            header("Location: students.php");
            exit;
        } catch (PDOException $e) {
            $error = "Không thêm được (có thể trùng tên đăng nhập).";
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Thêm sinh viên</title>
  <style>
    body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
    .wrap{max-width:520px;margin:40px auto;padding:18px;background:#111827;border:1px solid #1f2937;border-radius:14px}
    input{width:100%;padding:10px;border-radius:10px;border:1px solid #334155;background:#0b1220;color:#e5e7eb}
    label{display:block;margin:12px 0 6px;color:#94a3b8}
    button{margin-top:14px;width:100%;padding:11px;border:0;border-radius:10px;background:#22c55e;font-weight:bold;cursor:pointer}
    a{color:#60a5fa;text-decoration:none}
    .err{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);padding:10px;border-radius:12px;color:#fecaca;margin-top:10px}
  </style>
</head>
<body>
  <div class="wrap">
    <h2>Thêm sinh viên</h2>
    <a href="students.php">← Quay lại</a>
    <?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post">
      <label>Tên đăng nhập *</label>
      <input name="tendangnhap" required>

      <label>Mật khẩu *</label>
      <input name="matkhau" type="password" required>

      <label>Họ tên</label>
      <input name="hoten">

      <label>Email</label>
      <input name="email">

      <label>Số điện thoại</label>
      <input name="sodienthoai">

      <button type="submit">Lưu</button>
    </form>
  </div>
</body>
</html>