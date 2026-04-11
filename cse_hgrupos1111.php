<?php
session_start();
?>
<HTML>
<head>
<title>Guarda Tipos</title>
<Script Language="JavaScript">
function cargar() {
var load = window.open('cse_hgrupos11.php','fr03','');
window.opener = top;
window.close();
}
</Script>
<?php
//Aqui cargo las funciones 
include("funciones.php");
$link=conectarbd();
$sql="SELECT desc_tip FROM tipo WHERE desc_tip='$_POST[desc_tip]'";
$gcodi_gru = mysqli_real_escape_string($link, $_SESSION['gcodi_gru']);
$desc_tip  = mysqli_real_escape_string($link, $_POST['desc_tip']);
$valo_tip  = mysqli_real_escape_string($link, $_POST['valo_tip']);

$consulta = mysqli_query($link, $sql);

if (mysqli_num_rows($consulta) == 0) {
    $consulta = "SELECT MAX(codi_tip) AS codi_tip FROM tipo WHERE codi_gru='$gcodi_gru'";
    $consulta = mysqli_query($link, $consulta);
    $row      = mysqli_fetch_array($consulta);

    if (empty($row['codi_tip'])) {
        $codi_tip = $gcodi_gru . '001';
    } else {
        $codi_tip = str_pad($row['codi_tip'] + 1, 5, '0', STR_PAD_LEFT);
    }

    $sql = "INSERT INTO tipo (codi_tip, codi_gru, desc_tip, valo_tip, fijo_tip) 
            VALUES ('$codi_tip', '$gcodi_gru', '$desc_tip', '$valo_tip', 'N')";
    mysqli_query($link, $sql);
}

mysqli_free_result($consulta);
mysqli_close($link);
?>

</head>
<body bgcolor="#E6E8FA" onload="javascript:cargar()">

</form>
</body>
</HTML>
