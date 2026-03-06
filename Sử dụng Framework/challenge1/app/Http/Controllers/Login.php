<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Login extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function handle(Request $request)
    {
        $data = $request->validate([
            'tendangnhap' => ['required', 'string'],
            'matkhau'     => ['required', 'string'],
        ]);

        $user = User::where('tendangnhap', $data['tendangnhap'])->first();

        if (!$user) {
            return back()
                ->withErrors(['tendangnhap' => 'Sai tên đăng nhập hoặc mật khẩu.'])
                ->onlyInput('tendangnhap');
        }

        $inputPass = (string) $data['matkhau'];
        $dbPass    = (string) ($user->matkhau ?? '');

        $ok = false;

        $looksHashed = Str::startsWith($dbPass, ['$2y$', '$2a$', '$2b$', '$argon2i$', '$argon2id$']);

        if ($looksHashed) {
            $ok = Hash::check($inputPass, $dbPass);
            if ($ok && Hash::needsRehash($dbPass)) {
                $user->matkhau = Hash::make($inputPass);
                $user->save();
            }
        } else {
            $ok = hash_equals($dbPass, $inputPass);
            if ($ok) {
                $user->matkhau = Hash::make($inputPass);
                $user->save();
            }
        }

        if (!$ok) {
            return back()
                ->withErrors(['tendangnhap' => 'Sai tên đăng nhập hoặc mật khẩu.'])
                ->onlyInput('tendangnhap');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return ((int) $user->isteacher === 1)
            ? redirect()->route('teacher.home')
            : redirect()->route('student.home');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}