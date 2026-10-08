<x-app-layout>
    <div style="background-color: #f3f4f6; min-height: 100vh; padding: 16px;">
        <div style="max-width: 1400px; margin: 0 auto;">

            @if (session('success'))
                <div style="background-color: #dcfce7; color: #166534; border: 1px solid #86efac; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;">
                    {{ session('success') }}
                </div>
            @endif

            <section style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                <h2 style="font-size: 18px; font-weight: 800; margin: 0 0 12px; color: #111827;">Pesanan Masuk</h2>
                @forelse ($orders as $order)
                    <div style="display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 12px; align-items: center; border-top: 1px solid #e5e7eb; padding: 12px 0;">
                        <div>
                            <div style="font-weight: 700; color: #111827;">{{ $order->product_name }} × {{ $order->quantity }}</div>
                            <div style="font-size: 13px; color: #4b5563;">{{ $order->customer_name }} · Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $order->address }}</div>
                            <span class="inline-block mt-1.5 text-xs font-bold {{ $order->status === 'pending' ? 'text-amber-800' : 'text-green-800' }}">
                                {{ $order->status === 'pending' ? 'Menunggu' : 'Diproses' }}
                            </span>
                        </div>
                        @if (in_array($order->status, ['pending', 'processing'], true))
                            <form action="{{ route('kasir.orders.process', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background-color: #2563eb; color: #ffffff; border: 0; border-radius: 6px; padding: 8px 12px; font-weight: 700; cursor: pointer;">
                                    {{ $order->status === 'pending' ? 'Siapkan Pembayaran' : 'Lanjutkan Pembayaran' }}
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p style="margin: 0; padding: 12px 0; color: #6b7280;">Belum ada pesanan masuk.</p>
                @endforelse
            </section>
            
            <div style="display: grid; grid-template-columns: 360px 1fr; gap: 16px; align-items: start;">

                <!-- PANEL KIRI: STRUK & KERANJANG BELANJA -->
                <div style="background-color: #ffffff; border-radius: 16px; border: 1px solid #e5e7eb; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    
                    <!-- Header Pelanggan / Pelayan -->
                    <div style="border-bottom: 1px solid #f3f4f6; padding-bottom: 12px; margin-bottom: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span style="font-size: 13px; font-weight: 700; color: #111827;">Mira Workman</span>
                            <span style="color: #9ca3af; cursor: pointer;">•••</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 12px; color: #6b7280; margin-bottom: 4px;">
                            <span>Pelayan:</span>
                            <span style="color: #10b981; font-weight: 600;">Pilih pelayan</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 12px; color: #6b7280;">
                            <span>Catatan:</span>
                            <span style="color: #9ca3af;">Masukkan catatan</span>
                        </div>
                    </div>

                    <!-- SENARAI ITEM BELANJA -->
                    <div style="max-height: 380px; overflow-y: auto; padding-right: 4px;">
                        @php
                            $cart = session('cart', []);
                            $total = 0;
                        @endphp

                        @forelse($cart as $id => $details)
                            @php
                                $subtotal = ($details['price'] ?? 0) * ($details['quantity'] ?? 1);
                                $total += $subtotal;
                            @endphp
                            <div style="padding: 10px 0; border-bottom: 1px dashed #e5e7eb;">
                                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 700; color: #111827;">
                                    <span>{{ $details['quantity'] ?? 1 }} {{ $details['name'] ?? 'Produk' }}</span>
                                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div style="font-size: 11px; color: #6b7280; margin-top: 2px;">
                                    Rp {{ number_format($details['price'] ?? 0, 0, ',', '.') }} / item
                                </div>
                                <form action="{{ route('kasir.cart.remove', $id) }}" method="POST" style="margin-top: 4px; text-align: right;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="border: 0; background: none; color: #dc2626; font-size: 11px; cursor: pointer;">Hapus</button>
                                </form>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 40px 10px; color: #9ca3af;">
                                <div style="font-size: 32px; margin-bottom: 6px;">📋</div>
                                <p style="font-size: 12px; font-weight: 600;">Belum ada pesanan dimasukkan</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- RINGKASAN BAYAR & RINGKASAN TOTAL -->
                    <div style="border-top: 2px solid #f3f4f6; margin-top: 16px; padding-top: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 12px; color: #4b5563;">
                            <span>Makan ditempat</span>
                            <span>›</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                            <span style="font-size: 15px; font-weight: 800; color: #111827;">Total</span>
                            <span style="font-size: 18px; font-weight: 900; color: #111827;">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('kasir.checkout') }}" method="POST" style="display: grid; gap: 10px; margin-top: 14px;">
                        @csrf
                        @if ($errors->has('cart'))
                            <p style="color: #b91c1c; font-size: 12px; margin: 0;">{{ $errors->first('cart') }}</p>
                        @endif
                        @if ($errors->has('payment'))
                            <p style="color: #b91c1c; font-size: 12px; margin: 0;">{{ $errors->first('payment') }}</p>
                        @endif
                        <label style="display: grid; gap: 4px; color: #4b5563; font-size: 12px; font-weight: 700;">
                            Metode pembayaran
                            <select name="payment_method" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 9px;">
                                <option value="Tunai">Tunai</option>
                                <option value="QRIS">QRIS</option>
                                <option value="Transfer">Transfer</option>
                                <option value="Debit">Debit</option>
                            </select>
                        </label>
                        <label style="display: grid; gap: 4px; color: #4b5563; font-size: 12px; font-weight: 700;">
                            Uang diterima (Rp)
                            <input type="number" name="payment" min="{{ $total }}" step="1" value="{{ old('payment') }}" required placeholder="Nominal pembayaran" style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 9px;">
                        </label>
                        <button type="submit" @disabled(empty($cart)) style="background-color: #10b981; color: #ffffff; font-size: 13px; font-weight: 800; padding: 11px 20px; border-radius: 8px; border: none; cursor: pointer;">
                            Bayar Rp {{ number_format($total, 0, ',', '.') }}
                        </button>
                    </form>

                </div>

                <!-- PANEL KANAN: KATALOG PRODUK & FILTER -->
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    
                    <!-- BAR CARIAN & CATEGORY TABS -->
                    <div style="background-color: #ffffff; border-radius: 16px; border: 1px solid #e5e7eb; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <form action="{{ url()->current() }}" method="GET" style="margin-bottom: 12px;">
                            <div style="position: relative;">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Cari produk di sini..." 
                                       style="width: 100%; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 14px 10px 36px; font-size: 13px; background-color: #f9fafb; outline: none;">
                                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 14px;">🔍</span>
                            </div>
                        </form>

                        <!-- CATEGORY TABS BUTTONS -->
                        <div style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px;">
                            <button style="background-color: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; border-radius: 20px; padding: 6px 14px; font-size: 12px; font-weight: 700; white-space: nowrap; cursor: pointer;">
                                ✓ Semua ({{ is_countable($products ?? $produk ?? []) ? count($products ?? $produk ?? []) : 0 }})
                            </button>
                            <button style="background-color: #f9fafb; color: #6b7280; border: 1px solid #e5e7eb; border-radius: 20px; padding: 6px 14px; font-size: 12px; font-weight: 600; white-space: nowrap; cursor: pointer;">Makanan</button>
                            <button style="background-color: #f9fafb; color: #6b7280; border: 1px solid #e5e7eb; border-radius: 20px; padding: 6px 14px; font-size: 12px; font-weight: 600; white-space: nowrap; cursor: pointer;">Minuman</button>
                            <button style="background-color: #f9fafb; color: #6b7280; border: 1px solid #e5e7eb; border-radius: 20px; padding: 6px 14px; font-size: 12px; font-weight: 600; white-space: nowrap; cursor: pointer;">Snack</button>
                            <button style="background-color: #f9fafb; color: #6b7280; border: 1px solid #e5e7eb; border-radius: 20px; padding: 6px 14px; font-size: 12px; font-weight: 600; white-space: nowrap; cursor: pointer;">Dessert</button>
                        </div>
                    </div>

                    <!-- GRID KARTU PRODUK -->
                    @php
                        $listProduk = $products ?? $produk ?? [];
                    @endphp

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 14px;">
                        @forelse($listProduk as $product)
                            <div style="background-color: #ffffff; border-radius: 16px; border: 1px solid #e5e7eb; padding: 14px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.04); display: flex; flex-direction: column; justify-content: space-between; height: 100%; transition: transform 0.1s;"
                                 class="hover:shadow-md">
                                
                                <div>
                                    <!-- CONTAINER FOTO DENGAN SIZE RAPI -->
                                    <div style="width: 100%; height: 110px; background-color: #ffffff; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                        @if(!empty($product->image))
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->name ?? $product->nama ?? '' }}" 
                                                 style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                        @else
                                            <span style="font-size: 40px;">🍔</span>
                                        @endif
                                    </div>

                                    <!-- NAMA PRODUK -->
                                    <h4 style="font-size: 13px; font-weight: 700; color: #111827; margin: 0; line-height: 1.2; height: 32px; overflow: hidden; display: -webkit-box; line-clamp: 2; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                        {{ $product->name ?? $product->nama_produk ?? $product->nama ?? 'Produk' }}
                                    </h4>

                                    <!-- HARGA -->
                                    <p style="font-size: 12px; font-weight: 700; color: #4b5563; margin-top: 4px;">
                                        Rp {{ number_format($product->price ?? $product->harga ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- BUTTON TAMBAH KE KERANJANG -->
                                <div style="margin-top: 10px;">
                                    @if(Route::has('kasir.add'))
                                        <form action="{{ route('kasir.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" 
                                                    style="width: 100%; background-color: #f3f4f6; color: #1f2937; font-size: 11px; font-weight: 700; padding: 6px; border-radius: 8px; border: none; cursor: pointer;">
                                                + Tambah
                                            </button>
                                        </form>
                                    @else
                                        <button style="width: 100%; background-color: #f3f4f6; color: #1f2937; font-size: 11px; font-weight: 700; padding: 6px; border-radius: 8px; border: none; cursor: pointer;">
                                            + Tambah
                                        </button>
                                    @endif
                                </div>

                            </div>
                        @empty
                            <div style="grid-column: 1 / -1; background-color: #ffffff; border-radius: 16px; border: 1px solid #e5e7eb; padding: 40px; text-align: center; color: #6b7280;">
                                Tidak ada produk ditemukan.
                            </div>
                        @endforelse
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>