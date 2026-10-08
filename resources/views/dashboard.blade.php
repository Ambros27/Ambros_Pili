<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- BANNER WELCOME MENGGUNAKAN INLINE STYLES (PASTI KEREN & KONTRAS) -->
            <div style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); padding: 24px; border-radius: 16px; color: #ffffff; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);" 
                 class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                
                <div>
                    <!-- TULISAN WELCOME WARNA PUTIH TEBAL & TAJAM -->
                    <h3 style="color: #ffffff !important; font-size: 24px; font-weight: 800; margin: 0;" class="flex items-center gap-2">
                        Selamat Datang, {{ Auth::user()->name }}! 👋
                    </h3>
                    <p style="color: #dbeafe !important; font-size: 14px; margin-top: 6px;" class="font-medium">
                        Ringkasan performa toko dan statistik transaksi hari ini.
                    </p>
                </div>

                <div>
                    <!-- TOMBOL TRANSAKSI BARU -->
                    <a href="{{ route('kasir.index') }}" 
                       style="background-color: #ffffff; color: #1e40af; font-weight: 700; font-size: 13px; padding: 10px 20px; border-radius: 10px; display: inline-block; text-decoration: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        + Transaksi Baru
                    </a>
                </div>
            </div>

            <!-- GRID RINGKASAN STATISTIK -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                
                <!-- Card 1: Pendapatan -->
                <div style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 20px;" class="shadow-sm flex items-center justify-between">
                    <div>
                        <p style="color: #6b7280; font-size: 11px; font-weight: 700; text-transform: uppercase;">Pendapatan Hari Ini</p>
                        <h4 style="color: #111827; font-size: 22px; font-weight: 900; margin-top: 4px;">
                            Rp {{ number_format($totalPendapatanHariIni ?? 0, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div style="width: 48px; height: 48px; background-color: #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        💰
                    </div>
                </div>

                <!-- Card 2: Transaksi -->
                <div style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 20px;" class="shadow-sm flex items-center justify-between">
                    <div>
                        <p style="color: #6b7280; font-size: 11px; font-weight: 700; text-transform: uppercase;">Transaksi Hari Ini</p>
                        <h4 style="color: #2563eb; font-size: 22px; font-weight: 900; margin-top: 4px;">
                            {{ $totalTransaksiHariIni ?? 0 }} <span style="font-size: 14px; font-weight: 600; color: #6b7280;">Transaksi</span>
                        </h4>
                    </div>
                    <div style="width: 48px; height: 48px; background-color: #dbeafe; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        🛒
                    </div>
                </div>

                <!-- Card 3: Total Produk -->
                <div style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 20px;" class="shadow-sm flex items-center justify-between">
                    <div>
                        <p style="color: #6b7280; font-size: 11px; font-weight: 700; text-transform: uppercase;">Total Item Produk</p>
                        <h4 style="color: #111827; font-size: 22px; font-weight: 900; margin-top: 4px;">
                            {{ $totalProduk ?? 0 }} <span style="font-size: 14px; font-weight: 600; color: #6b7280;">Item</span>
                        </h4>
                    </div>
                    <div style="width: 48px; height: 48px; background-color: #d1fae5; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        📦
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout> 