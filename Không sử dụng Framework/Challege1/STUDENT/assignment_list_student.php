<?php
require __DIR__ . "/../auth.php";
requireStudent();

$studentId = currentUserId($pdo);

$assignments = $pdo->query("
  SELECT a.*,
         (SELECT COUNT(*) FROM submissions s WHERE s.assignment_id=a.id AND s.student_id=$studentId) AS submitted
  FROM assignments a
  ORDER BY a.id ASC
")->fetchAll();
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Bài tập</title>
<style>
  body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
  .wrap{max-width:1000px;margin:40px auto;padding:0 16px}
  a{color:#60a5fa;text-decoration:none;font-weight:bold}
  table{width:100%;border-collapse:collapse;margin-top:16px;background:#111827;border-radius:14px;overflow:hidden}
  th,td{padding:12px;border-bottom:1px solid #1f2937;text-align:left}
  th{background:#0b1220}
  .tag{padding:4px 10px;border-radius:999px;font-size:12px;font-weight:bold}
  .ok{background:rgba(34,197,94,.18);color:#bbf7d0;border:1px solid rgba(34,197,94,.28)}
  .no{background:rgba(239,68,68,.18);color:#fecaca;border:1px solid rgba(239,68,68,.28)}
  .muted{color:#94a3b8}
</style>
</head>
<body>
<div class="wrap">
  <h2 style="margin:0">Danh sách bài tập (Student)</h2>
  <div class="muted" style="margin-top:6px;">Tải bài tập và nộp bài làm tương ứng</div>
  <div style="margin-top:10px;">
    <a href="home_student.php">← Home</a> | <a href="../logout.php">Logout</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>Tiêu đề</th>
        <th>File bài tập</th>
        <th>Trạng thái</th>
        <th>Nộp bài</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($assignments as $a): ?>
        <tr>
          <td><?= htmlspecialchars($a["title"]) ?></td>
          <td>
            <a href="/CHALLEGE1/download.php?type=assignment&id=<?= (int)$a["id"] ?>">
              <?= htmlspecialchars($a["file_original_name"]) ?>
            </a>
          </td>
          <td>
            <?php if ((int)$a["submitted"] === 1): ?>
              <span class="tag ok">Đã nộp</span>
            <?php else: ?>
              <span class="tag no">Chưa nộp</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="submit.php?assignment_id=<?= (int)$a["id"] ?>">Upload bài làm</a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (count($assignments) === 0): ?>
        <tr><td colspan="5" class="muted">Chưa có bài tập.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>