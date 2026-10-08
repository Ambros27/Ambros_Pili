<?php

namespace App\Http\Controllers;

use Exception;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class StrukController extends Controller
{
    /**
     * Cetak struk transaksi langsung ke printer thermal
     */
    public function struk()
    {
        try {
            // 1. Koneksi ke Printer Windows (menggunakan nama "Generic / Text Only")
            $connector = new WindowsPrintConnector("Generic / Text Only");
            $printer = new Printer($connector);

            // 2. Cetak Logo Toko (Mengecek file logo.jpg atau logo.png)
            $logoJpg = public_path('images/logo.jpg');
            $logoPng = public_path('images/logo.png');

            if (file_exists($logoJpg)) {
                $logo = EscposImage::load($logoJpg, false);
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->bitImage($logo);
            } elseif (file_exists($logoPng)) {
                $logo = EscposImage::load($logoPng, false);
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->bitImage($logo);
            }

            // 3. Header Toko Raijua
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("RAIJUA STORE\n");
            $printer->setEmphasis(false);
            $printer->text("Jl. Mastrip No. 164, Jember\n");
            $printer->text("Telp. 0812-3456-7890\n");
            $printer->text("--------------------------------\n");

            // 4. Informasi Transaksi
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("No   : TRX-" . date('Ymd') . "-001\n");
            $printer->text("Kasir: Admin\n");
            $printer->text("Tgl  : " . date('d-m-Y H:i') . "\n");
            $printer->text("--------------------------------\n");

            // 5. Detail Barang & Subtotal
            $items = $this->getItems();
            $grandTotal = 0;

            foreach ($items as $item) {
                $subtotal = $item['qty'] * $item['price'];
                $grandTotal += $subtotal;

                $printer->text($item['name'] . "\n");
                $printer->text($item['qty'] . " x " . number_format($item['price'], 0, ',', '.'));
                $printer->text(" = Rp " . number_format($subtotal, 0, ',', '.') . "\n");
            }

            // 6. Total Belanja & Pembayaran
            $printer->text("--------------------------------\n");
            $printer->setEmphasis(true);
            $printer->text("TOTAL : Rp " . number_format($grandTotal, 0, ',', '.') . "\n");
            $printer->setEmphasis(false);

            $bayar = 100000;
            $kembalian = $bayar - $grandTotal;
            $printer->text("BAYAR : Rp " . number_format($bayar, 0, ',', '.') . "\n");
            $printer->text("KEMBALI: Rp " . number_format($kembalian, 0, ',', '.') . "\n");

            // 7. Footer Struk
            $printer->text("--------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Terima Kasih Atas Kunjungan Anda\n");
            $printer->text("Selamat Berbelanja Kembali\n");

            // 8. Potong Kertas & Tutup Koneksi
            $printer->feed(3);
            $printer->cut();
            $printer->close();

            return "Struk Raijua berhasil dicetak ke printer!";
        } catch (Exception $e) {
            return "Gagal mencetak struk: " . $e->getMessage();
        }
    }

    /**
     * Data barang (Dummy)
     */
    private function getItems()
    {
        return [
            ['name' => 'Indomie Goreng', 'qty' => 2, 'price' => 3500],
            ['name' => 'Aqua 600ml', 'qty' => 2, 'price' => 4000],
            ['name' => 'Roti Coklat', 'qty' => 1, 'price' => 7500],
            ['name' => 'Teh Botol Sosro', 'qty' => 2, 'price' => 5000],
        ];
    }
}