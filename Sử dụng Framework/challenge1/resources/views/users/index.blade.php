<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <title>Danh sách người dùng</title>
  <style>
    body{font-family:Arial;margin:0;padding:24px;background:#0b1220;color:#e5e7eb}
    .card{background:#111827;border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:16px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid rgba(255,255,255,.08);text-align:left}
    input{padding:8px 10px;border-radius:10px;border:1px solid rgba(255,255,255,.12);background:#0b1220;color:#e5e7eb}
    .btn{padding:8px 12px;border-radius:10px;border:0;font-weight:700;background:#fff;color:#111827;cursor:pointer}
    a{color:#93c5fd;text-decoration:none}
    .btn:hover{
    background:#ffd700;
    color:#111827;
}
  </style>
</head>
<body>

<h2>👥 Danh sách người dùng</h2>
<div style="margin:14px 0;">
    <a class="btn"
       href="{{ auth()->user()->isteacher == 1 ? route('teacher.home') : route('student.home') }}">
        ← Home
    </a>
</div>

<div class="card">
  <table>
    <thead>
      <tr>
        <th>Tên đăng nhập</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Vai trò</th>
        <th>Chi tiết</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $u)
        <tr>
          <td>{{ $u->tendangnhap }}</td>
          <td>{{ $u->hoten }}</td>
          <td>{{ $u->email }}</td>
          <td>{{ (int)$u->isteacher === 1 ? 'Teacher' : 'Student' }}</td>
          <td><a href="{{ route('users.show', $u->id) }}">Xem</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div style="margin-top:12px;">
    {{ $users->links() }}
  </div>
</div>

</body>
</html>