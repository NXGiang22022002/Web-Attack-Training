<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Assignment Submissions</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      color: #fff;
      min-height: 100vh;
    }

    .wrap {
      max-width: 1100px;
      margin: 0 auto;
      padding: 24px;
    }

    .top-actions {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 18px;
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

    .card {
      background: rgba(255, 255, 255, 0.12);
      border-radius: 12px;
      padding: 16px;
      backdrop-filter: blur(8px);
    }

    .meta {
      margin: 8px 0 14px;
      font-size: 18px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: rgba(0, 0, 0, 0.18);
      border-radius: 10px;
      overflow: hidden;
    }

    th, td {
      border: 1px solid rgba(255, 255, 255, 0.25);
      padding: 10px;
      text-align: left;
    }

    th {
      background: rgba(255, 255, 255, 0.18);
      font-weight: 700;
    }

    .download-link {
      color: #fff;
      font-weight: 700;
      text-underline-offset: 3px;
    }
  </style>
</head>
<body>
  <div class="wrap">
    <h2>Danh sách bài nộp: {{ $assignment->title }}</h2>

    <div class="top-actions">
      <a href="{{ route('teacher.assignments.index') }}">Quản lý bài tập</a>
      <a href="{{ route('teacher.home') }}">Home</a>
    </div>

    <div class="card">
      @if($submissions->isEmpty())
        <p>Chưa có học sinh nào nộp bài.</p>
      @else
        <p class="meta">Tổng số bài nộp: <b>{{ $submissions->count() }}</b></p>

        <table>
          <thead>
            <tr>
              <th>Học sinh</th>
              <th>Tài khoản</th>
              <th>Tên file</th>
              <th>Thời gian nộp</th>
              <th>Tùy chọn</th>
            </tr>
          </thead>
          <tbody>
            @foreach($submissions as $s)
              <tr>
                <td>{{ $s->student->hoten ?? 'N/A' }}</td>
                <td>{{ $s->student->tendangnhap ?? 'N/A' }}</td>
                <td>{{ $s->file_name }}</td>
                <td>{{ $s->submitted_at ?? '-' }}</td>
                <td>
                  <a class="download-link" href="{{ route('teacher.assignments.submissions.download', ['assignment' => $assignment->id, 'submission' => $s->id]) }}">
                    Tải bài làm
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>
</body>
</html>
