<x-app-layout>
    <div class="retail-page retail-enter space-y-6">
        @if (session('success'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900" role="status">
                {{ session('success') }}
            </div>
        @endif

        <header class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="retail-eyebrow">Rajua / Point of sale</p>
                <h1 class="retail-title mt-1">Meja kasir</h1>
                <p class="retail-muted mt-2 text-sm">Pilih barang, periksa keranjang, lalu selesaikan pembayaran.</p>
            </div>
            <div class="rounded-md border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm">
                <span class="retail-muted">Kasir aktif</span>
                <span class="ml-2 font-bold text-emerald-900">{{ auth()->user()?->name ?? 'Kasir' }}</span>
            </div>
        </header>

        @if ($orders->isNotEmpty())
            <section class="retail-panel overflow-hidden border-l-4 border-l-[var(--retail-coral)] p-4 sm:p-5">
                <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <p class="retail-eyebrow">Perlu ditangani</p>
                        <h2 class="mt-1 font-display text-xl font-bold">Pesanan pelanggan</h2>
                    </div>
                    <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-bold text-orange-900">{{ $orders->count() }} aktif</span>
                </div>
                <div class="divide-y divide-[var(--retail-line)]">
                    @foreach ($orders as $order)
                        <div class="flex flex-col justify-between gap-3 py-3 sm:flex-row sm:items-center">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-bold">{{ $order->product_name }} × {{ $order->quantity }}</span>
                                    <span @class(['rounded-sm px-2 py-0.5 text-[11px] font-bold', 'bg-orange-50 text-orange-900' => $order->status === 'pending', 'bg-emerald-50 text-emerald-900' => $order->status === 'processing'])>{{ $order->status === 'pending' ? 'Menunggu' : 'Diproses' }}</span>
                                </div>
                                <p class="retail-muted mt-1 text-sm">{{ $order->customer_name }} · Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                                <p class="retail-muted mt-0.5 truncate text-xs">{{ $order->address }}</p>
                            </div>
                            <form action="{{ route('kasir.orders.process', $order) }}" method="POST" class="shrink-0">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="retail-secondary w-full sm:w-auto">{{ $order->status === 'pending' ? 'Siapkan pembayaran' : 'Lanjutkan pembayaran' }}</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <div class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_360px]">
            <section class="space-y-4">
                <div class="retail-panel p-4 sm:p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="retail-eyebrow">Katalog kasir</p>
                            <h2 class="mt-1 font-display text-xl font-bold">Pilih produk</h2>
                        </div>
                        <form action="{{ route('kasir.index') }}" method="GET" class="w-full sm:max-w-xs">
                            @if ($activeCategoryId > 0)
                                <input type="hidden" name="category_id" value="{{ $activeCategoryId }}">
                            @endif
                            <label class="sr-only" for="kasir-search">Cari produk</label>
                            <input id="kasir-search" type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="retail-field">
                        </form>
                    </div>
                    <nav aria-label="Filter kategori" class="mt-4 flex gap-2 overflow-x-auto pb-1">
                        <a href="{{ route('kasir.index', request('search') ? ['search' => request('search')] : []) }}" @class(['shrink-0 rounded-full border px-3 py-1.5 text-xs font-bold transition', 'border-emerald-800 bg-emerald-800 text-white' => $activeCategoryId === 0, 'border-[var(--retail-line)] text-gray-600 hover:border-emerald-600 hover:text-emerald-800' => $activeCategoryId !== 0])>Semua <span class="opacity-70">{{ $categories->sum('products_count') }}</span></a>
                        @foreach ($categories as $category)
                            <a href="{{ route('kasir.index', array_filter(['category_id' => $category->id, 'search' => request('search')])) }}" @class(['shrink-0 rounded-full border px-3 py-1.5 text-xs font-bold transition', 'border-emerald-800 bg-emerald-800 text-white' => $activeCategoryId === $category->id, 'border-[var(--retail-line)] text-gray-600 hover:border-emerald-600 hover:text-emerald-800' => $activeCategoryId !== $category->id])>{{ $category->name }} <span class="opacity-70">{{ $category->products_count }}</span></a>
                        @endforeach
                    </nav>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-4">
                    @forelse ($products as $product)
                        <article class="group overflow-hidden rounded-lg border border-[var(--retail-line)] bg-white transition hover:-translate-y-0.5 hover:shadow-md">
                            <div class="aspect-[4/3] overflow-hidden bg-[#edf1ed]">
                                <img src="{{ $product->image && str_starts_with($product->image, 'http') ? $product->image : ($product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1490312278390-ab64016e0aa9?w=600&auto=format&fit=crop') }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                            </div>
                            <div class="p-3">
                                <p class="retail-muted truncate text-[11px]">{{ $product->categoryItem?->name ?? 'Produk' }}</p>
                                <h3 class="mt-1 min-h-10 font-display text-sm font-bold leading-5">{{ $product->name }}</h3>
                                <p class="mt-2 text-sm font-extrabold text-emerald-800">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <form action="{{ route('kasir.add') }}" method="POST" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="retail-secondary w-full px-2 py-2 text-xs group-hover:border-emerald-300">Tambah ke keranjang</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-lg border border-dashed border-emerald-300 bg-white px-6 py-12 text-center">
                            <p class="font-display text-lg font-bold">Produk tidak ditemukan.</p>
                            <a href="{{ route('kasir.index') }}" class="mt-2 inline-flex text-sm font-bold text-emerald-800 underline">Hapus filter</a>
                        </div>
                    @endforelse
                </div>
            </section>

            <aside class="retail-panel overflow-hidden lg:sticky lg:top-24">
                <div class="border-b border-[var(--retail-line)] p-4 sm:p-5">
                    <p class="retail-eyebrow">Transaksi aktif</p>
                    <h2 class="mt-1 font-display text-xl font-bold">Keranjang</h2>
                </div>
                <div class="max-h-[38vh] space-y-3 overflow-y-auto p-4 sm:p-5">
                    @php($cartTotal = 0)
                    @forelse ($cart as $id => $item)
                        @php($lineTotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1))
                        @php($cartTotal += $lineTotal)
                        <div class="flex items-start justify-between gap-3 border-b border-dashed border-[var(--retail-line)] pb-3">
                            <div class="min-w-0">
                                <p class="font-bold leading-5">{{ $item['name'] ?? 'Produk' }}</p>
                                <p class="retail-muted mt-1 text-xs">{{ $item['quantity'] ?? 1 }} × Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="text-sm font-bold">Rp {{ number_format($lineTotal, 0, ',', '.') }}</p>
                                <form action="{{ route('kasir.cart.remove', $id) }}" method="POST" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-[var(--retail-coral)] hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 font-display text-2xl font-bold text-emerald-800">0</span>
                            <p class="mt-3 font-bold">Keranjang masih kosong</p>
                            <p class="retail-muted mt-1 text-xs">Tambahkan produk untuk memulai.</p>
                        </div>
                    @endforelse
                </div>
                <div class="border-t border-[var(--retail-line)] bg-[#f8faf8] p-4 sm:p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <span class="retail-muted text-sm font-semibold">Total bayar</span>
                        <span class="font-display text-2xl font-bold text-emerald-900">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($errors->has('cart'))
                        <p class="mb-3 rounded-md bg-red-50 px-3 py-2 text-xs font-semibold text-red-800">{{ $errors->first('cart') }}</p>
                    @endif
                    @if ($errors->has('payment'))
                        <p class="mb-3 rounded-md bg-red-50 px-3 py-2 text-xs font-semibold text-red-800">{{ $errors->first('payment') }}</p>
                    @endif
                    <form action="{{ route('kasir.checkout') }}" method="POST" class="space-y-3">
                        @csrf
                        <label class="block text-xs font-bold text-gray-700">Metode pembayaran
                            <select name="payment_method" required class="retail-field mt-1.5">
                                <option value="Tunai">Tunai</option>
                                <option value="QRIS">QRIS</option>
                                <option value="Transfer">Transfer</option>
                                <option value="Debit">Debit</option>
                            </select>
                        </label>
                        <label class="block text-xs font-bold text-gray-700">Uang diterima
                            <div class="mt-1.5 flex overflow-hidden rounded-md border border-[var(--retail-line)] bg-white focus-within:border-emerald-700 focus-within:ring-2 focus-within:ring-emerald-100">
                                <span class="flex items-center border-r border-[var(--retail-line)] bg-gray-50 px-3 text-xs font-bold text-gray-500">Rp</span>
                                <input type="number" name="payment" min="{{ $cartTotal }}" step="1" value="{{ old('payment') }}" required placeholder="Nominal pembayaran" class="w-full border-0 text-sm outline-none focus:ring-0">
                            </div>
                        </label>
                        <button type="submit" @disabled(empty($cart)) class="retail-primary w-full py-3 disabled:cursor-not-allowed disabled:bg-gray-300">
                            Bayar sekarang
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
