<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\BimbinganProgressController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\UjianDokumenController;
use Illuminate\Support\Facades\Route;

// Redirect root
Route::get('/', function () { return redirect('/login'); });

// ====================== AUTH ======================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Notifications (all roles)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markRead'])->name('markRead');
        Route::post('/read-all', [NotificationController::class, 'markAllRead'])->name('markAllRead');
        Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
    });

    // ====================== MAHASISWA ======================
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'mahasiswa'])->name('dashboard');
        Route::get('/export-pdf', [DashboardController::class, 'exportPDF'])->name('export-pdf');

        // Bimbingan
        Route::prefix('bimbingan')->name('bimbingan.')->group(function () {
            Route::get('/', [BimbinganController::class, 'index'])->name('index');
            Route::get('/create', [BimbinganController::class, 'create'])->name('create');
            Route::get('/riwayat/all', [BimbinganController::class, 'riwayat'])->name('riwayat');
            Route::get('/riwayat/export', [BimbinganController::class, 'exportRiwayat'])->name('export-riwayat');
            Route::post('/', [BimbinganController::class, 'store'])->name('store');
            Route::get('/{bimbingan}', [BimbinganController::class, 'show'])->name('show');

            // Progress
            Route::get('/{bimbingan}/progress/create', [BimbinganProgressController::class, 'create'])->name('progress.create');
            Route::post('/{bimbingan}/progress', [BimbinganProgressController::class, 'store'])->name('progress.store');
            Route::get('/progress/{progress}/edit', [BimbinganProgressController::class, 'edit'])->name('progress.edit');
            Route::put('/progress/{progress}', [BimbinganProgressController::class, 'update'])->name('progress.update');
        });

        // Ujian
        Route::prefix('ujian')->name('ujian.')->group(function () {
            Route::get('/', [UjianController::class, 'index'])->name('index');
            Route::get('/create', [UjianController::class, 'create'])->name('create');
            Route::post('/', [UjianController::class, 'store'])->name('store');
            Route::get('/{ujian}', [UjianController::class, 'show'])->name('show');
            Route::get('/{ujian}/edit', [UjianController::class, 'edit'])->name('edit');
            Route::put('/{ujian}', [UjianController::class, 'update'])->name('update');
            Route::get('/riwayat/all', [UjianController::class, 'riwayat'])->name('riwayat');

            // Dokumen Upload
            Route::get('/{ujian}/dokumen/create', [UjianDokumenController::class, 'create'])->name('dokumen.create');
            Route::post('/{ujian}/dokumen', [UjianDokumenController::class, 'store'])->name('dokumen.store');
        });
    });

    // ====================== DOSEN ======================
    Route::middleware('role:dosen,kaprodi')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dosen'])->name('dashboard');

        // Bimbingan
        Route::prefix('bimbingan')->name('bimbingan.')->group(function () {
            Route::get('/', [BimbinganController::class, 'dosenIndex'])->name('index');
            Route::get('/{bimbingan}', [BimbinganController::class, 'dosenShow'])->name('show');
            Route::post('/{bimbingan}/approve', [BimbinganController::class, 'approve'])->name('approve');
            Route::post('/{bimbingan}/selesai', [BimbinganController::class, 'markSelesai'])->name('selesai');
            Route::post('/{bimbingan}/tidak-selesai', [BimbinganController::class, 'markNotSelesai'])->name('tidak-selesai');
        });

        // Progress approval
        Route::post('/progress/{progress}/approve', [BimbinganProgressController::class, 'approve'])->name('progress.approve');

        // Ujian
        Route::prefix('ujian')->name('ujian.')->group(function () {
            Route::get('/', [UjianController::class, 'dosenIndex'])->name('index');
            Route::get('/{ujian}', [UjianController::class, 'dosenShow'])->name('show');
            Route::post('/{ujian}/approve', [UjianController::class, 'dosenApprove'])->name('approve');
        });
    });

    // ====================== KAPRODI ======================
    Route::middleware('role:kaprodi')->prefix('kaprodi')->name('kaprodi.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'kaprodi'])->name('dashboard');

        // Manage Mahasiswa
        Route::get('/mahasiswa', [KaprodiController::class, 'mahasiswaList'])->name('mahasiswa.index');
        Route::get('/mahasiswa/{mahasiswa}', [KaprodiController::class, 'mahasiswaDetail'])->name('mahasiswa.detail');

        // Bimbingan oversight
        Route::prefix('bimbingan')->name('bimbingan.')->group(function () {
            Route::get('/', [KaprodiController::class, 'bimbinganList'])->name('index');
            Route::get('/{bimbingan}', [KaprodiController::class, 'bimbinganShow'])->name('show');
            Route::post('/{bimbingan}/feedback', [KaprodiController::class, 'bimbinganFeedback'])->name('feedback');
        });

        // Ujian approval
        Route::prefix('ujian')->name('ujian.')->group(function () {
            Route::get('/', [KaprodiController::class, 'ujianList'])->name('index');
            Route::get('/{ujian}', [KaprodiController::class, 'ujianShow'])->name('show');
            Route::post('/{ujian}/approve', [KaprodiController::class, 'ujianApprove'])->name('approve');
        });
    });

    // ====================== ADMIN ======================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // User management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminController::class, 'userIndex'])->name('index');
            Route::get('/create', [AdminController::class, 'userCreate'])->name('create');
            Route::post('/', [AdminController::class, 'userStore'])->name('store');
            Route::get('/{user}/edit', [AdminController::class, 'userEdit'])->name('edit');
            Route::put('/{user}', [AdminController::class, 'userUpdate'])->name('update');
            Route::delete('/{user}', [AdminController::class, 'userDestroy'])->name('destroy');
            Route::post('/{user}/toggle-active', [AdminController::class, 'userToggleActive'])->name('toggle-active');
        });

        // Monitoring - View Only (No Approval)
        Route::get('/mahasiswa', [AdminController::class, 'mahasiswaList'])->name('mahasiswa.index');
        Route::get('/mahasiswa/{mahasiswa}', [AdminController::class, 'mahasiswaDetail'])->name('mahasiswa.detail');

        Route::prefix('bimbingan')->name('bimbingan.')->group(function () {
            Route::get('/', [AdminController::class, 'bimbinganList'])->name('index');
            Route::get('/{bimbingan}', [AdminController::class, 'bimbinganShow'])->name('show');
        });

        Route::prefix('ujian')->name('ujian.')->group(function () {
            Route::get('/', [AdminController::class, 'ujianList'])->name('index');
            Route::get('/{ujian}', [AdminController::class, 'ujianShow'])->name('show');
        });
    });
});
