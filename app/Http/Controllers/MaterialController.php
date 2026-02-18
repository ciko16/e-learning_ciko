<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    // dosen upload materi
    public function store(Request $request) {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:2048',
        ]);

        $course = Course::findOrFail($request->course_id);

        // Pastikan hanya dosen pengampu yang bisa upload materi
        if (Auth::id() !== $course->lecturer_id) {
            return response()->json(['message' => 'Anda bukan dosen pengampu mata kuliah ini.'], 403);
        }

        $path = $request->file('file')->store('materials', 'public');

        $material = Material::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'file_path' => $path,
        ]);

        return response()->json($material, 201);
    }

    // mahasiswa download materi
    public function download($id) {
        
        $material = Material::findOrFail($id);

        // mengecek apakah file ada di penyimpanan
        if (!Storage::disk('public')->exists($material->file_path)) {
            return response()->json(['message' => 'File tidak ditemukan.'], 404);
        }
        return response()->download(storage_path('app/public/' . $material->file_path), $material->title);
    }
}