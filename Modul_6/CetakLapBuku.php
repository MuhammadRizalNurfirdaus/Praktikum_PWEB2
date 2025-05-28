<?php

// Path ini seharusnya sudah benar karena Composer dijalankan di Modul_6
require_once __DIR__ . '/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

ob_start(); // Mulai output buffering

// error_reporting(E_ALL); // Aktifkan ini jika masih ada masalah untuk debugging
// ini_set('display_errors', 1);
error_reporting(0); // Sembunyikan warning/notice di produksi

try {
    $pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));

    include "LapBuku.php"; // File ini menghasilkan HTML

    $html = ob_get_contents();
    ob_end_clean();

    $pdf->writeHTML($html);
    $pdf->Output('Laporan_Data_Buku.pdf', 'D');
} catch (Html2PdfException $e) {
    ob_end_clean();
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}
