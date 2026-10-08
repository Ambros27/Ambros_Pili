<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Produk') }}
            </h2>
            <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-semibold">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if ($errors->any())
                    <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Harga Jual (Rp)</label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Stok</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Foto Produk Saat Ini</label>
                        @if (!empty($product->image) && file_exists(public_path('storage/' . $product->image)))
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded-md mb-2 border border-gray-200">
                        @else
                            <p class="text-xs text-gray-400 mb-2">Belum ada foto.</p>
                        @endif

                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Ganti Foto (Opsional)</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-white hover:file:bg-gray-700">
                    </div>

                    <div class="pt-4 flex justify-end gap-2">
                        <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs font-semibold rounded-md hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-xs font-semibold rounded-md hover:bg-gray-700">
                            Perbarui Produk
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>