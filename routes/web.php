<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardRedirectController;

// Dashboard
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeknisiController;

// Pembukuan (Superadmin)
use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\Pembukuan\CategoryController;
use App\Http\Controllers\Pembukuan\PemasukanController;
use App\Http\Controllers\Pembukuan\PengeluaranController;

// Karyawan & User
use App\Http\Controllers\Karyawan\KaryawanController;
use App\Http\Controllers\User\UserController;

// Absensi
use App\Http\Controllers\Shift\AttendanceController;
use App\Http\Controllers\Absensi\ControlAdminController;
use App\Http\Controllers\adminControl\ShiftScheduleController;
use App\Http\Controllers\adminControl\ShiftTime;
use App\Http\Controllers\adminControl\RiwayatController;
use App\Http\Controllers\teknisiControl\WeeklyTeknisi;

// Slip Gaji
use App\Http\Controllers\SlipGaji\SlipGajiController;
use App\Http\Controllers\teknisiControl\MySallaryController;

// Mikrotik
use App\Http\Controllers\mikrotik\MikrotikConnectionController;
use App\Http\Controllers\mikrotik\PppoeController;

// Inventory
use App\Http\Controllers\inventory\SupplierController;
use App\Http\Controllers\inventory\ItemController;
use App\Http\Controllers\inventory\ItemRequestController;
use App\Http\Controllers\inventory\ItemEntryController;
use App\Http\Controllers\inventory\ItemOutController;
/*
|--------------------------------------------------------------------------
| Redirect root (/) langsung ke login
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));


/*
|--------------------------------------------------------------------------
| Guest Routes (Login/Register)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::get('register', [RegisterController::class, 'show'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});


/*
|--------------------------------------------------------------------------
| Logout + Dashboard Redirect (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardRedirectController::class, 'redirect'])->name('dashboard.redirect');
});


/*
|--------------------------------------------------------------------------
| Dashboard per Role
|--------------------------------------------------------------------------
*/
// Teknisi
Route::middleware(['auth', 'role:teknisi'])->group(function () {
    Route::get('dashboard/teknisi', [TeknisiController::class, 'teknisiDashboard'])
        ->name('dashboard.teknisi');
});

// Superadmin
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('dashboard/superadmin', [DashboardController::class, 'superadminDashboard'])
        ->name('dashboard.superadmin');
});


/*
|--------------------------------------------------------------------------
| Pembukuan (Superadmin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Buku Besar
    Route::get('superadmin/pembukuan/buku-besar', [BukuBesarController::class, 'index'])
        ->name('bukubesar.index');

    // Category
    Route::prefix('superadmin/pembukuan/category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::put('{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Pemasukan
    Route::prefix('superadmin/pembukuan')->group(function () {
        Route::get('pemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index');
        Route::post('pemasukan', [PemasukanController::class, 'store'])->name('pemasukan.store');
        Route::put('pemasukan/{id}', [PemasukanController::class, 'update'])->name('pemasukan.update');
        Route::delete('pemasukan/{id}', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');
    });

    // Pengeluaran
    Route::prefix('superadmin/pembukuan')->group(function () {
        Route::get('pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
        Route::post('pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
        Route::put('pengeluaran/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
        Route::delete('pengeluaran/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
    });

});


/*
|--------------------------------------------------------------------------
| Karyawan & User Management (Superadmin)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('karyawan')->group(function () {
    Route::get('/', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::post('/store', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::post('/update/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
});

Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});


/*
|--------------------------------------------------------------------------
| Absensi + Shift
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Absensi Teknisi
    Route::get('absensi', [AttendanceController::class, 'index'])->name('absensi.index');
    Route::post('absensi/check-in', [AttendanceController::class, 'checkIn'])->name('absensi.checkIn');
    Route::post('absensi/check-out', [AttendanceController::class, 'checkOut'])->name('absensi.checkOut');
    Route::post('absensi/confirm-checkin-photo', [AttendanceController::class, 'confirmCheckInPhoto'])
    ->name('absensi.confirmCheckInPhoto');

    Route::post('absensi/confirm-checkout-photo', [AttendanceController::class, 'confirmCheckOutPhoto'])
        ->name('absensi.confirmCheckOutPhoto');


    // Admin Absensi
    Route::get('admin/absensi', [ControlAdminController::class, 'index'])->name('admin.absensi.index');
    Route::post('admin/absensi/store-schedule', [ControlAdminController::class, 'storeSchedule'])
        ->name('admin.absensi.storeSchedule');

    // Shift Schedule
    Route::prefix('admin/shift-schedules')->group(function () {
        Route::get('/', [ShiftScheduleController::class, 'index'])->name('admin.shift_schedules.index');
        Route::post('/store', [ShiftScheduleController::class, 'store'])->name('admin.shift_schedules.store');
        Route::post('/auto-generate', [ShiftScheduleController::class, 'autoGenerate'])
            ->name('admin.shift_schedules.auto_generate');
        Route::get('/weekly', [ShiftScheduleController::class, 'weekly'])->name('shift.weekly');
    });

    // Shift Time
    Route::get('admin/shift-times', [ShiftTime::class, 'index'])->name('admin.shift_times.index');
    Route::post('admin/shift-times/store', [ShiftTime::class, 'store'])->name('admin.shift_times.store');

    // Riwayat
    Route::get('admin/riwayat', [RiwayatController::class, 'index'])->name('admin.riwayat.index');

    // Weekly teknisi
    Route::get('teknisi/weekly-schedule', [WeeklyTeknisi::class, 'index'])->name('teknisi.weekly_schedule');

    // Update shift
    Route::post('admin/shift/update', [ShiftScheduleController::class, 'updateShift'])->name('shift.update');
});


/*
|--------------------------------------------------------------------------
| Slip Gaji (Admin & Teknisi)
|--------------------------------------------------------------------------
*/
// Admin
Route::middleware('auth')->group(function () {
    Route::get('slip-gaji', [SlipGajiController::class, 'index'])->name('admin.slipgaji.index');
    Route::post('slip-gaji', [SlipGajiController::class, 'store'])->name('admin.slipgaji.store');
});

