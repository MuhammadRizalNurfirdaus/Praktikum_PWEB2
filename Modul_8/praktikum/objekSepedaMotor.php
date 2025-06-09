<?php

include_once 'classSepedaMotor.php';

$motor = new SepedaMotor();
$motor->setMerk("Honda");
echo "Merk Sepeda Motor: " . $motor->getMerk();
?>