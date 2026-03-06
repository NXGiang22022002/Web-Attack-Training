<?php
session_start();

$DB_HOST = "127.0.0.1";
$DB_NAME = "challege";  
$DB_USER = "root";
$DB_PASS = "";           

$error = "";
$success = "";

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit("Không kết nối được DB. Kiểm tra DB_NAME/USER/PASS.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["tendangnhap"] ?? "");
    $password = $_POST["matkhau"] ?? "";

    if ($username === "" || $password === "") {
        $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
    } else {
        $stmt = $pdo->prepare("SELECT tendangnhap, hoten, email, sodienthoai, isteacher, matkhau 
                               FROM `user` 
                               WHERE tendangnhap = ? 
                               LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && hash_equals((string)$user["matkhau"], (string)$password)) {
            $_SESSION["user"] = [
                "tendangnhap" => $user["tendangnhap"],
                "hoten" => $user["hoten"],
                "email" => $user["email"],
                "sodienthoai" => $user["sodienthoai"],
                "isteacher" => (int)$user["isteacher"],
            ];

            if ((int)$user["isteacher"] === 1) {
            header("Location: /CHALLEGE1/TEACHER/home_teacher.php");
            exit;
            } else {
            header("Location: /CHALLEGE1/STUDENT/home_student.php");
            exit;
            }

        } else {
            $error = "Sai tên đăng nhập hoặc mật khẩu.";
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <style>
    :root{
      --bg1:#0f172a; --bg2:#111827; --card:#0b1220cc;
      --text:#e5e7eb; --muted:#94a3b8; --accent:#22c55e; --accent2:#60a5fa;
      --shadow: 0 20px 60px rgba(0,0,0,.45); --radius: 18px;
    }
    *{ box-sizing:border-box; }
    body{
      margin:0; min-height:100vh; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
      color:var(--text);
      background:
        radial-gradient(900px 500px at 15% 20%, rgba(96,165,250,.35), transparent 60%),
        radial-gradient(900px 500px at 85% 80%, rgba(34,197,94,.30), transparent 60%),
        linear-gradient(180deg, var(--bg1), var(--bg2));
      display:flex; align-items:center; justify-content:center; padding:24px;
    }
    .wrap{ width:100%; max-width: 520px; }
    .card{
      border:1px solid rgba(255,255,255,.10);
      border-radius: var(--radius);
      background: var(--card);
      backdrop-filter: blur(10px);
      box-shadow: var(--shadow);
      padding: 22px;
    }
    h2{ margin:0 0 6px; font-size:22px; }
    .sub{ margin:0 0 16px; color:var(--muted); font-size:13.5px; line-height:1.5; }
    .alert{
      border-radius: 14px; padding: 12px 14px; margin: 12px 0 16px;
      border: 1px solid rgba(255,255,255,.10); font-size: 14px; line-height:1.5;
    }
    .alert.error{ background: rgba(239,68,68,.12); border-color: rgba(239,68,68,.25); color: #fecaca; }
    .alert.ok{ background: rgba(34,197,94,.10); border-color: rgba(34,197,94,.22); color: #bbf7d0; }
    form{ display:grid; gap: 12px; }
    label{ font-size: 13px; color: var(--muted); display:block; margin-bottom: 6px; }
    .field{
      border:1px solid rgba(255,255,255,.10);
      border-radius: 14px;
      background: rgba(17,24,39,.55);
      padding: 10px 12px;
      display:flex; align-items:center; gap:10px;
    }
    .field input{
      width:100%; border:0; outline:none; background:transparent;
      color: var(--text); font-size: 15px; padding: 6px 0;
    }
    .btn{
      margin-top: 6px; border:0; border-radius: 14px;
      padding: 12px 14px; font-weight: 700; font-size: 15px; cursor:pointer;
      color: #04120a;
      background: linear-gradient(90deg, var(--accent), #86efac);
      box-shadow: 0 10px 25px rgba(34,197,94,.20);
    }
    .footer{ margin-top: 12px; color: rgba(148,163,184,.8); font-size: 12.5px; line-height:1.6; }
  </style>
</head>
<body>
  <div class="wrap">
    <section class="card">
      <h2>Đăng Nhập</h2>

      <?php if ($error): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert ok"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="post" autocomplete="on">
        <div>
          <label for="tendangnhap">Tên đăng nhập</label>
          <div class="field">
            <span>👤</span>
            <input id="tendangnhap" name="tendangnhap" placeholder="vd: giangnx" required />
          </div>
        </div>

        <div>
          <label for="matkhau">Mật khẩu</label>
          <div class="field">
            <span>🔒</span>
            <input id="matkhau" name="matkhau" type="password" placeholder="••••••••" required />
          </div>
        </div>

        <button class="btn" type="submit">Đăng nhập</button>
      </form>

      <?php if (isset($_SESSION["user"])): ?>
      <?php else: ?>
        
      <?php endif; ?>
    </section>
  </div>
</body>
</html>