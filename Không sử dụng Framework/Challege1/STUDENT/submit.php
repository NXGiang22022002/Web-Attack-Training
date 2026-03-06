<?php
require __DIR__ . "/../auth.php";
requireStudent();

$studentId = currentUserId($pdo);
[$assignDir, $subDir] = ensureUploadDirs();

$assignmentId = (int)($_GET["assignment_id"] ?? 0);
if ($assignmentId <= 0) {
    header("Location: assignment_list_student.php");
    exit;
}

$stmt = $pdo->prepare("SELECT id, title, file_original_name FROM assignments WHERE id=? LIMIT 1");
$stmt->execute([$assignmentId]);
$ass = $stmt->fetch();
if (!$ass) {
    header("Location: assignment_list_student.php");
    exit;
}

$error = "";
$success = "";

$stmt = $pdo->prepare("SELECT * FROM submissions WHERE assignment_id=? AND student_id=? LIMIT 1");
$stmt->execute([$assignmentId, $studentId]);
$existing = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_FILES["file"]) || $_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
        $error = "Vui lòng chọn file bài làm.";
    } else {
        $orig = $_FILES["file"]["name"];
        $tmp  = $_FILES["file"]["tmp_name"];

        $stored = randomStoredName($orig);
        $dest = $subDir . "/" . $stored;

        if (!move_uploaded_file($tmp, $dest)) {
            $error = "Upload thất bại.";
        } else {
            if ($existing) {
                $oldPath = $subDir . "/" . $existing["file_stored_name"];
                if (is_file($oldPath)) @unlink($oldPath);

                $stmt = $pdo->prepare("UPDATE submissions
                                       SET file_original_name=?, file_stored_name=?, submitted_at=NOW()
                                       WHERE id=? AND student_id=?");
                $stmt->execute([$orig, $stored, (int)$existing["id"], $studentId]);
                $success = "Nộp lại bài thành công!";
            } else {
                $stmt = $pdo->prepare("INSERT INTO submissions (assignment_id, student_id, file_original_name, file_stored_name)
                                       VALUES (?, ?, ?, ?)");
                $stmt->execute([$assignmentId, $studentId, $orig, $stored]);
                $success = "Nộp bài thành công!";
            }
            header("Location: submit.php?assignment_id=" . $assignmentId);
            exit;
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM submissions WHERE assignment_id=? AND student_id=? LIMIT 1");
$stmt->execute([$assignmentId, $studentId]);
$existing = $stmt->fetch();
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Nộp bài</title>
<style>
  body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
  .wrap{max-width:680px;margin:40px auto;padding:18px;background:#111827;border:1px solid #1f2937;border-radius:14px}
  input[type=file]{width:100%;padding:10px;border-radius:10px;border:1px solid #334155;background:#0b1220;color:#e5e7eb}
  button{margin-top:14px;width:100%;padding:11px;border:0;border-radius:10px;background:#22c55e;font-weight:bold;cursor:pointer}
  a{color:#60a5fa;text-decoration:none;font-weight:bold}
  .muted{color:#94a3b8}
  .alert{margin-top:10px;padding:10px 12px;border-radius:12px;border:1px solid rgba(255,255,255,.10)}
  .ok{background:rgba(34,197,94,.10);border-color:rgba(34,197,94,.22);color:#bbf7d0}
  .err{background:rgba(239,68,68,.12);border-color:rgba(239,68,68,.25);color:#fecaca}
</style>
</head>
<body>
<div class="wrap">
  <h2 style="margin:0">Nộp bài</h2>
  <div class="muted" style="margin-top:6px;">
    Bài tập #<?= (int)$ass["id"] ?>: <b><?= htmlspecialchars($ass["title"]) ?></b><br>
    File bài tập: <a href="/CHALLEGE1/download.php?type=assignment&id=<?= (int)$ass["id"] ?>"><?= htmlspecialchars($ass["file_original_name"]) ?></a>
  </div>

  <div style="margin-top:10px;">
    <a href="assignment_list_student.php">← Danh sách bài tập</a>
  </div>

  <?php if ($error): ?><div class="alert err"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if ($success): ?><div class="alert ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data" style="margin-top:12px;">
    <input type="file" name="file" required>
    <button type="submit"><?= $existing ? "Nộp lại bài" : "Nộp bài" ?></button>
  </form>

  <?php if ($existing): ?>
    <div class="alert ok" style="margin-top:12px;">
      Bạn đã nộp: <b><?= htmlspecialchars($existing["file_original_name"]) ?></b><br>
      Thời gian: <?= htmlspecialchars($existing["submitted_at"]) ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>