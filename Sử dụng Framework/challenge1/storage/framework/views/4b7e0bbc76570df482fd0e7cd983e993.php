<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Challenges</title>
  <style>
    body { margin: 0; font-family: Arial, sans-serif; background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff; min-height: 100vh; }
    .wrap { max-width: 900px; margin: 0 auto; padding: 24px; }
    .top-actions { display: flex; gap: 12px; margin-bottom: 20px; }
    .top-actions a { padding: 10px 18px; background: #fff; color: #2a5298; text-decoration: none; border-radius: 8px; font-weight: 700; }
    .card { background: rgba(255,255,255,0.12); border-radius: 12px; padding: 18px; margin-bottom: 16px; backdrop-filter: blur(8px); }
    .row { margin-bottom: 10px; }
    input[type='text'] { width: 100%; box-sizing: border-box; border-radius: 8px; border: 1px solid #d7dceb; padding: 10px; }
    button { padding: 10px 16px; border: 0; border-radius: 8px; background: #fff; color: #2a5298; font-weight: 700; cursor: pointer; }
    .msg-ok { color: #c8ffd3; font-weight: 700; }
    .msg-err { color: #ffd1d1; font-weight: 700; }
    pre { white-space: pre-wrap; background: rgba(0,0,0,0.25); border-radius: 8px; padding: 12px; }
    .top-actions-btn:hover {
    background: #dfd337;
  }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="top-actions">
      <a class="top-actions-btn" href="<?php echo e(route('student.home')); ?>">Home</a>
    </div>

    <h2>Challenge Giải Đố</h2>

    <?php if($challenges->isEmpty()): ?>
      <div class="card">Chưa có challenge nào.</div>
    <?php else: ?>
      <?php $__currentLoopData = $challenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card">
          <div class="row"><b>Challenge #<?php echo e($c->id); ?></b></div>
          <div class="row"><b>Gợi ý:</b> <?php echo e($c->hint); ?></div>
          <?php if(empty($challengeMeta[$c->id])): ?>
            <p class="msg-err">Challenge này chưa có file hợp lệ.</p>
          <?php endif; ?>

          <form method="POST" action="<?php echo e(route('student.challenges.answer', $c->id)); ?>">
            <?php echo csrf_field(); ?>
            <div class="row">
              <label for="answer_<?php echo e($c->id); ?>">Nhập đáp án (đáp án viết thường không dấu)</label>
              <input id="answer_<?php echo e($c->id); ?>" type="text" name="answer" required>
            </div>
            <button type="submit">Kiểm tra đáp án</button>
          </form>

          <?php if(session('challenge_error_id') == $c->id): ?>
            <p class="msg-err"><?php echo e(session('challenge_error')); ?></p>
          <?php endif; ?>

          <?php if(session('challenge_success_id') == $c->id): ?>
            <p class="msg-ok"><?php echo e(session('challenge_success')); ?></p>
            <pre><?php echo e(session('challenge_content')); ?></pre>
          <?php endif; ?>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
  </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\challenge1\resources\views/student/challenges/index.blade.php ENDPATH**/ ?>