<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Developer\DashboardController as DeveloperDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\KeranjangController;
use App\Http\Controllers\User\PesananController as UserPesananController;
use App\Http\Controllers\User\ProdukController as UserProdukController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('produk', ProdukController::class);
    Route::get('pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
    Route::get('pesanan/{pesanan}', [AdminPesananController::class, 'show'])->name('pesanan.show');
    Route::put('pesanan/{pesanan}/status', [AdminPesananController::class, 'updateStatus'])->name('pesanan.update-status');
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

Route::middleware(['auth', 'role:developer'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/', [DeveloperDashboardController::class, 'index'])->name('dashboard');
    Route::get('/monitoring', [DeveloperDashboardController::class, 'monitoring'])->name('monitoring');
    
    // Kelola Versi
    Route::get('/versi', [DeveloperDashboardController::class, 'versiIndex'])->name('versi.index');
    Route::post('/versi', [DeveloperDashboardController::class, 'versiStore'])->name('versi.store');
    Route::delete('/versi/{id}', [DeveloperDashboardController::class, 'versiDestroy'])->name('versi.destroy');
    
    // Bug Report
    Route::get('/bug', [DeveloperDashboardController::class, 'bugIndex'])->name('bug.index');
    Route::post('/bug', [DeveloperDashboardController::class, 'bugStore'])->name('bug.store');
    Route::put('/bug/{id}/resolve', [DeveloperDashboardController::class, 'bugResolve'])->name('bug.resolve');
    Route::delete('/bug/{id}', [DeveloperDashboardController::class, 'bugDestroy'])->name('bug.destroy');
    
    // Log Aktivitas
    Route::get('/log', [DeveloperDashboardController::class, 'logIndex'])->name('log.index');
    
    // Kelola Pengguna (Role)
    Route::get('/users', [DeveloperDashboardController::class, 'usersIndex'])->name('users.index');
    Route::put('/users/{id}/role', [DeveloperDashboardController::class, 'usersUpdateRole'])->name('users.update-role');
});

Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('home');
    Route::get('/produk', [UserProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/{produk}', [UserProdukController::class, 'show'])->name('produk.show');
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah/{produk}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::put('/keranjang/{id_produk}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{id_produk}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'proses'])->name('checkout.proses');
    Route::get('/pesanan', [UserPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{pesanan}', [UserPesananController::class, 'show'])->name('pesanan.show');
});
