<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Assignments</title>
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

    .msg-ok { color: #c8ffd3; font-weight: 700; }
    .msg-err { color: #ffd1d1; font-weight: 700; }

    .list {
      display: grid;
      gap: 14px;
      margin-top: 10px;
    }

    .card {
      background: rgba(255, 255, 255, 0.12);
      border-radius: 12px;
      padding: 16px;
      backdrop-filter: blur(8px);
    }

    .title {
      font-size: 22px;
      margin: 0 0 6px;
    }

    .desc {
      margin: 0 0 10px;
      opacity: 0.95;
    }

    .download-link {
      color: #fff;
      font-weight: 700;
      text-underline-offset: 3px;
    }

    .status {
      margin: 10px 0;
    }

    .status .ok { color: #9df8b3; font-weight: 700; }
    .status .wait { color: #ffd87a; font-weight: 700; }

    .submit-form {
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
    }

    .submit-form input[type="file"] {
      background: #fff;
      color: #1e3c72;
      border-radius: 8px;
      border: 0;
      padding: 8px;
    }

    .submit-form button {
      padding: 9px 16px;
      border: 0;
      border-radius: 8px;
      background: #fff;
      color: #2a5298;
      font-weight: 700;
      cursor: pointer;
    }

    .submit-form button:hover {
      background: #ffd700;
      color: #1e3c72;
    }
  </style>
</head>
<body>
  <div class="wrap">
    <h2>Danh sách bài tập</h2>

    <div class="top-actions">
      <a href="{{ route('student.home') }}">Home</a>
    </div>

    @if(session('success')) <p class="msg-ok">{{ session('success') }}</p> @endif
    @if($errors->any()) <p class="msg-err">{{ $errors->first() }}</p> @endif

    <div class="list">
      @foreach($assignments as $a)
        <div class="card">
          <h3 class="title">{{ $a->title }}</h3>
          <p class="desc">{{ $a->description }}</p>

          <p>
            <a class="download-link" href="{{ route('student.assignments.download', $a->id) }}">Tải file bài tập</a>
          </p>

          <p class="status">
            Trạng thái:
            @if(isset($my[$a->id]))
              <span class="ok">Đã nộp: {{ $my[$a->id]->file_name }}</span>
            @else
              <span class="wait">Chưa nộp</span>
            @endif
          </p>

          <form class="submit-form" method="POST" action="{{ route('student.assignments.submit', $a->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" required>
            <button type="submit">Nộp bài</button>
          </form>
        </div>
      @endforeach
    </div>
  </div>
</body>
</html>
