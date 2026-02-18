<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\User;
use App\Models\Submission;

class ControllerLaporan extends Controller
{
    // jumlah mahasiswa per matkul
    public function jumlahMahasiswa() {
        // withCount untuk menghitung jumlah mahasiswa per matkul
        $jumlah = Course::withCount('matkulMahasiswa')->get();
        return response()->json($jumlah);
    }

    // statsitik tugas yang sudah dinilai/belum
    public function statTugas() {
        $totalTugas = Submission::count();
        $tugasDinilai = Submission::whereNotNull('score')->count();
        $tugasBelumDinilai = Submission::whereNull('score')->count();

        return response()->json([
            'total_tugas' => $totalTugas,
            'tugas_dinilai' => $tugasDinilai,
            'tugas_belum_dinilai' => $tugasBelumDinilai,
        ]);
    }

    //  statistik tugas dan nilai mahasiswa tertentu
    public function statTertentu($id) {
        $user = User::findOrFail($id);

        // menghitung jumlah tugas yang dikumpulkan mahasiswa sesuai id
        $totalTugas = Submission::where('student_id', $id)->count();

        // menghitung rata2 nilai
        $avgNilai = Submission::where('student_id', $id)->avg('score');
        
        return response()->json([
            'nama_mahasiswa' => $user->name,
            'total_tugas' => $totalTugas,
            'rata_rata_nilai' => round($avgNilai, 2)
        ]);
    }
}