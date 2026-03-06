<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherStudentController extends Controller
{
    
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $students = User::query()
            ->where('isteacher', 0)
            ->when($q !== '', function ($query) use ($q) {
                $query->where('tendangnhap', 'like', "%{$q}%")
                      ->orWhere('hoten', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('teacher.students.index', compact('students', 'q'));
    }

    // Form tạo mới
    public function create()
    {
        return view('teacher.students.create');
    }

    // Lưu sinh viên mới
    public function store(Request $request)
    {
        $data = $request->validate([
            'tendangnhap' => ['required', 'string', 'max:50', 'unique:user,tendangnhap'],
            'matkhau'     => ['required', 'string', 'min:6', 'max:255'],
            'hoten'       => ['required', 'string', 'max:255'],
            'email'       => ['nullable', 'email', 'max:255'],
            'sodienthoai' => ['nullable', 'string', 'max:30'],
        ]);

        $student = new User();
        $student->tendangnhap = $data['tendangnhap'];
        $student->matkhau     = Hash::make($data['matkhau']); 
        $student->hoten       = $data['hoten'];
        $student->email       = $data['email'] ?? null;
        $student->sodienthoai = $data['sodienthoai'] ?? null;
        $student->isteacher   = 0;
        $student->save();

        return redirect()->route('teacher.students.index')->with('success', 'Đã thêm sinh viên.');
    }

    // Form sửa
    public function edit($id)
    {
        $student = User::where('isteacher', 0)->findOrFail($id);
        return view('teacher.students.edit', compact('student'));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $student = User::where('isteacher', 0)->findOrFail($id);

        $data = $request->validate([
            'tendangnhap' => ['required', 'string', 'max:50', "unique:user,tendangnhap,{$student->id}"],
            'matkhau'     => ['nullable', 'string', 'min:6', 'max:255'], 
            'hoten'       => ['required', 'string', 'max:255'],
            'email'       => ['nullable', 'email', 'max:255'],
            'sodienthoai' => ['nullable', 'string', 'max:30'],
        ]);

        $student->tendangnhap = $data['tendangnhap'];
        $student->hoten       = $data['hoten'];
        $student->email       = $data['email'] ?? null;
        $student->sodienthoai = $data['sodienthoai'] ?? null;

        if (!empty($data['matkhau'])) {
            $student->matkhau = Hash::make($data['matkhau']);
        }

        $student->save();

        return redirect()->route('teacher.students.index')->with('success', 'Đã cập nhật sinh viên.');
    }

    // Xóa
    public function destroy($id)
    {
        $student = User::where('isteacher', 0)->findOrFail($id);
        $student->delete();

        return redirect()->route('teacher.students.index')->with('success', 'Đã xóa sinh viên.');
    }
}