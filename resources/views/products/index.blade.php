<x-app-layout>
    <div class="retail-page retail-enter space-y-6">
        @if (session('success'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900" role="status">
                {{ session('success') }}
            </div>
        @endif

        <section class="relative overflow-hidden rounded-lg border border-emerald-950/10 bg-white px-5 py-6 shadow-sm sm:px-8 sm:py-8">
            <div class="absolute inset-y-0 right-0 w-2 bg-[var(--retail-coral)]"></div>
            <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
                <div>
                    <p class="retail-eyebrow mb-2">Rajua / Katalog</p>
                    <h1 class="retail-title">Belanja yang terasa mudah.</h1>
                    <p class="retail-muted mt-2 max-w-xl text-sm">Temukan produk pilihan toko, tersusun rapi berdasarkan kategori.</p>
                </div>
                <a href="{{ route('products.create') }}" class="retail-primary self-start sm:self-center">
                    <svg aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" /></svg>
                    Tambah Produk
                </a>
            </div>
        </section>

        @php($categoryIcons = ['Elektronik' => '▣', 'Olahraga' => '↗', 'Mebel' => '⌂', 'Mainan' => '✳'])
        <section aria-label="Kategori produk" class="space-y-3">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="retail-eyebrow">Jelajahi</p>
                    <h2 class="mt-1 font-display text-xl font-bold">Kategori</h2>
                </div>
                <span class="retail-muted text-xs">{{ $categories->count() }} kategori tersedia</span>
            </div>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-5">
                <a href="{{ route('products.index') }}" @class(['group flex min-h-20 items-center gap-3 rounded-md border px-3 py-3 transition hover:-translate-y-0.5 hover:shadow-sm sm:px-4', 'border-emerald-700 bg-emerald-50 ring-1 ring-emerald-700' => $activeCategoryId === 0, 'border-[var(--retail-line)] bg-white hover:border-emerald-300' => $activeCategoryId !== 0])>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-emerald-100 font-display text-xl font-bold text-emerald-800">✳</span>
                    <span class="min-w-0"><span class="block text-sm font-bold">Semua</span><span class="retail-muted text-xs">{{ $categories->sum('products_count') }} produk</span></span>
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category_id' => $category->id]) }}" @class(['group flex min-h-20 items-center gap-3 rounded-md border px-3 py-3 transition hover:-translate-y-0.5 hover:shadow-sm sm:px-4', 'border-emerald-700 bg-emerald-50 ring-1 ring-emerald-700' => $activeCategoryId === $category->id, 'border-[var(--retail-line)] bg-white hover:border-emerald-300' => $activeCategoryId !== $category->id])>
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-orange-50 font-display text-xl font-bold text-orange-800">{{ $categoryIcons[$category->name] ?? '✳' }}</span>
                        <span class="min-w-0"><span class="block truncate text-sm font-bold">{{ $category->name }}</span><span class="retail-muted text-xs">{{ $category->products_count }} produk</span></span>
                    </a>
                @endforeach
            </div>
        </section>

        <section>
            <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="retail-eyebrow">Pilihan toko</p>
                    <h2 class="mt-1 font-display text-2xl font-bold">{{ $categories->firstWhere('id', $activeCategoryId)?->name ?? 'Semua produk' }}</h2>
                </div>
                <span class="retail-muted text-sm">{{ $products->count() }} produk</span>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                @forelse ($products as $product)
                    <article class="group overflow-hidden rounded-lg border border-[var(--retail-line)] bg-white transition duration-200 hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative aspect-[4/3] overflow-hidden bg-[#edf1ed]">
                            <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1490312278390-ab64016e0aa9?w=800&auto=format&fit=crop' }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                            <span class="absolute left-3 top-3 rounded-sm bg-white/95 px-2.5 py-1 text-[11px] font-bold text-emerald-900 shadow-sm">{{ $product->categoryItem?->name ?? 'Pilihan Rajua' }}</span>
                        </div>
                        <div class="p-4">
                            <h3 class="truncate font-display text-lg font-bold">{{ $product->name }}</h3>
                            <div class="mt-2 flex items-end justify-between gap-3">
                                <p class="font-display text-xl font-bold text-emerald-800">Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</p>
                                <a href="{{ route('products.checkout', $product->id) }}" aria-label="Beli {{ $product->name }}" class="retail-primary shrink-0 px-3 py-2 text-xs">Beli</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-lg border border-dashed border-emerald-300 bg-white px-6 py-14 text-center">
                        <p class="font-display text-xl font-bold">Belum ada produk di kategori ini.</p>
                        <a href="{{ route('products.index') }}" class="mt-3 inline-flex text-sm font-bold text-emerald-800 underline">Lihat semua produk</a>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>