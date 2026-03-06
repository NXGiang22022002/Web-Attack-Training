<?php
require __DIR__ . "/../auth.php";
requireStudent();

$baseDir = __DIR__ . "/../uploads/challenges";

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) { header("Location: challenge_list.php"); exit; }

$stmt = $pdo->prepare("SELECT id, hint FROM challenges WHERE id=? LIMIT 1");
$stmt->execute([$id]);
$ch = $stmt->fetch();
if (!$ch) { header("Location: challenge_list.php"); exit; }

$error = "";
$content = null;

function safeNormalize(string $s): string {
    $s = strtolower(trim($s));
    $s = preg_replace('/\s+/', ' ', $s);
    return $s;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ans = trim($_POST["answer"] ?? "");
    if ($ans === "") {
        $error = "Vui lòng nhập đáp án.";
    } else {
        // Cho phép nhập có hoặc không có ".txt"
        $ansNorm = safeNormalize($ans);
        if (!str_ends_with($ansNorm, ".txt")) $ansNorm .= ".txt";

        if (str_contains($ansNorm, "/") || str_contains($ansNorm, "\\") || str_contains($ansNorm, "..")) {
            $error = "Đáp án không hợp lệ.";
        } else {
            $dir = $baseDir . "/" . (int)$id;

            if (!is_dir($dir)) {
                $error = "Challenge chưa có file (liên hệ giáo viên).";
            } else {
                $files = glob($dir . "/*.txt");
                $foundPath = null;

                foreach ($files as $path) {
                    $fname = basename($path);
                    if (safeNormalize($fname) === $ansNorm) {
                        $foundPath = $path;
                        break;
                    }
                }

                if (!$foundPath) {
                    $error = "Sai đáp án.";
                } else {
                    $content = file_get_contents($foundPath);
                    if ($content === false) $error = "Không đọc được file.";
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Play Challenge</title>
<style>
  body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
  .wrap{max-width:900px;margin:40px auto;padding:0 16px}
  .card{background:#111827;border:1px solid #1f2937;border-radius:14px;padding:16px;margin-top:14px}
  input{width:97.5%;padding:10px;border-radius:10px;border:1px solid #334155;background:#0b1220;color:#e5e7eb}
  button{margin-top:10px;padding:10px 14px;border:0;border-radius:10px;background:#22c55e;font-weight:bold;cursor:pointer}
  a{color:#60a5fa;text-decoration:none;font-weight:bold}
  .muted{color:#94a3b8}
  .alert{margin-top:10px;padding:10px 12px;border-radius:12px;border:1px solid rgba(255,255,255,.10)}
  .err{background:rgba(239,68,68,.12);border-color:rgba(239,68,68,.25);color:#fecaca}
  pre{white-space:pre-wrap;background:#0b1220;border:1px solid #1f2937;border-radius:12px;padding:12px}
</style>
</head>
<body>
<div class="wrap">
  <h2 style="margin:0;">Challenge #<?= (int)$ch["id"] ?></h2>
  <div style="margin-top:10px;">
    <a href="challenge_list.php">← Danh sách challenge</a> | <a href="../logout.php">Logout</a>
  </div>

  <div class="card">
    <h3 style="margin:0 0 8px;">Gợi ý</h3>
    <div class="muted"><?= nl2br(htmlspecialchars($ch["hint"])) ?></div>
  </div>

  <div class="card">
    <h3 style="margin:0 0 8px;">Nhập đáp án</h3>
    <div class="muted">Đáp án là <b>tên file</b> (có thể nhập có hoặc không có <code>.txt</code>).</div>

    <?php if ($error): ?><div class="alert err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post">
      <input name="answer" placeholder="vd: bai tho tinh ban.txt" required>
      <button type="submit">Submit</button>
    </form>
  </div>

  <?php if ($content !== null): ?>
    <div class="card">
      <h3 style="margin:0 0 8px;">🎉 Đúng rồi! Nội dung file:</h3>
      <pre><?= htmlspecialchars($content) ?></pre>
    </div>
  <?php endif; ?>
</div>
</body>
</html>