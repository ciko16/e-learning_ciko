<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ControllerLaporan;
use Illuminate\Container\Attributes\Auth;

// rute publik
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// rute token sanctum
Route::middleware('auth:sanctum')->group(function () {
    // logout untuk mencabut token
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // rute crud
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll']);

    // rute materi
    Route::post('/materials', [MaterialController::class, 'store']);
    Route::get('/materials/{id}/download', [MaterialController::class, 'download']);

    // rute tugas
    Route::post('/assignments', [AssignmentController::class, 'dosenbuatTugas']);
    Route::post('/submissions', [AssignmentController::class, 'mahasiswakumpulTugas']);
    Route::post('/submissions/{id}/score', [AssignmentController::class, 'dosenberiNilai']);

    // rute diskusi
    Route::post('/discussions', [DiscussionController::class, 'openDiskusi']);
    Route::post('/discussions/{id}/replies', [DiscussionController::class, 'balasDiskusi']);

    // rute laporan dan statsitik
    Route::get('/reports/courses', [ControllerLaporan::class, 'jumlahMahasiswa']);
    Route::get('/reports/assignments', [ControllerLaporan::class, 'statTugas']);
    Route::get('/reports/students/{id}', [ControllerLaporan::class, 'statTertentu']);
});