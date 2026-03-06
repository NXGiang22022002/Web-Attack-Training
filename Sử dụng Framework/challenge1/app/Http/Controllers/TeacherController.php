<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function dashboard(Request $request)
    {
        return view('teacher.dashboard', [
            'user' => $request->user(),
        ]);
    }
}