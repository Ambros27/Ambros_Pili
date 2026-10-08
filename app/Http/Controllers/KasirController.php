<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        $orders = Order::whereIn('status', ['pending', 'processing'])
            ->latest()
            ->get();
        $categories = Category::withCount('products')->get();
        $activeCategoryId = request()->integer('category_id');
        $products = Product::with('categoryItem')->when(request('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->when($activeCategoryId > 0, function ($query) use ($activeCategoryId) {
            $query->where('category_id', $activeCategoryId);
        })->orderBy('name')->get();
        $cart = session('cart', []);

        return view('kasir.pos', compact('orders', 'products', 'categories', 'activeCategoryId', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $cart = session('cart', []);
        $productId = (string) $product->id;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('kasir.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function removeFromCart(string $productId)
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);

        return redirect()->route('kasir.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function processOrder(Order $order)
    {
        if (!in_array($order->status, ['pending', 'processing'], true)) {
            return redirect()->route('kasir.index')->withErrors(['cart' => 'Pesanan ini tidak dapat dibayar.']);
        }

        $product = Product::find($order->product_id);
        if (!$product) {
            return redirect()->route('kasir.index')->withErrors(['cart' => 'Produk pesanan sudah tidak tersedia.']);
        }

        session([
            'cart' => [
                (string) $product->id => [
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $order->quantity,
                ],
            ],
            'active_order_id' => $order->id,
        ]);
        $order->update(['status' => 'processing']);

        return redirect()->route('kasir.index')->with('success', 'Pesanan dimasukkan ke keranjang kasir. Lanjutkan pembayaran.');
    }

    // API / Endpoint untuk pencarian produk otomatis
    public function search(Request $request)
    {
        $query = $request->get('query');

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}