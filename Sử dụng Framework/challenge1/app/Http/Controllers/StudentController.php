<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard(Request $request)
    {
        return view('student.dashboard', [
            'user' => $request->user(),
        ]);
    }
}