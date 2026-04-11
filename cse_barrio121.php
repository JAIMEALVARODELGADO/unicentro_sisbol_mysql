<!-- Guarda la Edicion del  Premio -->
<HTML>
<head>
<title>Guarda la Edicion del Premio</title>
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
$id_barrio  = mysqli_real_escape_string($link, $_POST['id_barrio']);

$sql = "UPDATE barrio SET 
        comuna_bar = '$comuna_bar',
        nombre_bar = '$nombre_bar'
        WHERE id_barrio = '$id_barrio'";

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
