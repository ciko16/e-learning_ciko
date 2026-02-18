<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    // Dosen membuat tugas baru (POST)
    public function dosenbuatTugas(Request $request) {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string',
            'description' => 'required',
            'deadline' => 'required|date',
        ]);

        $assignment = Assignment::create($request->all());
        return response()->json($assignment, 201);
    }

    // Mahasiswa mengumpulkan tugas (POST)
    public function mahasiswakumpulTugas(Request $request) {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:2048',
        ]);

        $path = $request->file('file')->store('submissions', 'public');

        $submission = Submission::create([
            'assignment_id' => $request->assignment_id,
            'student_id' => Auth::id(),
            'file_path' => $path,
        ]);

        return response()->json($submission, 201);
    }

    // Dosen memberikan nilai tugas (POST)
    public function dosenberiNilai(Request $request) {
        $request->validate(['score' => 'required|integer|min:0|max:100']);

        $submission = Submission::findOrFail($request->submission_id);
        $submission->update(['score' => $request->score]);

        return response()->json(['message' => 'Nilai berhasil disubmit', 'data' => $submission]);
    }
}