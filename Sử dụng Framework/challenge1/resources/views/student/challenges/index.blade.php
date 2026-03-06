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
      <a class="top-actions-btn" href="{{ route('student.home') }}">Home</a>
    </div>

    <h2>Challenge Giải Đố</h2>

    @if($challenges->isEmpty())
      <div class="card">Chưa có challenge nào.</div>
    @else
      @foreach($challenges as $c)
        <div class="card">
          <div class="row"><b>Challenge #{{ $c->id }}</b></div>
          <div class="row"><b>Gợi ý:</b> {{ $c->hint }}</div>
          @if(empty($challengeMeta[$c->id]))
            <p class="msg-err">Challenge này chưa có file hợp lệ.</p>
          @endif

          <form method="POST" action="{{ route('student.challenges.answer', $c->id) }}">
            @csrf
            <div class="row">
              <label for="answer_{{ $c->id }}">Nhập đáp án (đáp án viết thường không dấu)</label>
              <input id="answer_{{ $c->id }}" type="text" name="answer" required>
            </div>
            <button type="submit">Kiểm tra đáp án</button>
          </form>

          @if(session('challenge_error_id') == $c->id)
            <p class="msg-err">{{ session('challenge_error') }}</p>
          @endif

          @if(session('challenge_success_id') == $c->id)
            <p class="msg-ok">{{ session('challenge_success') }}</p>
            <pre>{{ session('challenge_content') }}</pre>
          @endif
        </div>
      @endforeach
    @endif
  </div>
</body>
</html>
