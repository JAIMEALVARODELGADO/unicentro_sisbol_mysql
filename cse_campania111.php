<!-- Guarda las Campañas -->
<HTML>
<head>
<title>Guarda Campañas</title>
</head>
<link rel="stylesheet" href="css/style.css" type="text/css" />

<Script Language="JavaScript">
function cargar() {
    form1.submit();
}
function regresar(){
  history.go(-1);
}
</Script>
<form name='form1' action='cse_campania1.php' method='post' target='fr03'>
<?php
//Aqui cargo las funciones 
include("funciones.php");
$link = conectarbd();

$nombre_camp      = mysqli_real_escape_string($link, $_POST['nombre_camp']);
$mecanica_camp    = mysqli_real_escape_string($link, $_POST['mecanica_camp']);
$fechafin_camp    = mysqli_real_escape_string($link, $_POST['fechafin_camp']);
$actividad_camp   = mysqli_real_escape_string($link, $_POST['actividad_camp']);
$numpersonas_camp = mysqli_real_escape_string($link, $_POST['numpersonas_camp']);
$valor_camp       = mysqli_real_escape_string($link, $_POST['valor_camp']);

$sql = "INSERT INTO campania (nombre_camp, mecanica_camp, fechafin_camp, actividad_camp, numpersonas_camp, valor_camp, numeroboleta_camp)
        VALUES ('$nombre_camp', '$mecanica_camp', '$fechafin_camp', '$actividad_camp', '$numpersonas_camp', '$valor_camp', '1')";

mysqli_query($link, $sql);
$id_camp = mysqli_insert_id($link);

echo "<input type='hidden' name='id_camp' value='$id_camp'>";
mysqli_close($link);

?>

</form>
</body>
</HTML>
<script language='javascript'>cargar()</script>;