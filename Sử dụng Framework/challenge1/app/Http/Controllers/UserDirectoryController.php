<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class UserDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $users = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('tendangnhap', 'like', "%{$q}%")
                      ->orWhere('hoten', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users', 'q'));
    }

    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $me = $request->user();

        
        $myMessagesToThisUser = Message::query()
            ->where('sender_id', $me->id)
            ->where('receiver_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('users.show', compact('user', 'myMessagesToThisUser'));
    }
}