<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherChallengeController extends Controller
{
    public function index(Request $request)
    {
        $challenges = Challenge::where('teacher_id', $request->user()->id)
            ->orderByDesc('id')
            ->get();

        $showingSynced = false;
        if ($challenges->isEmpty()) {
            $challenges = Challenge::orderByDesc('id')->get();
            $showingSynced = $challenges->isNotEmpty();
        }

        $challengeMeta = [];
        foreach ($challenges as $challenge) {
            $challengeMeta[$challenge->id] = $this->resolveChallengeFileMeta($challenge->id);
        }

        return view('teacher.challenges.index', compact('challenges', 'challengeMeta', 'showingSynced'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hint' => ['required', 'string', 'max:2000'],
            'file' => ['required', 'file', 'mimes:txt', 'max:10240'],
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = strtolower((string) $file->getClientOriginalExtension());
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);

        if ($extension !== 'txt') {
            return back()->withErrors(['file' => 'Chi nhan file .txt'])->withInput();
        }

        if (!preg_match('/^[A-Za-z0-9]+(?: [A-Za-z0-9]+)*$/', $baseName)) {
            return back()->withErrors([
                'file' => 'Ten file phai khong dau, chi gom chu/so, va cach nhau dung 1 khoang trang.',
            ])->withInput();
        }

        $challenge = Challenge::create([
            'teacher_id' => $request->user()->id,
            'hint' => $data['hint'],
            'created_at' => now(),
        ]);

        $storedName = $challenge->id . '__' . $baseName . '.txt';
        $file->storeAs('challenges', $storedName, 'public');

        return redirect()->route('teacher.challenges')->with('success', 'Da tao challenge.');
    }

    private function resolveChallengeFileMeta(int $challengeId): ?array
    {
        $files = Storage::disk('public')->files('challenges');
        $prefix = $challengeId . '__';

        foreach ($files as $filePath) {
            $name = basename($filePath);
            if (!str_starts_with($name, $prefix) || !str_ends_with(strtolower($name), '.txt')) {
                continue;
            }

            $baseName = substr($name, strlen($prefix), -4);

            return [
                'path' => $filePath,
                'answer' => $baseName,
                'file_name' => $baseName . '.txt',
            ];
        }

        return null;
    }
}
