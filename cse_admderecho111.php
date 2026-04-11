<!-- Inactiva/Activa Derecho a la opcion -->
<HTML>
<head>
<title>Inactiva/Activa Derecho a la Opcion</title>
<Script Language="JavaScript">
function cargar(codi_ucs){
  window.open("cse_admderecho11.php?codi_ucs="+codi_ucs,"fr05");
}
</Script>
</head>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<?php
//Aqui cargo las funciones 
include("funciones.php");
$link=conectarbd();
$opc     = mysqli_real_escape_string($link, $_GET['opc_']);
$codi_men = mysqli_real_escape_string($link, $_GET['codi_men']);
$codi_ucs = mysqli_real_escape_string($link, $_GET['codi_ucs']);

if ($opc == 1) {
    $sql = "INSERT INTO um_cliseb (codi_men, codi_ucs) VALUES ('$codi_men', '$codi_ucs')";
} else {
    $sql = "DELETE FROM um_cliseb WHERE codi_men='$codi_men' AND codi_ucs='$codi_ucs'";
}

mysqli_query($link, $sql);
mysqli_close($link);
?>
<body onload='javascript:cargar(<?php echo $_GET['codi_ucs'];?>)'>
</body>
</HTML>
