<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teacher Assignments</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      color: #fff;
      min-height: 100vh;
    }

    .wrap {
      max-width: 1000px;
      margin: 0 auto;
      padding: 24px;
    }

    .top-actions {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }

    .top-actions a {
      padding: 10px 18px;
      background: #fff;
      color: #2a5298;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 700;
      transition: 0.2s;
    }

    .top-actions a:hover {
      background: #ffd700;
      color: #1e3c72;
    }

    .msg-ok {
      color: #c8ffd3;
      font-weight: 700;
    }

    .list {
      display: grid;
      gap: 14px;
    }

    .card {
      background: rgba(255, 255, 255, 0.12);
      border-radius: 12px;
      padding: 16px;
      backdrop-filter: blur(8px);
    }

    .title {
      margin: 0 0 6px;
      font-size: 22px;
    }

    .meta {
      margin: 4px 0;
      opacity: 0.95;
    }

    .submission-link {
      display: inline-block;
      margin-top: 8px;
      color: #fff;
      font-weight: 700;
      text-underline-offset: 3px;
    }
  </style>
</head>
<body>
  <div class="wrap">
    <h2>Quản lý bài tập</h2>

    <?php if(session('success')): ?> <p class="msg-ok"><?php echo e(session('success')); ?></p> <?php endif; ?>

    <div class="top-actions">
      <a href="<?php echo e(route('teacher.home')); ?>">Home</a>
      <a href="<?php echo e(route('teacher.assignments.create')); ?>">+ Thêm bài tập</a>
    </div>

    <?php if($assignments->isEmpty()): ?>
      <div class="card">Chưa có bài tập nào.</div>
    <?php else: ?>
      <div class="list">
        <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="card">
            <h3 class="title"><?php echo e($a->title); ?></h3>
            <p class="meta"><?php echo e($a->description); ?></p>
            <p class="meta">Giáo viên: <b><?php echo e($a->teacher->hoten ?? $a->teacher->tendangnhap ?? 'N/A'); ?></b></p>
            <p class="meta">Số lượng bài đã nộp: <b><?php echo e($a->submissions_count); ?></b></p>
            <a class="submission-link" href="<?php echo e(route('teacher.assignments.submissions', $a->id)); ?>">Xem bài nộp</a>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\challenge1\resources\views/teacher/assignments/index.blade.php ENDPATH**/ ?>