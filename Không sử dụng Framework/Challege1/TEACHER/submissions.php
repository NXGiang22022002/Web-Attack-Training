<?php
require __DIR__ . "/../auth.php";
requireTeacher();

$assignmentId = (int)($_GET["assignment_id"] ?? 0);
if ($assignmentId <= 0) {
    header("Location: assignment_list_teacher.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM assignments WHERE id=? LIMIT 1");
$stmt->execute([$assignmentId]);
$ass = $stmt->fetch();
if (!$ass) {
    header("Location: assignment_list_teacher.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT s.id, s.assignment_id, s.student_id, s.file_original_name, s.submitted_at,
           u.tendangnhap, u.hoten, u.email
    FROM submissions s
    JOIN `user` u ON u.id = s.student_id
    WHERE s.assignment_id = ?
    ORDER BY s.id DESC
");
$stmt->execute([$assignmentId]);
$subs = $stmt->fetchAll();
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Bài làm</title>
<style>
  body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
  .wrap{max-width:1000px;margin:40px auto;padding:0 16px}
  a{color:#60a5fa;text-decoration:none;font-weight:bold}
  table{width:100%;border-collapse:collapse;margin-top:16px;background:#111827;border-radius:14px;overflow:hidden}
  th,td{padding:12px;border-bottom:1px solid #1f2937;text-align:left}
  th{background:#0b1220}
  .muted{color:#94a3b8}
</style>
</head>
<body>
<div class="wrap">
  <h2 style="margin:0">Bài làm cho bài tập #<?= (int)$ass["id"] ?> - <?= htmlspecialchars($ass["title"]) ?></h2>
  <div class="muted" style="margin-top:6px;">
    File bài tập:
    <a href="/CHALLEGE1/download.php?type=assignment&id=<?= (int)$ass["id"] ?>">
      <?= htmlspecialchars($ass["file_original_name"]) ?>
    </a>
  </div>
  <div style="margin-top:10px;">
    <a href="assignment_list_teacher.php">← Quay lại danh sách bài tập</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>Sinh viên</th>
        <th>Email</th>
        <th>File bài làm</th>
        <th>Thời gian nộp</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($subs as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s["tendangnhap"]) ?> - <?= htmlspecialchars($s["hoten"] ?? "") ?></td>
          <td><?= htmlspecialchars($s["email"] ?? "") ?></td>
          <td>
            <a href="/CHALLEGE1/download.php?type=submission&id=<?= (int)$s["id"] ?>">
              <?= htmlspecialchars($s["file_original_name"]) ?>
            </a>
          </td>
          <td><?= htmlspecialchars($s["submitted_at"]) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (count($subs) === 0): ?>
        <tr><td colspan="5" class="muted">Chưa có sinh viên nộp bài.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>