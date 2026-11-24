<?php

use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));
Route::middleware('guest')->group(function () {
    Route::get('/login', [PortalController::class, 'loginForm'])->name('login');
    Route::post('/login', [PortalController::class, 'login'])->name('login.store');
});
Route::post('/logout', [PortalController::class, 'logout'])->middleware('role:admin_biasa,verifikator,petugas')->name('logout');
Route::get('/laporan/{laporan}/lampiran', [PortalController::class, 'attachment'])
    ->middleware('role:admin_biasa,verifikator,petugas')->name('reports.attachment');

Route::middleware('role:admin_biasa')->group(function () {
    Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/akun', [PortalController::class, 'accounts'])->name('accounts.index');
    Route::post('/akun', [PortalController::class, 'storeAccount'])->name('accounts.store');
    Route::patch('/akun/{admin}', [PortalController::class, 'updateAccount'])->name('accounts.update');
    Route::patch('/akun/{admin}/status', [PortalController::class, 'updateAccountStatus'])->name('accounts.status');
    Route::delete('/akun/{admin}', [PortalController::class, 'deleteAccount'])->name('accounts.destroy');
    Route::get('/petugas', [PortalController::class, 'staff'])->name('staff.index');
    Route::post('/petugas', [PortalController::class, 'storeStaff'])->name('staff.store');
    Route::delete('/petugas/{petugas}', [PortalController::class, 'deleteStaff'])->name('staff.destroy');
});

Route::middleware('role:admin_biasa,verifikator')->group(function () {
    Route::get('/laporan', [PortalController::class, 'reports'])->name('reports.index');
    Route::get('/data-laporan', [PortalController::class, 'reportArchive'])->name('reports.archive');
    Route::get('/data-laporan/{laporan}/ubah', [PortalController::class, 'editReport'])->name('reports.edit');
    Route::patch('/data-laporan/{laporan}', [PortalController::class, 'updateReport'])->name('reports.update');
    Route::delete('/data-laporan/{laporan}', [PortalController::class, 'deleteReport'])->name('reports.destroy');
    Route::patch('/laporan/{laporan}', [PortalController::class, 'review'])->name('reports.review');
    Route::patch('/laporan/{laporan}/tolak', [PortalController::class, 'rejectReport'])->name('reports.reject');
});

Route::middleware('role:petugas')->group(function () {
    Route::get('/karyawan/dashboard', [PortalController::class, 'employeeDashboard'])->name('employee.dashboard');
    Route::get('/karyawan/kegiatan-selesai', [PortalController::class, 'completedActivities'])->name('employee.completed');
    Route::get('/karyawan/kirim-laporan', [PortalController::class, 'reportForm'])->name('employee.report');
    Route::get('/karyawan/laporan/{laporan}/ubah', [PortalController::class, 'editOwnReport'])->name('employee.reports.edit');
    Route::patch('/karyawan/laporan/{laporan}', [PortalController::class, 'updateOwnReport'])->name('employee.reports.update');
    Route::delete('/karyawan/laporan/{laporan}', [PortalController::class, 'deleteOwnReport'])->name('employee.reports.destroy');
    Route::get('/kegiatan-saya', [PortalController::class, 'myActivities'])->name('my-activities');
    Route::post('/laporan', [PortalController::class, 'submitReport'])->name('reports.store');
});
