<?php
session_start();
$_SESSION['Gcodi_ucs']='';
?>
<!-- Aqui se definen los frames para la búsqueda del usuarios y de la solicitud -->
<HTML>
<HEAD>
<title>SisBol</title>
<link rel="shorcut icon" type="image/x icon" href="img/logosisbol.png">
<script languaje='javascript'>
function validar(){
  alert("Acceso Denegado");
  window.open("index.php");
  window.close();
}
</script>
</HEAD>
<?php

// Aquí cargo las funciones
include("funciones.php");
$link = conectarbd();

$clave = md5($_POST['clave']);
$usuario = mysqli_real_escape_string($link, $_POST['usuario']);

$consulta = "SELECT codi_ucs, logi_ucs, clav_ucs, tipo_ucs 
             FROM u_cliseb 
             WHERE logi_ucs='$usuario' 
             AND clav_ucs='$clave' 
             AND esta_ucs='A'";


$resultado = mysqli_query($link, $consulta);

if (mysqli_num_rows($resultado) == 1) {
    $row = mysqli_fetch_array($resultado);
    $_SESSION['Gcodi_ucs'] = $row['codi_ucs'];
    ?>
    <FRAMESET rows="15%,*" framespacing="0" border="1" frameborder="0"> 
        <FRAME SRC=cse_top.php NAME=fr01>
        <FRAMESET cols="15%,*" framespacing="0" border="1" frameborder="0"> 
            <FRAME SRC=cse_left2.php NAME=fr02>
            <FRAME SRC=cse_fondo.html NAME=fr03>
        </FRAMESET><noframes></noframes> 
    </FRAMESET><noframes></noframes>
    <?php
} else {
    ?>
    <script language='javascript'>
        validar();
    </script>
    <?php
}

mysqli_free_result($resultado);
mysqli_close($link);
?>
</html>
