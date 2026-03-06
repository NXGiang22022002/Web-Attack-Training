<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <title>Sửa sinh viên</title>
  <style>
    body{font-family:Arial;margin:0;padding:24px;background:#0b1220;color:#e5e7eb}
    .card{max-width:560px;background:#111827;border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:16px}
    label{display:block;margin:10px 0 6px}
    input{width:100%;padding:10px;border-radius:10px;border:1px solid rgba(255,255,255,.12);background:#0b1220;color:#e5e7eb}
    .btn{padding:10px 12px;border-radius:10px;border:0;font-weight:700}
    .btn-primary{background:#22c55e;color:#04120a}
    .btn-w{background:#fff;color:#111827}
    .err{padding:10px 12px;border-radius:10px;background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);margin-bottom:12px}
    .muted{opacity:.85;font-size:13px;margin-top:6px}
  </style>
</head>
<body>

<h2>✏️ Sửa sinh viên #{{ $student->id }}</h2>

@if($errors->any())
  <div class="err">{{ $errors->first() }}</div>
@endif

<div class="card">
  <form method="POST" action="{{ route('teacher.students.update', $student->id) }}">
    @csrf

    <label>Tên đăng nhập</label>
    <input name="tendangnhap" value="{{ old('tendangnhap', $student->tendangnhap) }}" required>

    <label>Mật khẩu mới (để trống nếu không đổi)</label>
    <input type="password" name="matkhau">
    <div class="muted">Không nhập gì thì giữ mật khẩu cũ.</div>

    <label>Họ tên</label>
    <input name="hoten" value="{{ old('hoten', $student->hoten) }}" required>

    <label>Email</label>
    <input name="email" value="{{ old('email', $student->email) }}">

    <label>Số điện thoại</label>
    <input name="sodienthoai" value="{{ old('sodienthoai', $student->sodienthoai) }}">

    <div style="margin-top:14px;display:flex;gap:10px;">
      <button class="btn btn-primary" type="submit">Cập nhật</button>
      <a class="btn btn-w" href="{{ route('teacher.students.index') }}">Quay lại</a>
    </div>
  </form>
</div>

</body>
</html>