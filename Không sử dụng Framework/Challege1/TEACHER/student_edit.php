<?php
session_start();
require __DIR__ . "/../config.php";

if (!isset($_SESSION["user"]) || (int)$_SESSION["user"]["isteacher"] !== 1) {
    header("Location: /CHALLEGE1/login.php");
    exit;
}

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) { header("Location: students.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM `user` WHERE id = ? AND isteacher = 0 LIMIT 1");
$stmt->execute([$id]);
$st = $stmt->fetch();
if (!$st) { header("Location: students.php"); exit; }

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tendangnhap = trim($_POST["tendangnhap"] ?? "");
    $matkhau = $_POST["matkhau"] ?? "";
    $hoten = trim($_POST["hoten"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $sodienthoai = trim($_POST["sodienthoai"] ?? "");

    if ($tendangnhap === "") {
        $error = "Tên đăng nhập là bắt buộc.";
    } else {
        // Nếu để trống mật khẩu thì giữ nguyên
        if ($matkhau === "") {
            $stmt = $pdo->prepare("UPDATE `user` SET tendangnhap=?, hoten=?, email=?, sodienthoai=? WHERE id=? AND isteacher=0");
            $stmt->execute([$tendangnhap, $hoten, $email, $sodienthoai, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE `user` SET tendangnhap=?, matkhau=?, hoten=?, email=?, sodienthoai=? WHERE id=? AND isteacher=0");
            $stmt->execute([$tendangnhap, $matkhau, $hoten, $email, $sodienthoai, $id]);
        }
        header("Location: students.php");
        exit;
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Sửa sinh viên</title>
  <style>
    body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
    .wrap{max-width:520px;margin:40px auto;padding:18px;background:#111827;border:1px solid #1f2937;border-radius:14px}
    input{width:100%;padding:10px;border-radius:10px;border:1px solid #334155;background:#0b1220;color:#e5e7eb}
    label{display:block;margin:12px 0 6px;color:#94a3b8}
    button{margin-top:14px;width:100%;padding:11px;border:0;border-radius:10px;background:#60a5fa;font-weight:bold;cursor:pointer}
    a{color:#60a5fa;text-decoration:none}
    .err{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);padding:10px;border-radius:12px;color:#fecaca;margin-top:10px}
    .muted{color:#94a3b8;font-size:12.5px}
  </style>
</head>
<body>
  <div class="wrap">
    <h2>Sửa sinh viên #<?= (int)$st["id"] ?></h2>
    <a href="students.php">← Quay lại</a>
    <?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post">
      <label>Tên đăng nhập *</label>
      <input name="tendangnhap" value="<?= htmlspecialchars($st["tendangnhap"]) ?>" required>

      <label>Mật khẩu (để trống nếu không đổi)</label>
      <input name="matkhau" type="password" placeholder="Không nhập = giữ nguyên">
      <div class="muted">Nếu bạn đang lưu mật khẩu dạng text, sẽ update theo đúng text bạn nhập.</div>

      <label>Họ tên</label>
      <input name="hoten" value="<?= htmlspecialchars($st["hoten"] ?? "") ?>">

      <label>Email</label>
      <input name="email" value="<?= htmlspecialchars($st["email"] ?? "") ?>">

      <label>Số điện thoại</label>
      <input name="sodienthoai" value="<?= htmlspecialchars($st["sodienthoai"] ?? "") ?>">

      <button type="submit">Cập nhật</button>
    </form>
  </div>
</body>
</html>