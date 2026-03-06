<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, $id)
    {
        $receiver = User::findOrFail($id);

        // không cho tự nhắn cho chính mình (optional)
        if ($receiver->id === $request->user()->id) {
            return back()->withErrors(['content' => 'Bạn không thể nhắn tin cho chính mình.']);
        }

        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $receiver->id,
            'content' => $data['content'],
        ]);

        return back()->with('success', 'Đã gửi tin nhắn.');
    }

    public function update(Request $request, Message $message)
    {
        // chỉ người gửi mới được sửa
        if ((int) $message->sender_id !== (int) $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $message->content = $data['content'];
        $message->save();

        return back()->with('success', 'Đã cập nhật tin nhắn.');
    }

    public function destroy(Request $request, Message $message)
    {
        // chỉ người gửi mới được xóa
        if ((int) $message->sender_id !== (int) $request->user()->id) {
            abort(403);
        }

        $message->delete();

        return back()->with('success', 'Đã xóa tin nhắn.');
    }
}