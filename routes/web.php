<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AkdController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, "login"])->name('login');
Route::post('/login', [AuthController::class, "loginPost"])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('/admin')->name('admin.')->group(function () {
    Route::resource('akd', AkdController::class);
    Route::resource('users', UserController::class);

    Route::resource('jadwal', JadwalController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('laporan-jadwal', [JadwalController::class, 'laporan'])->name('admin.jadwal.laporan');
    Route::get('admin/dashboard', [DashboardController::class, "index"])->name('admin.dashboard');

});

Route::middleware(['auth', 'role:admin,bamus'])->prefix('/petugas')->name('petugas.')->group(function () {

    Route::get('jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('jadwal/{id}', [JadwalController::class, 'show'])->name('jadwal.show');


    Route::post('jadwal/approve/{id}', [JadwalController::class, 'approve'])
        ->middleware('role:bamus')
        ->name('jadwal.approve');
});
