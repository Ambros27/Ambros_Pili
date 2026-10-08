<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Form Pemesanan Produk</h2>

                {{-- Ringkasan Produk --}}
                <div class="flex items-center gap-4 p-4 border rounded-lg bg-gray-50 mb-6">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/100' }}" class="w-20 h-20 object-cover rounded-lg">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">{{ $product->name }}</h3>
                        <p class="text-blue-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Form Pesanan --}}
                <form action="{{ route('products.order', $product->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Pembeli</label>
                        <input type="text" name="customer_name" required class="w-full mt-1 p-2 border rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah Pesanan</label>
                        <input type="number" name="quantity" min="1" value="1" required class="w-full mt-1 p-2 border rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat Pengiriman</label>
                        <textarea name="address" rows="3" required class="w-full mt-1 p-2 border rounded-lg"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('products.index') }}" class="px-4 py-2 border rounded-lg text-gray-600">Batal</a>
                        <button type="submit" style="background-color: #2563eb; color: #fff; padding: 8px 20px; border-radius: 8px; font-weight: 600;">
                            Konfirmasi Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>