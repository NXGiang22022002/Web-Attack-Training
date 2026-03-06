<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <title>Thêm sinh viên</title>
  <style>
    body{font-family:Arial;margin:0;padding:24px;background:#0b1220;color:#e5e7eb}
    .card{max-width:560px;background:#111827;border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:16px}
    label{display:block;margin:10px 0 6px}
    input{width:100%;padding:10px;border-radius:10px;border:1px solid rgba(255,255,255,.12);background:#0b1220;color:#e5e7eb}
    .btn{padding:10px 12px;border-radius:10px;border:0;font-weight:700}
    .btn-primary{background:#22c55e;color:#04120a}
    .btn-w{background:#fff;color:#111827}
    .err{padding:10px 12px;border-radius:10px;background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);margin-bottom:12px}
  </style>
</head>
<body>

<h2>➕ Thêm sinh viên</h2>

<?php if($errors->any()): ?>
  <div class="err"><?php echo e($errors->first()); ?></div>
<?php endif; ?>

<div class="card">
  <form method="POST" action="<?php echo e(route('teacher.students.store')); ?>">
    <?php echo csrf_field(); ?>

    <label>Tên đăng nhập</label>
    <input name="tendangnhap" value="<?php echo e(old('tendangnhap')); ?>" required>

    <label>Mật khẩu</label>
    <input type="password" name="matkhau" required>

    <label>Họ tên</label>
    <input name="hoten" value="<?php echo e(old('hoten')); ?>" required>

    <label>Email</label>
    <input name="email" value="<?php echo e(old('email')); ?>">

    <label>Số điện thoại</label>
    <input name="sodienthoai" value="<?php echo e(old('sodienthoai')); ?>">

    <div style="margin-top:14px;display:flex;gap:10px;">
      <button class="btn btn-primary" type="submit">Lưu</button>
      <a class="btn btn-w" href="<?php echo e(route('teacher.students.index')); ?>">Hủy</a>
    </div>
  </form>
</div>

</body>
</html><?php /**PATH C:\xampp\htdocs\challenge1\resources\views/teacher/students/create.blade.php ENDPATH**/ ?>