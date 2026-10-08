<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menampilkan daftar produk (Katalog)
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $productsQuery = Product::with('categoryItem');
        $activeCategoryId = request()->integer('category_id');

        if ($activeCategoryId > 0) {
            $productsQuery->where('category_id', $activeCategoryId);
        }

        $products = $productsQuery->get();

        return view('products.index', compact('products', 'categories', 'activeCategoryId'));
    }

    // Menampilkan form tambah produk
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|string',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        $validated['category'] = $category->name;
        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // Menampilkan halaman detail/checkout pesanan
    public function checkout(Product $product)
    {
        return view('products.checkout', compact('product'));
    }

    // Memproses pesanan/pembelian
    public function storeOrder(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        Order::create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $validated['quantity'],
            'unit_price' => $product->price,
            'total' => $product->price * $validated['quantity'],
            'customer_name' => $validated['customer_name'],
            'address' => $validated['address'],
        ]);

        return redirect()->route('products.index')->with('success', 'Pesanan berhasil dikirim ke kasir.');
    }
}