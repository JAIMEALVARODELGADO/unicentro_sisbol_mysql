<!-- Guarda los Premios -->
<HTML>
<head>
<title>Guarda Barrio</title>
<Script Language="JavaScript">
function cargar() {
   window.open('cse_barrio1.php','fr03','');
}
</Script>
<?php
//Aqui cargo las funciones 
include("funciones.php");
$link=conectarbd();
$comuna_bar = mysqli_real_escape_string($link, $_POST['comuna_bar']);
$nombre_bar = mysqli_real_escape_string($link, $_POST['nombre_bar']);

$sql = "INSERT INTO barrio (comuna_bar, nombre_bar) VALUES ('$comuna_bar', '$nombre_bar')";

mysqli_query($link, $sql);
mysqli_close($link);
?>
<script language='javascript'>
  alert("registro"+<?echo mysql_affected_rows();?>);
</script>
</head>
<body onload="javascript:cargar()">

</form>
</body>
</HTML>
