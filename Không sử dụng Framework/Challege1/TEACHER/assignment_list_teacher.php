<?php
require __DIR__ . "/../auth.php";
requireTeacher();

$teacherId = currentUserId($pdo);

$assignments = $pdo->query("SELECT a.*,
                           (SELECT COUNT(*) FROM submissions s WHERE s.assignment_id=a.id) AS submission_count
                           FROM assignments a
                           ORDER BY a.id ASC")->fetchAll();
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Danh sách bài tập</title>
<style>
  body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
  .wrap{max-width:1000px;margin:40px auto;padding:0 16px}
  .top{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
  a.btn{background:#22c55e;color:#04120a;padding:10px 14px;border-radius:10px;text-decoration:none;font-weight:bold}
  a.btn2{background:#334155;color:#e5e7eb;padding:10px 14px;border-radius:10px;text-decoration:none;font-weight:bold}
  table{width:100%;border-collapse:collapse;margin-top:16px;background:#111827;border-radius:14px;overflow:hidden}
  th,td{padding:12px;border-bottom:1px solid #1f2937;text-align:left}
  th{background:#0b1220}
  a.link{color:#60a5fa;text-decoration:none;font-weight:bold}
  a.link:hover{text-decoration:underline}
  .muted{color:#94a3b8}
</style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <div>
      <h2 style="margin:0">Bài tập (Teacher)</h2>
      <div class="muted">Giáo Viên upload bài, xem bài làm theo từng bài</div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      <a class="btn" href="assignment_create.php">+ Giao bài</a>
      <a class="btn2" href="home_teacher.php">← Home</a>
      <a class="btn2" href="../logout.php">Logout</a>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Tiêu đề</th>
        <th>File bài tập</th>
        <th>Số bài nộp</th>
        <th>Xem bài làm</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($assignments as $a): ?>
        <tr>
          <td><?= htmlspecialchars($a["title"]) ?></td>
          <td>
            <a class="link" href="/CHALLEGE1/download.php?type=assignment&id=<?= (int)$a["id"] ?>">
              <?= htmlspecialchars($a["file_original_name"]) ?>
            </a>
          </td>
          <td><?= (int)$a["submission_count"] ?></td>
          <td>
            <a class="link" href="submissions.php?assignment_id=<?= (int)$a["id"] ?>">Danh sách bài làm</a>
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