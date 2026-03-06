<?php
session_start();
require __DIR__ . "/../config.php";

if (!isset($_SESSION["user"]) || (int)$_SESSION["user"]["isteacher"] !== 1) {
    header("Location: /CHALLEGE1/login.php");
    exit;
}

$students = $pdo->query("SELECT id, tendangnhap, hoten, email, sodienthoai 
                         FROM `user` 
                         WHERE isteacher = 0
                         ORDER BY id ASC")->fetchAll();
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Quản lý sinh viên</title>
  <style>
    body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
    .wrap{max-width:1000px;margin:40px auto;padding:0 16px}
    .top{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
    a.btn{background:#22c55e;color:#04120a;padding:10px 14px;border-radius:10px;text-decoration:none;font-weight:bold}
    a.btn2{background:#334155;color:#e5e7eb;padding:10px 14px;border-radius:10px;text-decoration:none}
    a.btn3{background:#334155;color:#e5e7eb;padding:10px 14px;border-radius:10px;text-decoration:none}
    table{width:100%;border-collapse:collapse;margin-top:16px;background:#111827;border-radius:14px;overflow:hidden}
    th,td{padding:12px;border-bottom:1px solid #1f2937;text-align:left}
    th{background:#0b1220}
    .actions a{margin-right:10px;text-decoration:none;font-weight:bold}
    .edit{color:#60a5fa}
    .del{color:#ef4444}
    .muted{color:#94a3b8}
  </style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <div>
      <h2 style="margin:0">Quản lý sinh viên</h2>
    </div>
    <div>
      <a class="btn" href="student_create.php">+ Thêm sinh viên</a>
      <a class="btn2" href="home_teacher.php">Home</a>
      <a class="btn3" href="../logout.php">Logout</a>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Tên đăng nhập</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>SĐT</th>
        <th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($students as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s["tendangnhap"]) ?></td>
          <td><?= htmlspecialchars($s["hoten"] ?? "") ?></td>
          <td><?= htmlspecialchars($s["email"] ?? "") ?></td>
          <td><?= htmlspecialchars($s["sodienthoai"] ?? "") ?></td>
          <td class="actions">
            <a class="edit" href="student_edit.php?id=<?= (int)$s["id"] ?>">Sửa</a>
            <a class="del" href="student_delete.php?id=<?= (int)$s["id"] ?>" onclick="return confirm('Xóa sinh viên này?');">Xóa</a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (count($students) === 0): ?>
        <tr><td colspan="6" class="muted">Chưa có sinh viên.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>