<!--Elimina el  Tipo -->
<HTML>
<head>
<title>Elimina Tipos</title>
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
$codi_tip = mysqli_real_escape_string($link, $_GET['codi_tip']);

$sql = "DELETE FROM tipo WHERE codi_tip='$codi_tip'";

mysqli_query($link, $sql);
mysqli_close($link);
?>

</head>
<body onload="javascript:cargar()">


</form>
</body>
</HTML>
