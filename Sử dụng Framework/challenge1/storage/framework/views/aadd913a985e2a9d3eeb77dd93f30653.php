<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <title>Thông tin người dùng</title>
  <style>
    body{font-family:Arial;margin:0;padding:24px;background:#0b1220;color:#e5e7eb}
    .card{background:#111827;border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:16px;margin-bottom:12px}
    .msg{padding:10px 12px;border-radius:10px;margin-bottom:12px;background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.3)}
    .err{padding:10px 12px;border-radius:10px;margin-bottom:12px;background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3)}
    textarea,input{width:100%;padding:10px;border-radius:10px;border:1px solid rgba(255,255,255,.12);background:#0b1220;color:#e5e7eb}
    .btn{padding:8px 12px;border-radius:10px;border:0;font-weight:700;cursor:pointer}
    .btn-w{background:#fff;color:#111827}
    .btn-danger{background:#ef4444;color:#fff}
    a{color:#93c5fd;text-decoration:none}
    .row{display:flex;gap:10px;align-items:center}
    .btn-back{
    display:inline-block;
    padding:10px 18px;
    border-radius:12px;
    background:linear-gradient(135deg,#60a5fa,#3b82f6);
    color:#fff;
    font-weight:600;
    text-decoration:none;
    transition:all .25s ease;
    box-shadow:0 4px 14px rgba(0,0,0,.25);
    }
    .btn-back:hover{
    background:#ffd700;
    color:#111827;
    }
  </style>
</head>
<body>

<div style="margin-bottom:18px;">
    <a href="<?php echo e(route('users.index')); ?>" class="btn-back">
        ← Quay lại danh sách
    </a>
</div>

<?php if(session('success')): ?>
  <div class="msg"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if($errors->any()): ?>
  <div class="err"><?php echo e($errors->first()); ?></div>
<?php endif; ?>

<div class="card">
  <h2>👤 <?php echo e($user->hoten); ?></h2>
  <p><b>Username:</b> <?php echo e($user->tendangnhap); ?></p>
  <p><b>Email:</b> <?php echo e($user->email); ?></p>
  <p><b>SĐT:</b> <?php echo e($user->sodienthoai); ?></p>
  <p><b>Vai trò:</b> <?php echo e((int)$user->isteacher === 1 ? 'Teacher' : 'Student'); ?></p>
</div>


<?php if(auth()->id() !== $user->id): ?>
  <div class="card">
    <h3>✉️ Gửi tin nhắn</h3>
    <form method="POST" action="<?php echo e(route('messages.store', $user->id)); ?>">
      <?php echo csrf_field(); ?>
      <textarea name="content" rows="4" placeholder="Nhập tin nhắn..."><?php echo e(old('content')); ?></textarea>
      <div style="margin-top:10px;">
        <button class="btn btn-w" type="submit">Gửi</button>
      </div>
    </form>
  </div>
<?php endif; ?>


<div class="card">
  <h3>📝 Tin nhắn bạn đã gửi cho người này</h3>

  <?php $__empty_1 = true; $__currentLoopData = $myMessagesToThisUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div style="border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:12px;margin:10px 0;">
      <div style="opacity:.8;font-size:13px;margin-bottom:6px;">
        Gửi lúc: <?php echo e($m->created_at); ?>

      </div>

      <form method="POST" action="<?php echo e(route('messages.update', $m->id)); ?>">
        <?php echo csrf_field(); ?>
        <textarea name="content" rows="3"><?php echo e($m->content); ?></textarea>

        <div class="row" style="margin-top:8px;">
          <button class="btn btn-w" type="submit">Sửa</button>
      </form>

          <form method="POST" action="<?php echo e(route('messages.destroy', $m->id)); ?>"
                onsubmit="return confirm('Xóa tin nhắn này?');">
            <?php echo csrf_field(); ?>
            <button class="btn btn-danger" type="submit">Xóa</button>
          </form>
        </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <p style="opacity:.85;">Chưa có tin nhắn nào.</p>
  <?php endif; ?>
</div>

</body>
</html><?php /**PATH C:\xampp\htdocs\challenge1\resources\views/users/show.blade.php ENDPATH**/ ?>