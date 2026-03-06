<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $assignments = Assignment::orderByDesc('id')->get();

        $my = Submission::where('student_id', $request->user()->id)->get()->keyBy('assignment_id');

        return view('student.assignments.index', compact('assignments', 'my'));
    }

    public function download(Assignment $assignment)
    {
        $disk = Storage::disk('public');

        $candidatePaths = array_values(array_filter([
            $assignment->file_path,
            !empty($assignment->file_stored_name) ? 'assignments/' . ltrim($assignment->file_stored_name, '/') : null,
            !empty($assignment->file_stored_name) ? ltrim($assignment->file_stored_name, '/') : null,
        ]));

        foreach ($candidatePaths as $path) {
            if ($disk->exists($path)) {
                return $disk->download($path, $assignment->file_name ?? 'download.file');
            }
        }

        $storedName = basename((string) ($assignment->file_stored_name ?? ''));
        $legacyCandidates = array_values(array_filter([
            $storedName !== '' ? base_path('../Challege1/uploads/assignments/' . $storedName) : null,
            $storedName !== '' ? public_path('uploads/assignments/' . $storedName) : null,
        ]));

        foreach ($legacyCandidates as $legacyPath) {
            if (is_file($legacyPath)) {
                return response()->download($legacyPath, $assignment->file_name ?? $storedName);
            }
        }

        abort(404, 'Khong tim thay file tren server');
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $request->file('file');
        $storedName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('submissions', $storedName, 'public');

        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => $request->user()->id,
            ],
            [
                'file_original_name' => $file->getClientOriginalName(),
                'file_stored_name' => $storedName,
                'submitted_at' => now(),
            ]
        );

        return back()->with('success', 'Da nop bai.');
    }
}
