<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('student.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user(); 

        $data = $request->validate([
            'email'       => ['nullable', 'email', 'max:255'],
            'sodienthoai'  => ['nullable', 'string', 'max:30'],
            'matkhau'      => ['nullable', 'string', 'min:6', 'max:255'],
        ]);

        $user->email = $data['email'] ?? $user->email;
        $user->sodienthoai = $data['sodienthoai'] ?? $user->sodienthoai;

        if (!empty($data['matkhau'])) {
            $user->matkhau = Hash::make($data['matkhau']);
        }

        $user->save();

        return back()->with('success', 'Cập nhật thông tin thành công.');
    }
}