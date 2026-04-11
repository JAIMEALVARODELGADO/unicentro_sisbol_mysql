<!-- Guarda la Edicion de la entidad -->
<HTML>
<head>
<title>Guarda Edicion de la Entidad</title>
<Script Language="JavaScript">
function cargar() {
  window.open("cse_enti1.php","fr03");
}
</Script>
</head>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<?php
//Aqui cargo las funciones
include("funciones.php");
$fini_sor=cambiafecha($_POST['fini_sor']);
$ffin_sor=cambiafecha($_POST['ffin_sor']);
$link=conectarbd();
$nit_ent  = mysqli_real_escape_string($link, $_POST['nit_ent']);
$nomb_ent = mysqli_real_escape_string($link, $_POST['nomb_ent']);
$valxb_ent = mysqli_real_escape_string($link, $_POST['valxb_ent']);
$fini_sor = mysqli_real_escape_string($link, $fini_sor);
$ffin_sor = mysqli_real_escape_string($link, $ffin_sor);

$sql_ = "UPDATE entidad SET 
         nit_ent   = '$nit_ent',
         nomb_ent  = '$nomb_ent',
         valxb_ent = '$valxb_ent',
         fini_sor  = '$fini_sor',
         ffin_sor  = '$ffin_sor'";

mysqli_query($link, $sql_);
mysqli_close($link);
?>
<body onload='javascript:cargar()'>
</body>
</HTML>
