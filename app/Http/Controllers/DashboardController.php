<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Total Pendapatan Hari Ini
        $todayIncome = Transaction::whereDate('created_at', $today)->sum('grand_total');

        // Total Transaksi Hari Ini
        $todayTransactions = Transaction::whereDate('created_at', $today)->count();

        // Total Produk Registered
        $totalProducts = Product::count();

        // Peringatan Produk Stok Menipis (<= 5)
        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        // 5 Transaksi Terakhir
        $recentTransactions = Transaction::latest()->take(5)->get();

        return view('dashboard', compact(
            'todayIncome',
            'todayTransactions',
            'totalProducts',
            'lowStockProducts',
            'recentTransactions'
        ));
    }
}