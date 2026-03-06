<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <title>Quản lý sinh viên</title>
  <style>
    body{font-family:Arial;margin:0;padding:24px;background:#0b1220;color:#e5e7eb}
    a,button{cursor:pointer}
    .top{display:flex;gap:12px;align-items:center;justify-content:space-between;margin-bottom:16px}
    .card{background:#111827;border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:16px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid rgba(255,255,255,.08);text-align:left}
    .btn{padding:8px 12px;border-radius:10px;border:0;font-weight:700}
    .btn-primary{background:#22c55e;color:#04120a}
    .btn-w{background:#fff;color:#111827}
    .btn-danger{background:#ef4444;color:#fff}
    .msg{padding:10px 12px;border-radius:10px;margin-bottom:12px;background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.3)}
    input{padding:8px 10px;border-radius:10px;border:1px solid rgba(255,255,255,.12);background:#0b1220;color:#e5e7eb}
    .actions{display:flex;gap:8px;align-items:center}
  </style>
</head>
<body>

<div class="top">
  <h2>👩‍🏫 Quản lý sinh viên</h2>

  <div class="actions">
    <a class="btn btn-primary" href="{{ route('teacher.students.create') }}">+ Thêm sinh viên</a>
    <a class="btn btn-w" href="{{ route('teacher.home') }}">← Home</a>
  </div>
</div>

@if(session('success'))
  <div class="msg">{{ session('success') }}</div>
@endif

<div class="card">
  <table>
    <thead>
      <tr>
        <th>Tên đăng nhập</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>SĐT</th>
        <th style="width:180px">Thao tác</th>
      </tr>
    </thead>
    <tbody>
    @foreach($students as $s)
      <tr>
        <td>{{ $s->tendangnhap }}</td>
        <td>{{ $s->hoten }}</td>
        <td>{{ $s->email }}</td>
        <td>{{ $s->sodienthoai }}</td>
        <td>
          <div class="actions">
            <a class="btn btn-w" href="{{ route('teacher.students.edit', $s->id) }}">Sửa</a>

            <form method="POST" action="{{ route('teacher.students.destroy', $s->id) }}"
                  onsubmit="return confirm('Xóa sinh viên này?');">
              @csrf
              <button class="btn btn-danger" type="submit">Xóa</button>
            </form>
          </div>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <div style="margin-top:12px;">
    {{ $students->links() }}
  </div>
</div>

</body>
</html>