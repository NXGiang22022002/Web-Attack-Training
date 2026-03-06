<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teacher Challenges</title>
  <style>
    body { margin: 0; font-family: Arial, sans-serif; background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff; min-height: 100vh; }
    .wrap { max-width: 900px; margin: 0 auto; padding: 24px; }
    .top-actions { display: flex; gap: 12px; margin-bottom: 20px; }
    .top-actions a { padding: 10px 18px; background: #fff; color: #2a5298; text-decoration: none; border-radius: 8px; font-weight: 700; }
    .card { background: rgba(255,255,255,0.12); border-radius: 12px; padding: 18px; margin-bottom: 16px; backdrop-filter: blur(8px); }
    .row { margin-bottom: 12px; }
    label { display: block; margin-bottom: 6px; font-weight: 700; }
    textarea, input[type='file'] { width: 100%; box-sizing: border-box; border-radius: 8px; border: 1px solid #d7dceb; padding: 10px; }
    button { padding: 10px 16px; border: 0; border-radius: 8px; background: #fff; color: #2a5298; font-weight: 700; cursor: pointer; }
    .msg-ok { color: #c8ffd3; font-weight: 700; }
    .msg-err { color: #ffd1d1; font-weight: 700; }
    ul { margin: 0; padding-left: 18px; }
    .top-actions-btn:hover {
    background: #dfd337;
  }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="top-actions">
      <a class="top-actions-btn" href="{{ route('teacher.home') }}">Home</a>
    </div>

    <div class="card">
      <h2>Tạo challenge</h2>
      <p>Upload file .txt. Đáp án sẽ là tên file viết thường không dấu (không tính .txt).</p>

      @if(session('success')) <p class="msg-ok">{{ session('success') }}</p> @endif
      @if($errors->any()) <p class="msg-err">{{ $errors->first() }}</p> @endif

      <form method="POST" action="{{ route('teacher.challenges.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <label for="hint">Gợi ý</label>
          <textarea id="hint" name="hint" required>{{ old('hint') }}</textarea>
        </div>
        <div class="row">
          <label for="file">File .txt</label>
          <input id="file" type="file" name="file" accept=".txt,text/plain" required>
        </div>
        <button class="top-actions-btn" type="submit">Tạo challenge</button>
      </form>
    </div>

    <div class="card">
      <h3>Challenge đã tạo</h3>
      @if($challenges->isEmpty())
        <p>Chưa có challenge nào.</p>
      @else
        <ul>
          @foreach($challenges as $c)
            <li>
              <b>#{{ $c->id }}</b> - {{ $c->hint }}
              <br>
              @if(!empty($challengeMeta[$c->id]))
                file: {{ $challengeMeta[$c->id]['file_name'] }}
              @else
                file: (chưa có file hoặc file không hợp lệ)
              @endif
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>
</body>
</html>
