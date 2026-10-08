<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    // Menampilkan halaman Riwayat Transaksi
    public function index()
    {
        $query = Transaction::query()
            ->when(request('search'), function ($query, $search) {
                $query->where('invoice_number', 'like', "%{$search}%");
            })
            ->when(request('date_from'), function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when(request('date_to'), function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            });

        $transactionCount = (clone $query)->count();
        $revenue = (clone $query)->sum('total_amount');
        $transactions = $query->latest()->paginate(10)->withQueryString();

        return view('transactions.history', compact('transactions', 'transactionCount', 'revenue'));
    }

    public function printReceipt(Transaction $transaction)
    {
        $transaction->load('details.product');

        return view('transactions.print', compact('transaction'));
    }

    // Menyimpan Transaksi dari Kasir
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Tunai,QRIS,Transfer,Debit',
        ]);

        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('kasir.index')->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        $activeOrderId = $request->session()->get('active_order_id');
        if ($activeOrderId && !Order::whereKey($activeOrderId)->whereIn('status', ['pending', 'processing'])->exists()) {
            return redirect()->route('kasir.index')->withErrors(['cart' => 'Pesanan ini sudah tidak menunggu pembayaran.']);
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $lineItems = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = $products->get((int) $productId);
            $quantity = (int) ($item['quantity'] ?? 0);

            if (!$product || $quantity < 1) {
                return redirect()->route('kasir.index')->withErrors(['cart' => 'Keranjang berisi produk yang tidak valid.']);
            }

            $subtotal = (float) $product->price * $quantity;
            $lineItems[] = compact('product', 'quantity', 'subtotal');
            $total += $subtotal;
        }

        if ((float) $validated['payment'] < $total) {
            return redirect()->route('kasir.index')
                ->withErrors(['payment' => 'Uang pembayaran kurang dari total belanja.'])
                ->withInput();
        }

        $transaction = DB::transaction(function () use ($validated, $lineItems, $total, $activeOrderId) {
            $transaction = Transaction::create([
            'order_id' => $activeOrderId,
                'invoice_number' => 'TRX-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                'total_amount' => $total,
                'pay_amount' => $validated['payment'],
                'change_amount' => $validated['payment'] - $total,
                'payment_method' => $validated['payment_method'],
            ]);

            foreach ($lineItems as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['product']->price,
                    'subtotal' => $item['subtotal'],
                ]);
            }

            if ($activeOrderId) {
                Order::whereKey($activeOrderId)->update(['status' => 'paid']);
            }

            return $transaction;
        });

        $request->session()->forget('cart');
        $request->session()->forget('active_order_id');

        return redirect()->route('transactions.print', $transaction)
            ->with('success', 'Pembayaran berhasil diproses.');
    }
} 