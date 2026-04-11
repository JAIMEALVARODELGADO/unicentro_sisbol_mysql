<!-- Guarda la Edicion de la Campa�a -->
<HTML>
<head>
<title>Guarda Edicion de la Campa�a</title>
<Script Language="JavaScript">
function cargar(id_) {
  window.open("cse_campania1.php?id_camp="+id_,"fr03");
}
</Script>
</head>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<?php
//Aqui cargo las funciones 
include("funciones.php");
$link=conectarbd();
$nombre_camp      = mysqli_real_escape_string($link, $_POST['nombre_camp']);
$mecanica_camp    = mysqli_real_escape_string($link, $_POST['mecanica_camp']);
$fechafin_camp    = mysqli_real_escape_string($link, $_POST['fechafin_camp']);
$actividad_camp   = mysqli_real_escape_string($link, $_POST['actividad_camp']);
$numpersonas_camp = mysqli_real_escape_string($link, $_POST['numpersonas_camp']);
$valor_camp       = mysqli_real_escape_string($link, $_POST['valor_camp']);
$estado_camp      = mysqli_real_escape_string($link, $_POST['estado_camp']);
$id_camp          = mysqli_real_escape_string($link, $_POST['id_camp']);

$sql = "UPDATE campania SET 
        nombre_camp      = '$nombre_camp',
        mecanica_camp    = '$mecanica_camp',
        fechafin_camp    = '$fechafin_camp',
        actividad_camp   = '$actividad_camp',
        numpersonas_camp = '$numpersonas_camp',
        valor_camp       = '$valor_camp',
        estado_camp      = '$estado_camp'
        WHERE id_camp    = '$id_camp'";

mysqli_query($link, $sql);
mysqli_close($link);

echo "<body onload='javascript:cargar(\"$_POST[id_camp]\")'>";

?>
</body>
</HTML>
