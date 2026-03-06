<?php
session_start();
require __DIR__ . "/config.php";

if (!isset($_SESSION["user"])) {
    header("Location: /CHALLEGE1/login.php");
    exit;
}

$users = $pdo->query("SELECT id, tendangnhap, hoten, email, sodienthoai, isteacher 
                      FROM `user`
                      ORDER BY id ASC")->fetchAll();
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Danh sách người dùng</title>
  <style>
    body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
    .wrap{max-width:1000px;margin:40px auto;padding:0 16px}
    .top{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
    a.btn{background:#334155;color:#e5e7eb;padding:10px 14px;border-radius:10px;text-decoration:none;font-weight:bold}
    table{width:100%;border-collapse:collapse;margin-top:16px;background:#111827;border-radius:14px;overflow:hidden}
    th,td{padding:12px;border-bottom:1px solid #1f2937;text-align:left}
    th{background:#0b1220}
    .role{padding:4px 10px;border-radius:999px;font-size:12px;font-weight:bold}
    .t{background:rgba(34,197,94,.18);color:#bbf7d0;border:1px solid rgba(34,197,94,.28)}
    .s{background:rgba(96,165,250,.18);color:#bfdbfe;border:1px solid rgba(96,165,250,.28)}
    a.link{color:#60a5fa;text-decoration:none;font-weight:bold}
    a.link:hover{text-decoration:underline}
    .muted{color:#94a3b8}
  </style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <div>
      <h2 style="margin:0">Danh sách người dùng</h2>
      <div class="muted">Click “Xem” để xem chi tiết và nhắn tin</div>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
      <?php
        $home = ((int)$_SESSION["user"]["isteacher"] === 1) ? "/CHALLEGE1/TEACHER/home_teacher.php" : "/CHALLEGE1/STUDENT/home_student.php";
      ?>
      <a class="btn" href="<?= $home ?>">Home</a>
      <a class="btn" href="/CHALLEGE1/logout.php">Logout</a>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Tên đăng nhập</th>
        <th>Họ tên</th>
        <th>Role</th>
        <th>Email</th>
        <th>SĐT</th>
        <th>Xem</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u["tendangnhap"]) ?></td>
          <td><?= htmlspecialchars($u["hoten"] ?? "") ?></td>
          <td>
            <?php if ((int)$u["isteacher"] === 1): ?>
              <span class="role t">TEACHER</span>
            <?php else: ?>
              <span class="role s">STUDENT</span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($u["email"] ?? "") ?></td>
          <td><?= htmlspecialchars($u["sodienthoai"] ?? "") ?></td>
          <td><a class="link" href="/CHALLEGE1/user_detail.php?id=<?= (int)$u["id"] ?>">Xem</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>