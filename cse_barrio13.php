<!-- Guarda la Edicion del  Premio -->
<HTML>
<head>
<title>Elimina Barrio</title>
<Script Language="JavaScript">
function cargar() {
   window.open('cse_barrio1.php','fr03','');
}
</Script>
<?php
//Aqui cargo las funciones 
include("funciones.php");
$link=conectarbd();

$id_barrio = mysqli_real_escape_string($link, $_GET['id_barrio']);

$consulta = "SELECT id_barrio FROM cliente WHERE id_barrio='$id_barrio'";
$consulta = mysqli_query($link, $consulta);

if (mysqli_num_rows($consulta) == 0) {
    $sql = "DELETE FROM barrio WHERE id_barrio='$id_barrio'";
    mysqli_query($link, $sql);
}

mysqli_close($link);
?>

</head>
<body onload="javascript:cargar()">

</form>
</body>
</HTML>
