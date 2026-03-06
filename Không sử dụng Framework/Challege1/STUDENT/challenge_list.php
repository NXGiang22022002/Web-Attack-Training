<?php
require __DIR__ . "/../auth.php";
requireStudent();

$challenges = $pdo->query("SELECT id, hint, created_at FROM challenges ORDER BY id ASC")->fetchAll();
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Challenge</title>
<style>
  body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
  .wrap{max-width:1000px;margin:40px auto;padding:0 16px}
  a{color:#60a5fa;text-decoration:none;font-weight:bold}
  table{width:100%;border-collapse:collapse;margin-top:16px;background:#111827;border-radius:14px;overflow:hidden}
  th,td{padding:12px;border-bottom:1px solid #1f2937;text-align:left;vertical-align:top}
  th{background:#0b1220}
  .muted{color:#94a3b8}
</style>
</head>
<body>
<div class="wrap">
  <h2 style="margin:0;">Student - Danh sách Challenge</h2>
  <div class="muted" style="margin-top:6px;">Xem gợi ý và nhập đáp án</div>
  <div style="margin-top:10px;">
    <a href="home_student.php">← Home</a> | <a href="../logout.php">Logout</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Gợi ý</th>
        <th>Ngày tạo</th>
        <th>Chơi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($challenges as $c): ?>
        <tr>
          <td><?= (int)$c["id"] ?></td>
          <td><?= nl2br(htmlspecialchars($c["hint"])) ?></td>
          <td class="muted"><?= htmlspecialchars($c["created_at"]) ?></td>
          <td><a href="challenge_play.php?id=<?= (int)$c["id"] ?>">Nhập đáp án</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if (count($challenges) === 0): ?>
        <tr><td colspan="4" class="muted">Chưa có challenge.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>