// Teknisi
Route::middleware('auth')->group(function () {
    Route::get('slip-saya', [MySallaryController::class, 'mySalary'])->name('teknisi.slip');
    Route::get('slip-saya/{id}/pdf', [MySallaryController::class, 'downloadPdf'])->name('teknisi.slip.pdf');
    Route::post('slip/terima/{id}', [MySallaryController::class, 'terimaGaji'])->name('teknisi.slip.terima');
});


/*
|--------------------------------------------------------------------------
| Mikrotik
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::resource('admin/mikrotik', MikrotikConnectionController::class);

    Route::post('mikrotik/test-connection', [MikrotikConnectionController::class, 'testConnection'])
        ->name('mikrotik.test');

    Route::get('mikrotik/sync-pppoe', [MikrotikConnectionController::class, 'syncPppoe'])
        ->name('mikrotik.syncPppoe');

    // PPPoE
    Route::get('pppoe', [PppoeController::class, 'index'])->name('pppoe.index');
    Route::post('pppoe', [PppoeController::class, 'store'])->name('pppoe.store');
    Route::put('pppoe/{pppoe}', [PppoeController::class, 'update'])->name('pppoe.update');
    Route::delete('pppoe/{pppoe}', [PppoeController::class, 'destroy'])->name('pppoe.destroy');
    Route::post('pppoe/sync', [PppoeController::class, 'syncFromMikrotik'])->name('pppoe.sync');
});


/*
|--------------------------------------------------------------------------
| Inventory Management
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Supplier
    Route::resource('/suppliers', SupplierController::class);

    // Master barang
    Route::resource('/items', ItemController::class);

    // Request barang
    Route::resource('/item-requests', ItemRequestController::class);
    Route::post('/item-requests/{id}/approve', [ItemRequestController::class, 'approve'])->name('item-requests.approve');
    Route::post('/item-requests/{id}/reject', [ItemRequestController::class, 'reject'])->name('item-requests.reject');
    Route::post('/item-requests/{id}/order', [ItemRequestController::class, 'order'])->name('item-requests.order');

    // Barang masuk
    Route::get('stock-in', [StockInController::class, 'index'])->name('stockin.index');
    Route::get('stock-in/create', [StockInController::class, 'create'])->name('stockin.create');
    Route::post('stock-in', [StockInController::class, 'store'])->name('stockin.store');

    // Barang Keluar
    Route::get('stock-out', [StockOutController::class, 'index'])->name('stockout.index');
    Route::get('stock-out/create', [StockOutController::class, 'create'])->name('stockout.create');
    Route::post('stock-out', [StockOutController::class, 'store'])->name('stockout.store');

});

