<!-- Guarda el  Tipo -->
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
$desc_tip = mysqli_real_escape_string($link, $_POST['desc_tip']);
$valo_tip = mysqli_real_escape_string($link, $_POST['valo_tip']);
$codi_tip = mysqli_real_escape_string($link, $_POST['codi_tip']);

$sql = "UPDATE tipo SET 
        desc_tip = '$desc_tip',
        valo_tip = '$valo_tip'
        WHERE codi_tip = '$codi_tip'";

mysqli_query($link, $sql);
mysqli_close($link);
?>

</head>
<body bgcolor="#E6E8FA" onload="javascript:cargar()">


</form>
</body>
</HTML>
