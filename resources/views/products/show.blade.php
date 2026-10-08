@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow">
            
            <!-- Header Halaman & Tombol Kembali -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 style="font-size: 24px; font-weight: bold; color: #1f2937; margin: 0;">Detail Produk</h2>
                <a href="{{ route('products.index') }}" style="padding: 8px 16px; background-color: #6b7280; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px;">
                    &larr; Kembali
                </a>
            </div>

            <!-- Kartu Detail Produk -->
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px;">
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
                    
                    <!-- Gambar Produk -->
                    <div style="text-align: center;">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; max-height: 250px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb;">
                        @else
                            <div style="width: 100%; height: 200px; background-color: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 14px;">
                                Tidak ada gambar
                            </div>
                        @endif
                    </div>

                    <!-- Informasi Detail -->
                    <div>
                        <h3 style="font-size: 20px; font-weight: bold; color: #111827; margin: 0 0 8px 0;">{{ $product->name }}</h3>
                        
                        <div style="margin-bottom: 16px;">
                            <span style="background-color: #e0e7ff; color: #3730a3; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 9999px;">
                                {{ $product->categoryItem?->name ?? $product->category ?? 'Tanpa Kategori' }}
                            </span>
                            
                            @if ($product->is_active)
                                <span style="background-color: #dcfce7; color: #166534; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 9999px; margin-left: 6px;">
                                    Aktif
                                </span>
                            @else
                                <span style="background-color: #fee2e2; color: #991b1b; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 9999px; margin-left: 6px;">
                                    Non-Aktif
                                </span>
                            @endif
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; color: #6b7280; font-weight: bold; display: block;">HARGA</label>
                            <span style="font-size: 22px; font-weight: bold; color: #2563eb;">
                                Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}
                            </span>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; color: #6b7280; font-weight: bold; display: block;">STOK TERSERDIA</label>
                            <span style="font-size: 16px; font-weight: 600; color: #374151;">
                                {{ $product->stock ?? 0 }} unit
                            </span>
                        </div>

                        <div>
                            <label style="font-size: 12px; color: #6b7280; font-weight: bold; display: block;">DESKRIPSI</label>
                            <p style="font-size: 14px; color: #4b5563; line-height: 1.5; margin-top: 4px;">
                                {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Tombol Action -->
                <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; display: flex; gap: 8px;">
                    <a href="{{ route('products.edit', $product->id) }}" style="padding: 8px 16px; background-color: #eab308; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px;">
                        Edit Produk
                    </a>
                    
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding: 8px 16px; background-color: #ef4444; color: white; border-radius: 6px; border: none; font-weight: 600; font-size: 14px; cursor: pointer;" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                            Hapus Produk
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection 