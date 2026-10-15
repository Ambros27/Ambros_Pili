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
            margin: 0 auto;
            padding: 5mm 4mm;
            background: #fff;
            box-shadow: 0 4px 18px rgba(23, 33, 28, .12);
            line-height: 1.25;
        }
        .text-center { text-align: center; }
        .line { border-bottom: 1px dashed #536159; margin: 7px 0; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        td { padding: 1px 0; vertical-align: top; }
        .receipt h1 { margin: 0 0 2px; font-size: 17px; letter-spacing: .04em; }
        .receipt p { margin: 2px 0; }
        .meta-label { width: 19mm; }
        .item-name { overflow-wrap: anywhere; }
        .item-price { padding-left: 2mm; }
        .total-row td { padding-top: 3px; font-weight: 700; }
        .footer { margin-top: 8px; }
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
                margin: 0;
                padding: 4mm;
                box-shadow: none;
            }
            tr, .line, header, footer { break-inside: avoid; }
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

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 500);
        });
    </script>

    <main class="receipt">
        <header class="text-center">
            <h1>RAIJUA STORE</h1>
            <p>Jl. Mastrip No. 164, Jember</p>
            <p>Telp. 0812-3456-7890</p>
        </header>

        <div class="line"></div>

        <table>
            <tr><td class="meta-label">No</td><td>: {{ $transaction->invoice_number }}</td></tr>
            <tr><td class="meta-label">Kasir</td><td>: {{ auth()->user()?->name ?? 'Kasir' }}</td></tr>
            <tr><td class="meta-label">Tgl</td><td>: {{ $transaction->created_at->format('d-m-Y H:i') }}</td></tr>
            <tr><td class="meta-label">Pemb</td><td>: {{ $transaction->payment_method }}</td></tr>
        </table>

        <div class="line"></div>

        <table>
            @foreach ($transaction->details as $detail)
                <tr><td colspan="2" class="item-name">{{ $detail->product->name ?? 'Barang' }}</td></tr>
                <tr>
                    <td colspan="2" class="item-price">{{ $detail->quantity }} x {{ number_format($detail->price, 0, ',', '.') }} = Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <div class="line"></div>

        <table>
            <tr class="total-row"><td>TOTAL</td><td>: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td></tr>
            <tr><td>BAYAR</td><td>: Rp {{ number_format($transaction->pay_amount, 0, ',', '.') }}</td></tr>
            <tr><td>KEMBALI</td><td>: Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td></tr>
        </table>

        <div class="line"></div>

        <footer class="text-center footer">
            <p>Terima Kasih Atas Kunjungan Anda</p>
            <p>Selamat Berbelanja Kembali</p>
        </footer>
    </main>
</body>
</html>