<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeacherAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $assignments = Assignment::with('teacher')
            ->withCount('submissions')
            ->orderByDesc('id')
            ->get();

        return view('teacher.assignments.index', compact('assignments'));
    }

    public function create()
    {
        return view('teacher.assignments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file'        => ['required', 'file', 'max:10240'],
        ]);

        $file = $request->file('file');
        $storedName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('assignments', $storedName, 'public');

        Assignment::create([
            'teacher_id'         => $request->user()->id,
            'title'              => $data['title'],
            'description'        => $data['description'] ?? null,
            'file_original_name' => $file->getClientOriginalName(),
            'file_stored_name'   => $storedName,
            'created_at'         => now(),
        ]);

        return redirect()->route('teacher.assignments.index')->with('success', 'Da tao bai tap.');
    }

    public function submissions(Assignment $assignment, Request $request)
    {
        if (!$this->canViewAssignment($assignment, $request)) {
            abort(403);
        }

        $submissions = Submission::with('student')
            ->where('assignment_id', $assignment->id)
            ->orderByDesc('id')
            ->get();

        return view('teacher.assignments.submissions', compact('assignment', 'submissions'));
    }

    public function downloadSubmission(Assignment $assignment, Submission $submission, Request $request)
    {
        if (!$this->canViewAssignment($assignment, $request)) {
            abort(403);
        }

        if ((int) $submission->assignment_id !== (int) $assignment->id) {
            abort(404);
        }

        $disk = Storage::disk('public');
        $candidatePaths = array_values(array_filter([
            $submission->file_path,
            !empty($submission->file_stored_name) ? 'submissions/' . ltrim($submission->file_stored_name, '/') : null,
            !empty($submission->file_stored_name) ? ltrim($submission->file_stored_name, '/') : null,
        ]));

        foreach ($candidatePaths as $path) {
            if ($disk->exists($path)) {
                return $disk->download($path, $submission->file_name ?? 'submission.file');
            }
        }

        abort(404, 'Khong tim thay file bai nop.');
    }

    private function canViewAssignment(Assignment $assignment, Request $request): bool
    {
        return (int) $request->user()->isteacher === 1;
    }
}
