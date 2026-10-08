<x-app-layout>
    <div style="background-color: #f4f6f8; min-height: 100vh; padding: 20px; font-family: sans-serif;">
        <div style="max-width: 1200px; margin: 0 auto;">

            <!-- HEADER NAVIGATION DASHBOARD TOKO -->
            <div style="background-color: #ffffff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; overflow: hidden;">
                <div style="background-color: #1e293b; color: #ffffff; padding: 12px 20px; font-weight: 600; font-size: 15px;">
                    Dashboard Toko Velocity
                </div>
                <div style="display: flex; gap: 24px; padding: 12px 20px; font-size: 14px; border-bottom: 1px solid #e2e8f0; background-color: #ffffff;">
                    <a href="#" style="text-decoration: none; color: #0284c7; display: flex; align-items: center; gap: 6px;">
                        👥 Beranda
                    </a>
                    <a href="#" style="text-decoration: none; color: #0284c7; display: flex; align-items: center; gap: 6px;">
                        📊 Laporan
                    </a>
                    <a href="#" style="text-decoration: none; color: #e11d48; display: flex; align-items: center; gap: 6px;">
                        ❤️ Wishlist
                    </a>
                    <a href="#" style="text-decoration: none; color: #0284c7; font-weight: 700; display: flex; align-items: center; gap: 6px; border-bottom: 2px solid #0284c7; padding-bottom: 4px;">
                        📋 Riwayat Belanja
                    </a>
                    <a href="#" style="text-decoration: none; color: #0284c7; display: flex; align-items: center; gap: 6px;">
                        ➕ Produk
                    </a>
                    <a href="#" style="text-decoration: none; color: #0284c7; display: flex; align-items: center; gap: 6px;">
                        ⚙️ Profil ▾
                    </a>
                </div>
            </div>

            <!-- MAIN CONTENT AREA (2 KOLOM: TABEL RIWAYAT + SIDEBAR) -->
            <div style="display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start;">

                <!-- KOLOM KIRI: TABEL RIWAYAT BELANJA -->
                <div style="background-color: #ffffff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                    <!-- HEADER BOX -->
                    <div style="background-color: #1e293b; color: #ffffff; padding: 12px 20px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                        💻 Riwayat Belanja
                    </div>

                    <!-- CONTAINER TABEL DENGAN BORDER -->
                    <div style="padding: 20px;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; color: #334155;">
                            <thead>
                                <tr style="border-bottom: 2px solid #cbd5e1; font-weight: 700; color: #0f172a;">
                                    <th style="padding: 12px 8px;">Invoice</th>
                                    <th style="padding: 12px 8px;">Date</th>
                                    <th style="padding: 12px 8px;">Total</th>
                                    <th style="padding: 12px 8px; text-align: right;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Variabel penampung data (sesuaikan dengan nama variabel dari controller Anda, misal: $transactions)
                                    $riwayatList = $transactions ?? $orders ?? $riwayat ?? [];
                                @endphp

                                @forelse($riwayatList as $item)
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 12px 8px; font-weight: 600; color: #1e293b;">
                                            #{{ $item->invoice_number ?? $item->kode_transaksi ?? $item->id ?? '339892833' }}
                                        </td>
                                        <td style="padding: 12px 8px; color: #475569;">
                                            {{ isset($item->created_at) ? \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i:s') : '07-01-2020 10:27:50' }}
                                        </td>
                                        <td style="padding: 12px 8px; font-weight: 600; color: #0f172a;">
                                            Rp {{ number_format($item->total_price ?? $item->total ?? 268000, 0, ',', '.') }}
                                        </td>
                                        <td style="padding: 12px 8px; text-align: right;">
                                            <a href="{{ Route::has('transaksi.detail') ? route('transaksi.detail', $item->id ?? 1) : '#' }}" 
                                               style="display: inline-block; padding: 6px 12px; border: 1px solid #64748b; border-radius: 4px; color: #334155; text-decoration: none; font-size: 12px; font-weight: 500; background-color: #ffffff; transition: all 0.2s;">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- DATA DUMMY / CONTOH JIKA BELUM ADA DATA DARI DATABASE -->
                                    @for($i = 1; $i <= 8; $i++)
                                        <tr style="border-bottom: 1px solid #e2e8f0;">
                                            <td style="padding: 12px 8px; font-weight: 600; color: #1e293b;">
                                                #{{ rand(100000000, 999999999) }}
                                            </td>
                                            <td style="padding: 12px 8px; color: #475569;">
                                                07-01-2020 10:27:50
                                            </td>
                                            <td style="padding: 12px 8px; font-weight: 600; color: #0f172a;">
                                                Rp {{ number_format(rand(100, 5000) * 1000, 0, ',', '.') }}
                                            </td>
                                            <td style="padding: 12px 8px; text-align: right;">
                                                <button style="padding: 6px 12px; border: 1px solid #64748b; border-radius: 4px; color: #334155; font-size: 12px; font-weight: 500; background-color: #ffffff; cursor: pointer;">
                                                    Lihat Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @endfor
                                @endforelse
                            </tbody>
                        </table>

                        <!-- PAGINATION -->
                        <div style="margin-top: 20px; display: flex; gap: 4px;">
                            <span style="padding: 6px 12px; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155; font-size: 12px; border-radius: 2px; font-weight: 600; cursor: pointer;">1</span>
                            <span style="padding: 6px 12px; border: 1px solid #e2e8f0; background-color: #ffffff; color: #64748b; font-size: 12px; border-radius: 2px; cursor: pointer;">2</span>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: SIDEBAR TOKO & PRODUK POPULER -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    
                    <!-- CARD DETAIL TOKO -->
                    <div style="background-color: #ffffff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                        <div style="padding: 16px; text-align: center;">
                            <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400" 
                                 alt="Gambar Toko" 
                                 style="width: 100%; height: 180px; object-fit: cover; border-radius: 4px; margin-bottom: 12px;">
                        </div>
                        <div style="background-color: #1e293b; color: #ffffff; padding: 10px; text-align: center; font-weight: 700; font-size: 14px;">
                            Toko Naura
                        </div>
                        <div style="padding: 12px 16px; font-size: 12px; color: #475569; text-align: center; border-bottom: 1px solid #f1f5f9;">
                            📍 Subang, Jawa Barat
                        </div>
                        <div style="padding: 12px 16px; font-size: 12px; color: #64748b; text-align: center; line-height: 1.4;">
                            Menjual Kebutuhan Busana Muslim Terlengkap dan Termurah. Baca deskripsi lengkap.
                        </div>
                    </div>

                    <!-- CARD PRODUK POPULER -->
                    <div style="background-color: #ffffff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                        <div style="background-color: #1e293b; color: #ffffff; padding: 10px 16px; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 6px;">
                            👁️ Produk Populer
                        </div>
                        <div style="display: flex; flex-direction: column;">
                            @php
                                $populer = [
                                    ['nama' => 'Kinara Scarf Rhinestone', 'views' => 402],
                                    ['nama' => 'Lamma Necklace Green', 'views' => 253],
                                    ['nama' => 'Devina Blouse White', 'views' => 211],
                                    ['nama' => 'Noola Apr Brooches', 'views' => 190],
                                    ['nama' => 'Baju Velocity', 'views' => 170],
                                    ['nama' => 'Square Voile Cotton', 'views' => 157],
                                    ['nama' => 'Felia Skirt Black', 'views' => 132],
                                    ['nama' => 'Shaleema Draped Tunic Gold', 'views' => 130],
                                ];
                            @endphp

                            @foreach($populer as $p)
                                <div style="padding: 10px 16px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                                    <a href="#" style="text-decoration: none; color: #0284c7; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 190px;">
                                        {{ $p['nama'] }}
                                    </a>
                                    <span style="background-color: #0284c7; color: #ffffff; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 12px;">
                                        {{ $p['views'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>