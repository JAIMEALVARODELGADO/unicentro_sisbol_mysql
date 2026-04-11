<!-- Guarda las Clientes -->
<HTML>
<head>
<title>Guarda Clientes</title>
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
<form name='form1' action='cse_ccompra2.php' method='post' target='fr03'>
<?php
//Aqui cargo las funciones 
include("funciones.php");
$link=conectarbd();
if(!empty($_POST['fnac_cli'])){
  $fnac_cli=cambiafecha($_POST['fnac_cli']);
}
else{
  $fnac_cli='0000-00-00';
}
$hoy=cambiafecha(hoy());
//$puntos=floor($valo_com/1000);
$consulta="SELECT codi_cli FROM cliente WHERE tpid_cli='$_POST[tpid_cli]' and nrod_cli='$_POST[nrod_cli]'";
$consulta=mysqli_query($link,$consulta);
if(mysqli_num_rows($consulta)==0){
  $tpid    = mysqli_real_escape_string($link, $_POST['tpid_cli']   ?? '');
  $nrod    = mysqli_real_escape_string($link, $_POST['nrod_cli']   ?? '');
  $exped   = mysqli_real_escape_string($link, $_POST['exped_cli']  ?? '');
  $nomb    = mysqli_real_escape_string($link, $_POST['nomb_cli']   ?? '');
  $apel    = mysqli_real_escape_string($link, $_POST['apel_cli']   ?? '');
  $dire    = mysqli_real_escape_string($link, $_POST['dire_cli']   ?? '');
  $tele    = mysqli_real_escape_string($link, $_POST['tele_cli']   ?? '');
  $sexo    = mysqli_real_escape_string($link, $_POST['sexo_cli']   ?? '');
  $emai    = mysqli_real_escape_string($link, $_POST['emai_cli']   ?? '');
  $prof    = mysqli_real_escape_string($link, $_POST['prof_cli']   ?? '');
  $barrio  = mysqli_real_escape_string($link, $_POST['id_barrio']  ?? '');

  $sql = "INSERT INTO cliente 
              (tpid_cli, nrod_cli, exped_cli, nomb_cli, apel_cli, 
              dire_cli, tele_cli, fnac_cli, sexo_cli, emai_cli, prof_cli, id_barrio)
          VALUES 
              ('$tpid','$nrod','$exped','$nomb','$apel',
              '$dire','$tele','$fnac_cli','$sexo','$emai','$prof','$barrio')";

  $res = mysqli_query($link, $sql);

  if ($res) {
      $codi_cli = mysqli_insert_id($link); // ✅ ID del registro insertado
  } else {
      die("Error en INSERT: " . mysqli_error($link));
  }
  echo "<input type='hidden' name='tpid_cli' value='$_POST[tpid_cli]'>";
  echo "<input type='hidden' name='nrod_cli' value='$_POST[nrod_cli]'>";
}
else{
  //$codi_cli=$codigo;
  echo "<input type='hidden' name='codi_cli' value='$_POST[codigo]'>";
}
mysqli_free_result($consulta);
mysqli_close($link);
?>

</form>
</body>
</HTML>
<script language='javascript'>cargar()</script>
