<?php
require_once "funciones.php";
$link=conectarbd();

$q = strtoupper($_GET["q"]);
if (!$q) RETURN;
$q   = mysqli_real_escape_string($link, $q);
$sql = "SELECT DISTINCT id_barrio, descripcion FROM vw_barrio WHERE descripcion LIKE '%$q%'";

$rsd = mysqli_query($link, $sql);
if ($rsd) {
    while ($rs = mysqli_fetch_row($rsd)) {
        $id_   = $rs[0];
        $cname = $rs[1];
        echo "$cname|$id_\n";
    }
}
?>
<p><font color="#000000">no encontrado</font></p>
