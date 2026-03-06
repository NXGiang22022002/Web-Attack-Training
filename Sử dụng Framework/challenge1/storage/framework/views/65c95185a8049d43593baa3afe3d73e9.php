<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tao Bai Tap</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      color: #fff;
      min-height: 100vh;
    }

    .navbar {
      display: flex;
      justify-content: center;
      gap: 12px;
      flex-wrap: wrap;
      padding: 20px;
      background: rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(10px);
    }

    .navbar a {
      padding: 10px 18px;
      background: #fff;
      color: #2a5298;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 700;
      transition: 0.2s;
    }

    .navbar a:hover {
      background: #ffd700;
      color: #1e3c72;
    }

    .container {
      display: flex;
      justify-content: center;
      padding: 40px 16px;
    }

    .card {
      width: 100%;
      max-width: 680px;
      background: rgba(255, 255, 255, 0.12);
      border-radius: 14px;
      padding: 28px;
      backdrop-filter: blur(10px);
      box-sizing: border-box;
    }

    h2 {
      margin-top: 0;
      margin-bottom: 20px;
    }

    .error {
      color: #ffb3b3;
      font-weight: 700;
      margin-bottom: 12px;
    }

    .row {
      margin-bottom: 14px;
    }

    .row label {
      display: block;
      font-weight: 700;
      margin-bottom: 6px;
    }

    input[type="text"],
    textarea,
    input[type="file"] {
      width: 100%;
      max-width: 100%;
      box-sizing: border-box;
      border: 1px solid #d9e0ee;
      border-radius: 8px;
      padding: 10px 12px;
      font-size: 15px;
      color: #1e3c72;
      background: #fff;
    }

    textarea {
      min-height: 120px;
      resize: vertical;
    }

    .submit-btn {
      padding: 10px 22px;
      background: #fff;
      color: #2a5298;
      border: 0;
      border-radius: 8px;
      font-weight: 700;
      cursor: pointer;
      transition: 0.2s;
    }

    .submit-btn:hover {
      background: #ffd700;
      color: #1e3c72;
    }
  </style>
</head>
<body>
  <div class="navbar">
    <a href="<?php echo e(route('teacher.home')); ?>">Home</a>
    <a href="<?php echo e(route('teacher.assignments.index')); ?>">Danh sách bài tập</a>
  </div>

  <div class="container">
    <div class="card">
      <h2>Thêm bài tập</h2>

      <?php if($errors->any()): ?>
        <div class="error"><?php echo e($errors->first()); ?></div>
      <?php endif; ?>

      <form method="POST" action="<?php echo e(route('teacher.assignments.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="row">
          <label for="title">Tiêu đề</label>
          <input id="title" type="text" name="title" value="<?php echo e(old('title')); ?>" required>
        </div>

        <div class="row">
          <label for="description">Mô tả</label>
          <textarea id="description" name="description"><?php echo e(old('description')); ?></textarea>
        </div>

        <div class="row">
          <label for="file">File bài tập</label>
          <input id="file" type="file" name="file" required>
        </div>

        <button class="submit-btn" type="submit">Thêm</button>
      </form>
    </div>
  </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\challenge1\resources\views/teacher/assignments/create.blade.php ENDPATH**/ ?>