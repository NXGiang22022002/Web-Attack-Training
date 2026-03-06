<?php
require __DIR__ . "/../auth.php";
requireTeacher();

$baseDir = __DIR__ . "/../uploads/challenges";

$id = (int)($_GET["id"] ?? 0);
$file = $_GET["file"] ?? "";

if ($id <= 0 || $file === "") {
    header("Location: challenge_list_teacher.php");
    exit;
}

if (str_contains($file, "..") || str_contains($file, "/") || str_contains($file, "\\") || !str_ends_with(strtolower($file), ".txt")) {
    http_response_code(400);
    exit("Bad request");
}

$path = $baseDir . "/" . $id . "/" . $file;
if (!is_file($path)) {
    http_response_code(404);
    exit("File not found");
}

$content = file_get_contents($path);
if ($content === false) $content = "";
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Xem đáp án</title>
<style>
  body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
  .wrap{max-width:900px;margin:40px auto;padding:0 16px}
  a{color:#60a5fa;text-decoration:none;font-weight:bold}
  .card{background:#111827;border:1px solid #1f2937;border-radius:14px;padding:16px;margin-top:14px}
  pre{white-space:pre-wrap;background:#0b1220;border:1px solid #1f2937;border-radius:12px;padding:12px}
  .muted{color:#94a3b8}
  code{background:#0b1220;border:1px solid #1f2937;padding:2px 6px;border-radius:8px}
</style>
</head>
<body>
<div class="wrap">
  <h2 style="margin:0;">Teacher - Xem nội dung file</h2>
  <div class="muted" style="margin-top:6px;">
    Challenge #<?= $id ?> — File: <code><?= htmlspecialchars($file) ?></code>
  </div>
  <div style="margin-top:10px;">
    <a href="challenge_list_teacher.php">← Quay lại danh sách</a> |
    <a href="../logout.php">Logout</a>
  </div>

  <div class="card">
    <pre><?= htmlspecialchars($content) ?></pre>
  </div>
</div>
</body>
</html>