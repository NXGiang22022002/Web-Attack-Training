<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentChallengeController extends Controller
{
    public function index()
    {
        $challenges = Challenge::orderByDesc('id')->get();
        $challengeMeta = [];

        foreach ($challenges as $challenge) {
            $challengeMeta[$challenge->id] = $this->resolveChallengeFileMeta($challenge->id);
        }

        return view('student.challenges.index', compact('challenges', 'challengeMeta'));
    }

    public function answer(Request $request, Challenge $challenge)
    {
        $data = $request->validate([
            'answer' => ['required', 'string', 'max:255'],
        ]);

        $meta = $this->resolveChallengeFileMeta((int) $challenge->id);
        if (!$meta) {
            return back()->with('challenge_error_id', $challenge->id)
                ->with('challenge_error', 'Challenge chua co file hop le.');
        }

        $expected = $this->normalize($meta['answer']);
        $actual = $this->normalize($data['answer']);

        if ($actual !== $expected) {
            return back()->with('challenge_error_id', $challenge->id)
                ->with('challenge_error', 'Sai đáp án, hãy thử lại.');
        }

        $path = $meta['path'];
        if (!Storage::disk('public')->exists($path)) {
            return back()->with('challenge_error_id', $challenge->id)
                ->with('challenge_error', 'Không tìm thấy nội dung challenge.');
        }

        $content = Storage::disk('public')->get($path);

        return back()
            ->with('challenge_success_id', $challenge->id)
            ->with('challenge_content', $content)
            ->with('challenge_success', 'Đáp án đúng! Đây là nội dung của đáp án');
    }

    private function normalize(string $value): string
    {
        $value = trim($value);
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return mb_strtolower($value);
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
