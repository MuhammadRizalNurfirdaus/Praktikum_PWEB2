<?php
extract($_POST);

function tampilAlert($pesan)
{
    echo "<script>alert('Pesan : $pesan'); 
        window.history.go(-1);</script>";
}

function kuadrat($bilangan)
{
    $kuadrat = pow($bilangan, 2);
    echo "<script>
        alert('Hasil kuadrat dari $bilangan adalah $kuadrat');
        window.history.go(-1);
    </script>";
}

// Fungsi baru untuk mengecek bilangan ganjil/genap
function cekGanjilGenap($bilangan)
{
    if (!is_numeric($bilangan)) {
        echo "<script>
            alert('Input bukan angka!');
            window.history.go(-1);
        </script>";
        return;
    }

    if ($bilangan % 2 == 0) {
        $hasil = "genap";
    } else {
        $hasil = "ganjil";
    }

    echo "<script>
        alert('Bilangan $bilangan adalah $hasil');
        window.history.go(-1);
    </script>";
}

if (isset($submit)) {
    switch ($submit) {
        case "Tampilkan":
            tampilAlert($pesan);
            break;
        case "Kuadrat":
            kuadrat($bilangan);
            break;
        case "Cek Ganjil/Genap":
            cekGanjilGenap($bilangan);
            break;
    }
}
?>

<HTML>

<HEAD>
    <TITLE> Latihan FUNGSI </TITLE>
</HEAD>

<BODY>
    <FORM METHOD=POST ACTION="prak_2f.php">

        Tuliskan Pesan Anda <INPUT TYPE="text" NAME="pesan">
        <INPUT TYPE="submit" name="submit" value="Tampilkan">
        <BR>
        <HR>
        Tuliskan Bilangan <INPUT TYPE="text" NAME="bilangan" size=3>
        <INPUT TYPE="submit" name="submit" value="Kuadrat">
        <INPUT TYPE="submit" name="submit" value="Cek Ganjil/Genap">

    </FORM>
</BODY>

</HTML>