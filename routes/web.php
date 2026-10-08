<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StrukController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Tambahkan di dalam group middleware auth atau di luar (sesuai kebutuhan)
Route::get('/struk', [StrukController::class, 'struk'])->name('struk.cetak');

// Halaman utama
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Route Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route khusus user yang sudah login
Route::middleware('auth')->group(function () {

    // === PROFILE MANAGEMENT ===
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === KATALOG & PRODUCT MANAGEMENT ===
    Route::resource('products', ProductController::class);

    // === ROUTE PEMESANAN / CHECKOUT PRODUK ===
    Route::get('/products/{product}/checkout', [ProductController::class, 'checkout'])->name('products.checkout');
    Route::post('/products/{product}/order', [ProductController::class, 'storeOrder'])->name('products.order');

    // === ROUTE KASIR & TRANSAKSI ===
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/cart', [KasirController::class, 'addToCart'])->name('kasir.add');
    Route::delete('/kasir/cart/{productId}', [KasirController::class, 'removeFromCart'])->name('kasir.cart.remove');
    Route::patch('/kasir/orders/{order}/process', [KasirController::class, 'processOrder'])->name('kasir.orders.process');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('kasir.checkout');
    Route::get('/transactions/{transaction}/print', [TransactionController::class, 'printReceipt'])->name('transactions.print');
});

require __DIR__.'/auth.php';