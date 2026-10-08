<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Daftar Transaksi Penjualan</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200">
                                <th class="p-3 border-r">No. Nota</th>
                                <th class="p-3 border-r">Tanggal</th>
                                <th class="p-3 border-r">Metode</th>
                                <th class="p-3 border-r">Total</th>
                                <th class="p-3 border-r">Bayar</th>
                                <th class="p-3 border-r">Kembali</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 border-r font-semibold text-blue-600">{{ $item->invoice_number }}</td>
                                    <td class="p-3 border-r">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="p-3 border-r">
                                        <span class="px-2 py-1 bg-gray-200 rounded text-xs font-bold">{{ $item->payment_method }}</span>
                                    </td>
                                    <td class="p-3 border-r font-bold">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                    <td class="p-3 border-r">Rp {{ number_format($item->pay_amount, 0, ',', '.') }}</td>
                                    <td class="p-3 border-r">Rp {{ number_format($item->change_amount, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center">
                                        <a href="{{ route('transactions.print', $item->id) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded inline-block font-medium shadow-sm transition duration-150">
                                            Cetak Struk
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-4 text-center text-gray-500">Belum ada transaksi recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>