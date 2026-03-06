<?php
require __DIR__ . "/../auth.php";
requireTeacher();

$teacherId = currentUserId($pdo);
[$assignDir, $subDir] = ensureUploadDirs();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($title === "") {
        $error = "Vui lòng nhập tiêu đề bài tập.";
    } elseif (!isset($_FILES["file"]) || $_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
        $error = "Vui lòng chọn file bài tập để upload.";
    } else {
        $orig = $_FILES["file"]["name"];
        $tmp  = $_FILES["file"]["tmp_name"];

        $stored = randomStoredName($orig);
        $dest = $assignDir . "/" . $stored;

        if (!move_uploaded_file($tmp, $dest)) {
            $error = "Upload thất bại (không move được file).";
        } else {
            $stmt = $pdo->prepare("INSERT INTO assignments (teacher_id, title, description, file_original_name, file_stored_name)
                                   VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$teacherId, $title, $description, $orig, $stored]);

            header("Location: assignment_list_teacher.php");
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Giao bài</title>
  <style>
    body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
    .wrap{max-width:680px;margin:40px auto;padding:18px;background:#111827;border:1px solid #1f2937;border-radius:14px}
    input,textarea{width:100%;padding:10px;border-radius:10px;border:1px solid #334155;background:#0b1220;color:#e5e7eb}
    label{display:block;margin:12px 0 6px;color:#94a3b8}
    button{margin-top:14px;width:100%;padding:11px;border:0;border-radius:10px;background:#22c55e;font-weight:bold;cursor:pointer}
    a{color:#60a5fa;text-decoration:none}
    .err{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);padding:10px;border-radius:12px;color:#fecaca;margin-top:10px}
  </style>
</head>
<body>
<div class="wrap">
  <h2>Giao bài (Teacher)</h2>
  <a href="assignment_list_teacher.php">← Danh sách bài tập</a>
  <?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <label>Tiêu đề *</label>
    <input name="title" required>

    <label>Mô tả</label>
    <textarea name="description" rows="4"></textarea>

    <label>File bài tập *</label>
    <input type="file" name="file" required>

    <button type="submit">Upload bài tập</button>
  </form>
</div>
</body>
</html>