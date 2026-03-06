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

$otherId = (int)($_GET["id"] ?? 0);
if ($otherId <= 0) {
    header("Location: /CHALLEGE1/users.php");
    exit;
}

$stmt = $pdo->prepare("SELECT id, tendangnhap, hoten, email, sodienthoai, isteacher FROM `user` WHERE id=? LIMIT 1");
$stmt->execute([$otherId]);
$other = $stmt->fetch();
if (!$other) {
    header("Location: /CHALLEGE1/users.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $content = trim($_POST["content"] ?? "");
    if ($content === "") {
        $error = "Nội dung tin nhắn không được để trống.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$myId, $otherId, $content]);
        $success = "Đã gửi tin nhắn!";
    }
}

$stmt = $pdo->prepare("
    SELECT m.id, m.sender_id, m.receiver_id, m.content, m.created_at, m.updated_at,
           su.hoten AS sender_hoten
    FROM messages m
    JOIN `user` su ON su.id = m.sender_id
    WHERE (m.sender_id = ? AND m.receiver_id = ?)
       OR (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY m.id DESC
");
$stmt->execute([$myId, $otherId, $otherId, $myId]);
$messages = $stmt->fetchAll();

$home = ((int)$me["isteacher"] === 1) ? "/CHALLEGE1/TEACHER/home_teacher.php" : "/CHALLEGE1/STUDENT/home_student.php";
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Chi tiết người dùng</title>
  <style>
    body{font-family:Arial;margin:0;background:#0f172a;color:#e5e7eb}
    .wrap{max-width:980px;margin:40px auto;padding:0 16px}
    .top{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
    a.btn{background:#334155;color:#e5e7eb;padding:10px 14px;border-radius:10px;text-decoration:none;font-weight:bold}
    .card{background:#111827;border:1px solid #1f2937;border-radius:14px;padding:16px;margin-top:14px}
    .muted{color:#94a3b8}
    textarea{width:97.5%;min-height:90px;border-radius:12px;border:1px solid #334155;background:#0b1220;color:#e5e7eb;padding:10px}
    button{margin-top:10px;padding:10px 14px;border:0;border-radius:10px;background:#22c55e;font-weight:bold;cursor:pointer}
    .alert{margin-top:10px;padding:10px 12px;border-radius:12px;border:1px solid rgba(255,255,255,.10)}
    .ok{background:rgba(34,197,94,.10);border-color:rgba(34,197,94,.22);color:#bbf7d0}
    .err{background:rgba(239,68,68,.12);border-color:rgba(239,68,68,.25);color:#fecaca}
    .msg{padding:12px;border-radius:14px;border:1px solid #1f2937;background:#0b1220;margin-top:10px}
    .msg .meta{display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;color:#94a3b8;font-size:12px}
    .msg .content{margin-top:6px;white-space:pre-wrap}
    .actions a{margin-right:12px;text-decoration:none;font-weight:bold}
    .edit{color:#60a5fa}
    .del{color:#ef4444}
    .role{padding:4px 10px;border-radius:999px;font-size:12px;font-weight:bold}
    .t{background:rgba(34,197,94,.18);color:#bbf7d0;border:1px solid rgba(34,197,94,.28)}
    .s{background:rgba(96,165,250,.18);color:#bfdbfe;border:1px solid rgba(96,165,250,.28)}
  </style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <div>
      <h2 style="margin:0">Chi tiết người dùng</h2>
      <div class="muted">Bạn đang xem hồ sơ của người khác và có thể nhắn tin</div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      <a class="btn" href="/CHALLEGE1/users.php">Danh sách</a>
      <a class="btn" href="<?= $home ?>">Home</a>
      <a class="btn" href="/CHALLEGE1/logout.php">Logout</a>
    </div>
  </div>

  <div class="card">
    <h3 style="margin:0 0 8px;">
      <?= htmlspecialchars($other["hoten"] ?? "") ?>
      <?php if ((int)$other["isteacher"] === 1): ?>
        <span class="role t">TEACHER</span>
      <?php else: ?>
        <span class="role s">STUDENT</span>
      <?php endif; ?>
    </h3>
    <div class="muted">Username: <b><?= htmlspecialchars($other["tendangnhap"]) ?></b></div>
    <div style="margin-top:10px">
      Email: <?= htmlspecialchars($other["email"] ?? "") ?><br>
      SĐT: <?= htmlspecialchars($other["sodienthoai"] ?? "") ?>
    </div>
  </div>

  <div class="card">
    <h3 style="margin:0 0 10px;">Gửi tin nhắn</h3>

    <?php if ($success): ?><div class="alert ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post">
      <textarea name="content" placeholder="Nhập tin nhắn..."></textarea>
      <button type="submit">Gửi</button>
    </form>
  </div>

  <div class="card">
    <h3 style="margin:0 0 6px;">Tin nhắn giữa bạn và <?= htmlspecialchars($other["hoten"]) ?></h3>
    <div class="muted">Bạn chỉ được sửa/xóa tin nhắn do bạn gửi.</div>

    <?php foreach ($messages as $m): ?>
      <div class="msg">
        <div class="meta">
          <div>
            From: <b><?= htmlspecialchars($m["sender_hoten"]) ?></b>
            <?= ((int)$m["sender_id"] === $myId) ? "(Bạn)" : "" ?>
          </div>
          <div>
            <?= htmlspecialchars($m["created_at"]) ?>
            <?php if (!empty($m["updated_at"])): ?>
              | edited: <?= htmlspecialchars($m["updated_at"]) ?>
            <?php endif; ?>
          </div>
        </div>

        <div class="content"><?= htmlspecialchars($m["content"]) ?></div>

        <?php if ((int)$m["sender_id"] === $myId): ?>
          <div class="actions" style="margin-top:8px;">
            <a class="edit" href="/CHALLEGE1/message_edit.php?id=<?= (int)$m["id"] ?>&to=<?= (int)$otherId ?>">Sửa</a>
            <a class="del" href="/CHALLEGE1/message_delete.php?id=<?= (int)$m["id"] ?>&to=<?= (int)$otherId ?>" onclick="return confirm('Xóa tin nhắn này?');">Xóa</a>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>

    <?php if (count($messages) === 0): ?>
      <div class="muted" style="margin-top:10px;">Chưa có tin nhắn.</div>
    <?php endif; ?>
  </div>

</div>
</body>
</html>