<?php
session_start();
unset($_SESSION['nama_user']);
session_destroy();
echo "<h2>Thanks For Visitation</h2>";
