<HTML>
    <BODY>
        <form name="form1" method="get" action="Prak_1f.php">
            <pre>
NIM = <input type="text" name="NIM" maxlength="11">
<input type="submit" name="tampil" value="TAMPILKAN">

<?php
extract($_GET);
if (isset($tampil)) {
    echo "NIM Anda = $NIM";
}
?>
            </pre>
        </form>
    </BODY>
</HTML>
