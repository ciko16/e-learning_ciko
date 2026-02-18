<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // menampilkan semua matkul
    public function index() {
        return Course::with('lecturer:id,name')->get();
    }

    public function store(Request $request) {
        if (Auth::user()->role !== 'dosen') {
            return response()->json(['message' => 'Hanya dosen yang bisa menambahkan data kuliah!'], 403);
        }

        $request->validate(['name' => 'required|string']);

        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'lecturer_id' => Auth::id(),
        ]);

        return response()->json($course, 201);
    }

    public function update(Request $request, $id) {
        $course = Course::findOrFail($id);

        if (Auth::id() !== $course->lecturer_id) {
            return response()->json(['message' => 'Anda bukan pemilik mata kuliah ini.'], 403);
        }

        $course->update($request->only(['name', 'description']));
        return response()->json($course);
    }

    public function destroy($id) {
        $course = Course::findOrFail($id);

        if (Auth::id() !== $course->lecturer_id) {
            return response()->json(['nessage' => 'Unauthorized'], 403);
        }

        $course->delete();
        return response()->json(['message' => 'mata kuliah dihapus']);
    }

    public function enroll($id) {
        /** @var \App\Models\User $user */ // variabel user dengan tipe User
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Hanya mahasiswa yang bisa mendaftar'], 403);
        }

        $user->matkulMahasiswa()->syncWithoutDetaching([$id]);
        return response()->json(['message' => 'Berhasil mendaftar ke mata kuliah']);
    }
}
