<?php
require __DIR__ . "/../auth.php";
requireTeacher();

$baseDir = __DIR__ . "/../uploads/challenges";

$challenges = $pdo->query("SELECT id, hint, created_at FROM challenges ORDER BY id ASC")->fetchAll();

function listAnswersForChallenge(string $baseDir, int $id): array {
    $dir = $baseDir . "/" . $id;
    if (!is_dir($dir)) return [];
    $files = glob($dir . "/*.txt") ?: [];
    return array_map(fn($p) => basename($p), $files);
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Teacher - Challenges</title>
<style>
  body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
  .wrap{max-width:1100px;margin:40px auto;padding:0 16px}
  a{color:#60a5fa;text-decoration:none;font-weight:bold}
  table{width:100%;border-collapse:collapse;margin-top:16px;background:#111827;border-radius:14px;overflow:hidden}
  th,td{padding:12px;border-bottom:1px solid #1f2937;text-align:left;vertical-align:top}
  th{background:#0b1220}
  .muted{color:#94a3b8}
  code{background:#0b1220;border:1px solid #1f2937;padding:2px 6px;border-radius:8px}
</style>
</head>
<body>
<div class="wrap">
  <h2 style="margin:0;">Teacher - Danh sách Challenge</h2>
  <div style="margin-top:10px;">
    <a href="home_teacher.php">← Home</a> |
    <a href="challenge_create.php">+ Tạo challenge</a> |
    <a href="../logout.php">Logout</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Gợi ý</th>
        <th>Đáp án (tên file)</th>
        <th>Xem nội dung</th>
        <th>Ngày tạo</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($challenges as $c): ?>
        <?php $answers = listAnswersForChallenge($baseDir, (int)$c["id"]); ?>
        <tr>
          <td><?= (int)$c["id"] ?></td>
          <td><?= nl2br(htmlspecialchars($c["hint"])) ?></td>
          <td>
            <?php if (count($answers) === 0): ?>
              <span class="muted">Chưa có file</span>
            <?php else: ?>
              <?php foreach ($answers as $a): ?>
                <div><code><?= htmlspecialchars($a) ?></code></div>
              <?php endforeach; ?>
            <?php endif; ?>
          </td>
          <td>
            <?php if (count($answers) === 0): ?>
              <span class="muted">-</span>
            <?php else: ?>
              <?php foreach ($answers as $a): ?>
                <div>
                  <a href="challenge_view_teacher.php?id=<?= (int)$c["id"] ?>&file=<?= urlencode($a) ?>">Xem: <?= htmlspecialchars($a) ?></a>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </td>
          <td class="muted"><?= htmlspecialchars($c["created_at"]) ?></td>
        </tr>
      <?php endforeach; ?>

      <?php if (count($challenges) === 0): ?>
        <tr><td colspan="5" class="muted">Chưa có challenge.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>