<?php 
$NA = 89; // Nilai Angka

if($NA < 0 || $NA > 100) {
    echo "Maaf Nilai Anda Harus > 0 dan < 100";
} else {
    switch (intdiv($NA, 10)) {
        case 10: // nilai 100
        case 9:  // nilai 90-99
            $HM = 'A';
            break;
        case 8: // 80-89
        case 7: // 70-79
            $HM = 'B';
            break;
        case 6: // 60-69
            $HM = 'C';
            break;
        case 5: // 50-59
            $HM = 'D';
            break;
        default: // 0-49
            $HM = 'E';
            break;
    }

    echo "Nilai Anda = $NA<br>Huruf Mutu = $HM";
}
?>
