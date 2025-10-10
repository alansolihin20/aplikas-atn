<?php

use App\Http\Controllers\AttendanceController as ControllersAttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\Pembukuan\CategoryController;
use App\Http\Controllers\Pembukuan\PemasukanController;
use App\Http\Controllers\Pembukuan\PengeluaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Inventory\BarangInController;
use App\Http\Controllers\Inventory\BarangOutController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\OpnameController;
use App\Http\Controllers\Inventory\SuppliersController;
use App\Http\Controllers\Karyawan\KaryawanController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Shift\AttendanceController;
use App\Http\Controllers\Absensi\AbsensiController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


// use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});


// Route::middleware('auth')->group(function () {
//     Route::get('/dashboard/admin', function() {
//         return view('dashboard.admin');
//     });
//     // Route::get('/dashboard/teknisi', function() {
//     //     return view('dashboard.teknisi');
//     // });
//     Route::get('/dashboard/superadmin', [DashboardController::class, 'superadminDashboard'])->name('dashboard.superadmin');
//     Route::get('/dashboard/teknisi', [TeknisiController::class, 'teknisiDashboard'])->name('dashboard.teknisi');
//     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
//     Route::get('/dashboard', [DashboardRedirectController::class, 'redirect'])->name('dashboard.redirect');
// });


// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');
// });

// Dashboard Teknisi
Route::middleware(['auth', 'role:teknisi'])->group(function () {
    Route::get('/dashboard/teknisi', [TeknisiController::class, 'teknisiDashboard'])->name('dashboard.teknisi');
});

// Dashboard Superadmin
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/dashboard/superadmin', [DashboardController::class, 'superadminDashboard'])->name('dashboard.superadmin');
});

// Logout & Redirect (umum untuk semua role)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardRedirectController::class, 'redirect'])->name('dashboard.redirect');
});


Route::middleware('auth')->group(function () {
    
    Route::get('superadmin/pembukuan/buku-besar', [BukuBesarController::class, 'index'])->name('bukubesar.index');

    Route::prefix('superadmin/pembukuan/category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('superadmin/pembukuan')->group(function () {
        Route::get('pemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index');
        Route::post('pemasukan', [PemasukanController::class, 'store'])->name('pemasukan.store');
        Route::delete('pemasukan/{id}', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');
        Route::put('pemasukan/{id}', [PemasukanController::class, 'update'])->name('pemasukan.update');
    });

    Route::prefix('superadmin/pembukuan')->group(function () {
        Route::get('pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
        Route::post('pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
        Route::delete('pengeluaran/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
        Route::put('pengeluaran/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
    });

    Route::prefix('karyawan')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::get('/create', [KaryawanController::class, 'create'])->name('create');
        Route::post('/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::put('/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    });

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    });

    Route::prefix('inventori')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('inventori.index');
    });

    Route::prefix('barang-in')->group(function () {
        Route::get('/', [BarangInController::class, 'index'])->name('barang-in.index');
        Route::post('/store', [BarangInController::class, 'store'])->name('barang-in.store');
    });

    Route::prefix('barang-out')->group(function () {
        Route::get('/', [BarangOutController::class, 'index'])->name('barang-out.index');
    });

    Route::prefix('supplier')->group(function () {
        Route::get('/', [SuppliersController::class, 'index'])->name('supplier.index');
    });

    Route::prefix('opname')->group(function () {
        Route::get('/', [OpnameController::class, 'index'])->name('opname.index');
    });


    Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check.in');
    Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check.out');

    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/masuk', [AbsensiController::class, 'absenMasuk'])->name('absensi.masuk');
    Route::post('/absensi/pulang', [AbsensiController::class, 'absenPulang'])->name('absensi.pulang');



    Route::get('/absensi', [AttendanceController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/masuk', [AttendanceController::class, 'checkIn'])->name('absensi.masuk');
    Route::post('/absensi/pulang', [AttendanceController::class, 'checkOut'])->name('absensi.pulang');

    Route::post('/absensi/check-in', [AttendanceController::class, 'checkIn'])->name('absensi.checkIn');
    Route::post('/absensi/check-out', [AttendanceController::class, 'checkOut'])->name('absensi.checkOut');
    Route::post('/absensi/confirm-photo', [AttendanceController::class, 'confirmCheckInPhoto'])->name('absensi.confirmPhoto');

});



