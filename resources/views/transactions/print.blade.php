<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->invoice_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 24px 12px;
            background: #eef1ee;
            color: #17211c;
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
        }
        .print-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 0 auto 18px;
            font-family: Arial, sans-serif;
        }
        .print-actions a,
        .print-actions button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 0 16px;
            border: 1px solid #d7dfd9;
            border-radius: 6px;
            background: #fff;
            color: #1d3329;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }
        .print-actions button {
            border-color: #11745b;
            background: #11745b;
            color: #fff;
        }
        .receipt {
            width: 80mm;
            min-height: 90mm;
            margin: 0 auto;
            padding: 5mm 4mm;
            background: #fff;
            box-shadow: 0 4px 18px rgba(23, 33, 28, .12);
            line-height: 1.4;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-bottom: 1px dashed #536159; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 2px 0; vertical-align: top; }
        .receipt h1 { margin: 0 0 4px; font-size: 17px; letter-spacing: .04em; }
        .receipt p { margin: 3px 0; }
        .total-row td { padding-top: 5px; font-size: 15px; font-weight: 700; }
        .print-actions button:hover { background: #0d5d49; }
        @page {
            size: 80mm 200mm;
            margin: 0;
        }
        @media print {
            html, body {
                width: 80mm;
                margin: 0;
                padding: 0;
                background: #fff;
            }
            .print-actions { display: none !important; }
            .receipt {
                width: 80mm;
                min-height: 0;
                margin: 0;
                padding: 4mm;
                box-shadow: none;
            }
            tr, .line, .text-center { break-inside: avoid; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <a href="{{ route('transactions.index') }}">Kembali</a>
        <button type="button" onclick="window.print()">Cetak struk</button>
    </div>

    <main class="receipt">
        <header class="text-center">
            <h1>TOKO RAJUA</h1>
            <p>Jl. Contoh No. 123 Jember</p>
            <p>Telp: 0812-xxxx-xxxx</p>
        </header>

        <div class="line"></div>

        <table>
            <tr><td>No. Nota</td><td class="text-right">{{ $transaction->invoice_number }}</td></tr>
            <tr><td>Tanggal</td><td class="text-right">{{ $transaction->created_at->format('d/m/Y H:i') }}</td></tr>
            <tr><td>Kasir</td><td class="text-right">{{ auth()->user()?->name ?? 'Kasir' }}</td></tr>
        </table>

        <div class="line"></div>

        <table>
            @foreach ($transaction->details as $detail)
                <tr><td colspan="2"><strong>{{ $detail->product->name ?? 'Barang' }}</strong></td></tr>
                <tr>
                    <td>{{ $detail->quantity }} × {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <div class="line"></div>

        <table>
            <tr class="total-row"><td>TOTAL</td><td class="text-right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td></tr>
            <tr><td>Bayar ({{ $transaction->payment_method }})</td><td class="text-right">Rp {{ number_format($transaction->pay_amount, 0, ',', '.') }}</td></tr>
            <tr><td>Kembalian</td><td class="text-right">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td></tr>
        </table>

        <div class="line"></div>

        <footer class="text-center">
            <p>Terima kasih telah berbelanja.</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar.</p>
        </footer>
    </main>
</body>
</html>