<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Bank</title>
</head>
<body>
<?php
class BankAccount {
    private $saldo;
    private $minimumSaldo = 5000;
    private $history = [];

    public function __construct($saldoAwal) {
        $this->saldo = max($saldoAwal, $this->minimumSaldo);
        $this->history[] = "Saldo awal: Rp. " . number_format($this->saldo, 0, ',', '.');
    }

    public function transaksi($kode, $jumlah) {
        if ($kode == 0) {
            $this->setor($jumlah);
        } elseif ($kode == 1) {
            $this->tarik($jumlah);
        } else {
            $this->history[] = "Kode transaksi tidak valid.";
        }
    }

    private function setor($jumlah) {
        if ($jumlah > 0) {
            $this->saldo += $jumlah;
            $this->history[] = "Setor Rp. " . number_format($jumlah, 0, ',', '.') . " | Saldo: Rp. " . number_format($this->saldo, 0, ',', '.');
        } else {
            $this->history[] = "Setoran gagal: Jumlah harus lebih dari 0.";
        }
    }

    private function tarik($jumlah) {
        if ($jumlah > 0) {
            if ($this->saldo - $jumlah >= $this->minimumSaldo) {
                $this->saldo -= $jumlah;
                $this->history[] = "Tarik Rp. " . number_format($jumlah, 0, ',', '.') . " | Saldo: Rp. " . number_format($this->saldo, 0, ',', '.');
            } else {
                $this->history[] = "Penarikan gagal: Saldo minimal Rp. " . number_format($this->minimumSaldo, 0, ',', '.') . " harus dipertahankan.";
            }
        } else {
            $this->history[] = "Penarikan gagal: Jumlah harus lebih dari 0.";
        }
    }

    public function tampilkanRiwayat() {
        echo "<h2>Riwayat Transaksi</h2>";
        echo "<ul>";
        foreach ($this->history as $transaksi) {
            echo "<li>$transaksi</li>";
        }
        echo "</ul>";
    }
}

// Contoh penggunaan
$akun = new BankAccount(10000);

$akun->transaksi(0, 5000); // Setor Rp. 5000
$akun->transaksi(1, 7000); // Tarik Rp. 7000
$akun->transaksi(1, 8000); // Tarik Rp. 8000 (harus gagal)

// Menampilkan riwayat transaksi
$akun->tampilkanRiwayat();
?>
</body>
</html>