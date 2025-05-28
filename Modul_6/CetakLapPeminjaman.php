<?php
require_once "Koneksi_FKOM.php";

require_once __DIR__ . '/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

ob_start();

error_reporting(0);

try {
    $pdf = new Html2Pdf('L', 'A4', 'en', true, 'UTF-8', array(10, 15, 10, 15));
    $pdf->setTestTdInOnePage(false);

    // Sekarang LapPeminjaman.php akan meng-handle koneksinya sendiri via require_once
    include "LapPeminjaman.php";

    $html = ob_get_contents();
    ob_end_clean();

    $pdf->writeHTML($html);
    $pdf->Output('Laporan_Peminjaman_Buku_FKOM.pdf', 'D');
} catch (Html2PdfException $e) {
    ob_end_clean();
    $formatter = new ExceptionFormatter($e);
    echo "Terjadi Kesalahan Saat Membuat PDF:<br>";
    echo $formatter->getHtmlMessage();
} catch (Exception $e) {
    ob_end_clean();
    echo "Terjadi Kesalahan Umum:<br>";
    echo "Pesan: " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "File: " . htmlspecialchars($e->getFile()) . "<br>";
    echo "Baris: " . htmlspecialchars($e->getLine()) . "<br>";
}
