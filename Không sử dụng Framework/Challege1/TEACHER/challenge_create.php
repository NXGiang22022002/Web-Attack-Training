<?php
require __DIR__ . "/../auth.php";
requireTeacher();

$teacherId = currentUserId($pdo);

$baseDir = __DIR__ . "/../uploads/challenges";
if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);

$error = "";
$success = "";

function isValidAnswerFilename(string $name): bool {
    if (!preg_match('/^[a-z0-9 _.-]+\.txt$/', $name)) return false;
    if (str_contains($name, "..") || str_contains($name, "/") || str_contains($name, "\\")) return false;
    return true;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $hint = trim($_POST["hint"] ?? "");

    if ($hint === "") {
        $error = "Vui lòng nhập gợi ý (hint).";
    } elseif (!isset($_FILES["file"]) || $_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
        $error = "Vui lòng chọn file .txt để upload.";
    } else {
        $origName = $_FILES["file"]["name"];
        $tmp = $_FILES["file"]["tmp_name"];

        $origName = trim($origName);

        if (!isValidAnswerFilename($origName)) {
            $error = "Tên file không hợp lệ. Yêu cầu: không dấu, dùng a-z/0-9 và khoảng trắng, kết thúc bằng .txt (vd: bai tho tinh ban.txt).";
        } else {
            $stmt = $pdo->prepare("INSERT INTO challenges (teacher_id, hint) VALUES (?, ?)");
            $stmt->execute([$teacherId, $hint]);
            $challengeId = (int)$pdo->lastInsertId();

            $dir = $baseDir . "/" . $challengeId;
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            $dest = $dir . "/" . $origName;

            if (!move_uploaded_file($tmp, $dest)) {
                $error = "Upload thất bại (không move được file).";
                $pdo->prepare("DELETE FROM challenges WHERE id=?")->execute([$challengeId]);
            } else {
                $success = "Tạo challenge thành công! ID = " . $challengeId;
            }
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Tạo Challenge</title>
  <style>
    body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
    .wrap{max-width:720px;margin:40px auto;padding:18px;background:#111827;border:1px solid #1f2937;border-radius:14px}
    label{display:block;margin:12px 0 6px;color:#94a3b8}
    input,textarea{width:100%;padding:10px;border-radius:10px;border:1px solid #334155;background:#0b1220;color:#e5e7eb}
    button{margin-top:14px;width:100%;padding:11px;border:0;border-radius:10px;background:#22c55e;font-weight:bold;cursor:pointer}
    a{color:#60a5fa;text-decoration:none;font-weight:bold}
    .alert{margin-top:10px;padding:10px 12px;border-radius:12px;border:1px solid rgba(255,255,255,.10)}
    .ok{background:rgba(34,197,94,.10);border-color:rgba(34,197,94,.22);color:#bbf7d0}
    .err{background:rgba(239,68,68,.12);border-color:rgba(239,68,68,.25);color:#fecaca}
    .muted{color:#94a3b8;font-size:13px;line-height:1.5}
    code{background:#0b1220;border:1px solid #1f2937;padding:2px 6px;border-radius:8px}
  </style>
</head>
<body>
<div class="wrap">
  <h2 style="margin:0 0 8px;">Teacher - Tạo Challenge</h2>
  <div class="muted">
    Đáp án là <b>tên file</b> bạn upload.<br>
    Ví dụ tên file: <code>bai tho tinh ban.txt</code>
  </div>

  <div style="margin-top:10px;">
    <a href="/CHALLEGE1/TEACHER/home_teacher.php">← Home</a> |
    <a href="../logout.php">Logout</a>
  </div>

  <?php if ($success): ?><div class="alert ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data" style="margin-top:12px;">
    <label>Gợi ý (hint) *</label>
    <textarea name="hint" rows="4" required></textarea>

    <label>Upload file .txt (tên file là đáp án) *</label>
    <input type="file" name="file" accept=".txt" required>

    <button type="submit">Tạo Challenge</button>
  </form>
</div>
</body>
</html>