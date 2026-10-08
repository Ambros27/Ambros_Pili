<x-app-layout>
    <div class="retail-page retail-enter space-y-6">
        <header class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="retail-eyebrow">Rajua / Laporan</p>
                <h1 class="retail-title mt-1">Riwayat transaksi</h1>
                <p class="retail-muted mt-2 text-sm">Pantau pembayaran, nota, dan omzet toko.</p>
            </div>
            <span class="rounded-md border border-emerald-100 bg-white px-3 py-2 text-xs font-bold text-emerald-900">{{ now()->translatedFormat('l, d F Y') }}</span>
        </header>

        <section class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <article class="retail-panel relative overflow-hidden p-5">
                <span class="absolute inset-y-0 left-0 w-1 bg-[var(--retail-green)]"></span>
                <p class="retail-muted text-xs font-bold uppercase tracking-wider">Transaksi ditemukan</p>
                <p class="mt-2 font-display text-3xl font-bold">{{ number_format($transactionCount, 0, ',', '.') }}</p>
                <p class="retail-muted mt-1 text-xs">Sesuai pencarian dan tanggal yang dipilih</p>
            </article>
            <article class="retail-panel relative overflow-hidden p-5">
                <span class="absolute inset-y-0 left-0 w-1 bg-[var(--retail-coral)]"></span>
                <p class="retail-muted text-xs font-bold uppercase tracking-wider">Total omzet</p>
                <p class="mt-2 font-display text-3xl font-bold text-emerald-900">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
                <p class="retail-muted mt-1 text-xs">Dari transaksi yang sedang ditampilkan</p>
            </article>
        </section>

        <section class="retail-panel overflow-hidden">
            <div class="border-b border-[var(--retail-line)] p-4 sm:p-5">
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="retail-eyebrow">Semua pembayaran</p>
                        <h2 class="mt-1 font-display text-xl font-bold">Daftar transaksi</h2>
                    </div>
                    <span class="retail-muted text-xs">{{ $transactions->total() }} hasil</span>
                </div>
                <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-[minmax(220px,1fr)_160px_160px_auto_auto]">
                    <label class="sr-only" for="history-search">Cari nomor nota</label>
                    <input id="history-search" type="search" name="search" value="{{ request('search') }}" placeholder="Cari nomor nota..." class="retail-field">
                    <label class="sr-only" for="history-from">Dari tanggal</label>
                    <input id="history-from" type="date" name="date_from" value="{{ request('date_from') }}" class="retail-field" aria-label="Dari tanggal">
                    <label class="sr-only" for="history-to">Sampai tanggal</label>
                    <input id="history-to" type="date" name="date_to" value="{{ request('date_to') }}" class="retail-field" aria-label="Sampai tanggal">
                    <button type="submit" class="retail-primary">Terapkan filter</button>
                    <a href="{{ route('transactions.index') }}" class="retail-secondary">Reset</a>
                </form>
            </div>

            @if ($transactions->isNotEmpty())
                <div class="space-y-3 p-4 md:hidden">
                    @foreach ($transactions as $item)
                        <article class="rounded-md border border-[var(--retail-line)] p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate font-bold text-emerald-900">{{ $item->invoice_number }}</p>
                                    <p class="retail-muted mt-1 text-xs">{{ $item->created_at->format('d M Y · H:i') }}</p>
                                </div>
                                <span class="rounded-sm bg-emerald-50 px-2 py-1 text-[11px] font-bold text-emerald-900">Lunas</span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                                <div><p class="retail-muted">Total</p><p class="mt-1 font-bold">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</p></div>
                                <div><p class="retail-muted">Metode</p><p class="mt-1 font-bold">{{ $item->payment_method }}</p></div>
                            </div>
                            <a href="{{ route('transactions.print', $item->id) }}" class="retail-secondary mt-4 w-full">Lihat struk</a>
                        </article>
                    @endforeach
                </div>

                <div class="hidden overflow-x-auto md:block">
                    <table class="w-full min-w-[760px] text-left">
                        <thead class="bg-[#f7f9f7] text-[11px] uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="px-5 py-3 font-bold">Nomor nota</th>
                                <th class="px-5 py-3 font-bold">Tanggal</th>
                                <th class="px-5 py-3 font-bold">Metode</th>
                                <th class="px-5 py-3 text-right font-bold">Total</th>
                                <th class="px-5 py-3 text-right font-bold">Dibayar</th>
                                <th class="px-5 py-3 text-right font-bold">Kembalian</th>
                                <th class="px-5 py-3 text-center font-bold">Status</th>
                                <th class="px-5 py-3 text-right font-bold">Struk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--retail-line)] text-sm">
                            @foreach ($transactions as $item)
                                <tr class="transition hover:bg-emerald-50/40">
                                    <td class="px-5 py-4 font-bold text-emerald-900">{{ $item->invoice_number }}</td>
                                    <td class="retail-muted px-5 py-4">{{ $item->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-5 py-4"><span class="rounded-sm bg-orange-50 px-2 py-1 text-xs font-bold text-orange-900">{{ $item->payment_method }}</span></td>
                                    <td class="px-5 py-4 text-right font-bold">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-right">Rp {{ number_format($item->pay_amount, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-right">Rp {{ number_format($item->change_amount, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-center"><span class="rounded-sm bg-emerald-50 px-2 py-1 text-xs font-bold text-emerald-900">Lunas</span></td>
                                    <td class="px-5 py-4 text-right"><a href="{{ route('transactions.print', $item->id) }}" class="font-bold text-emerald-800 hover:text-emerald-950 hover:underline">Cetak</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-14 text-center">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-orange-50 font-display text-xl font-bold text-orange-900">—</span>
                    <h3 class="mt-4 font-display text-lg font-bold">Belum ada transaksi yang cocok</h3>
                    <p class="retail-muted mt-1 text-sm">Pembayaran yang berhasil akan tercatat di sini.</p>
                </div>
            @endif

            @if ($transactions->hasPages())
                <div class="border-t border-[var(--retail-line)] px-4 py-3 sm:px-5">{{ $transactions->links() }}</div>
            @endif
        </section>
    </div>
</x-app-layout>
