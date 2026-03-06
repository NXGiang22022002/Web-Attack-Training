<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Thông tin cá nhân</title>
  <style>
    body{margin:0;min-height:100vh;font-family:Arial,sans-serif;background:linear-gradient(135deg,#1e3c72,#2a5298);color:#fff;display:flex;align-items:center;justify-content:center;padding:24px;}
    .card{width:520px;background:rgba(255,255,255,0.10);backdrop-filter:blur(10px);border-radius:15px;padding:28px;}
    .row{margin:12px 0;}
    label{display:block;margin-bottom:6px;opacity:.9;}
    input{width:100%;padding:10px 12px;border-radius:10px;border:0;outline:none;}
    .muted{opacity:.85;font-size:13px;margin-top:6px;}
    .btn{margin-top:14px;padding:10px 14px;border:0;border-radius:10px;font-weight:700;cursor:pointer;}
    .alert{margin:12px 0;padding:10px 12px;border-radius:10px;}
    .ok{background:rgba(34,197,94,.18);border:1px solid rgba(34,197,94,.35);}
    .err{background:rgba(239,68,68,.18);border:1px solid rgba(239,68,68,.35);}
    a{color:#fff;}
  </style>
</head>
<body>
  <div class="card">
    <h2>Thông tin cá nhân</h2>

    <?php if(session('success')): ?>
      <div class="alert ok"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
      <div class="alert err"><?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    
    <div class="row">
      <label>Tên đăng nhập (không thể đổi)</label>
      <input value="<?php echo e($user->tendangnhap); ?>" disabled>
    </div>

    <div class="row">
      <label>Họ tên (không thể đổi)</label>
      <input value="<?php echo e($user->hoten); ?>" disabled>
    </div>

    <form method="POST" action="<?php echo e(route('student.profile.update')); ?>">
      <?php echo csrf_field(); ?>

      <div class="row">
        <label>Email</label>
        <input name="email" value="<?php echo e(old('email', $user->email)); ?>" placeholder="email@example.com">
      </div>

      <div class="row">
        <label>Số điện thoại</label>
        <input name="sodienthoai" value="<?php echo e(old('sodienthoai', $user->sodienthoai)); ?>" placeholder="0123456789">
      </div>

      <div class="row">
        <label>Mật khẩu mới (để trống nếu không đổi)</label>
        <input type="password" name="matkhau" placeholder="••••••••">
        <div class="muted">Nếu bạn nhập mật khẩu mới, hệ thống sẽ tự mã hoá và lưu.</div>
      </div>

      <button class="btn" type="submit">Lưu thay đổi</button>
    </form>

    <div class="muted" style="margin-top:14px;">
      <a href="<?php echo e(route('student.home')); ?>">← Quay về Dashboard</a>
    </div>
  </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\challenge1\resources\views/student/profile.blade.php ENDPATH**/ ?>