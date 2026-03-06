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
    <a href="{{ route('users.index') }}" class="btn-back">
        ← Quay lại danh sách
    </a>
</div>

@if(session('success'))
  <div class="msg">{{ session('success') }}</div>
@endif

@if($errors->any())
  <div class="err">{{ $errors->first() }}</div>
@endif

<div class="card">
  <h2>👤 {{ $user->hoten }}</h2>
  <p><b>Username:</b> {{ $user->tendangnhap }}</p>
  <p><b>Email:</b> {{ $user->email }}</p>
  <p><b>SĐT:</b> {{ $user->sodienthoai }}</p>
  <p><b>Vai trò:</b> {{ (int)$user->isteacher === 1 ? 'Teacher' : 'Student' }}</p>
</div>

{{-- Form gửi tin nhắn --}}
@if(auth()->id() !== $user->id)
  <div class="card">
    <h3>✉️ Gửi tin nhắn</h3>
    <form method="POST" action="{{ route('messages.store', $user->id) }}">
      @csrf
      <textarea name="content" rows="4" placeholder="Nhập tin nhắn...">{{ old('content') }}</textarea>
      <div style="margin-top:10px;">
        <button class="btn btn-w" type="submit">Gửi</button>
      </div>
    </form>
  </div>
@endif

{{-- Danh sách tin nhắn tôi đã gửi cho user này: sửa/xóa --}}
<div class="card">
  <h3>📝 Tin nhắn bạn đã gửi cho người này</h3>

  @forelse($myMessagesToThisUser as $m)
    <div style="border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:12px;margin:10px 0;">
      <div style="opacity:.8;font-size:13px;margin-bottom:6px;">
        Gửi lúc: {{ $m->created_at }}
      </div>

      <form method="POST" action="{{ route('messages.update', $m->id) }}">
        @csrf
        <textarea name="content" rows="3">{{ $m->content }}</textarea>

        <div class="row" style="margin-top:8px;">
          <button class="btn btn-w" type="submit">Sửa</button>
      </form>

          <form method="POST" action="{{ route('messages.destroy', $m->id) }}"
                onsubmit="return confirm('Xóa tin nhắn này?');">
            @csrf
            <button class="btn btn-danger" type="submit">Xóa</button>
          </form>
        </div>
    </div>
  @empty
    <p style="opacity:.85;">Chưa có tin nhắn nào.</p>
  @endforelse
</div>

</body>
</html>