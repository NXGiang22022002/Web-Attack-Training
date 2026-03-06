<?php
session_start();
require __DIR__ . "/config.php";

if (!isset($_SESSION["user"])) {
    header("Location: /CHALLEGE1/login.php");
    exit;
}

$me = $_SESSION["user"];
$myId = (int)($me["id"] ?? 0);
if ($myId <= 0) {
    $stmt = $pdo->prepare("SELECT id FROM `user` WHERE tendangnhap=? LIMIT 1");
    $stmt->execute([$me["tendangnhap"]]);
    $row = $stmt->fetch();
    if (!$row) { header("Location: /CHALLEGE1/login.php"); exit; }
    $myId = (int)$row["id"];
    $_SESSION["user"]["id"] = $myId;
}

$id = (int)($_GET["id"] ?? 0);
$to = (int)($_GET["to"] ?? 0);
if ($id <= 0 || $to <= 0) {
    header("Location: /CHALLEGE1/users.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM messages WHERE id=? LIMIT 1");
$stmt->execute([$id]);
$msg = $stmt->fetch();

if (!$msg || (int)$msg["sender_id"] !== $myId) {
    header("Location: /CHALLEGE1/user_detail.php?id=" . $to);
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $content = trim($_POST["content"] ?? "");
    if ($content === "") {
        $error = "Nội dung không được trống.";
    } else {
        $stmt = $pdo->prepare("UPDATE messages SET content=?, updated_at=NOW() WHERE id=? AND sender_id=?");
        $stmt->execute([$content, $id, $myId]);
        header("Location: /CHALLEGE1/user_detail.php?id=" . $to);
        exit;
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Sửa tin nhắn</title>
  <style>
    body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
    .wrap{max-width:680px;margin:40px auto;padding:0 16px}
    .card{background:#111827;border:1px solid #1f2937;border-radius:14px;padding:16px}
    textarea{width:100%;min-height:120px;border-radius:12px;border:1px solid #334155;background:#0b1220;color:#e5e7eb;padding:10px}
    button{margin-top:10px;padding:10px 14px;border:0;border-radius:10px;background:#60a5fa;font-weight:bold;cursor:pointer}
    a{color:#94a3b8;text-decoration:none}
    .err{margin-top:10px;padding:10px 12px;border-radius:12px;background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);color:#fecaca}
  </style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <h3 style="margin:0 0 10px;">Sửa tin nhắn</h3>
    <a href="/CHALLEGE1/user_detail.php?id=<?= (int)$to ?>">← Quay lại</a>

    <?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post">
      <textarea name="content"><?= htmlspecialchars($msg["content"]) ?></textarea>
      <button type="submit">Lưu</button>
    </form>
  </div>
</div>
</body>
</html>