<!-- Guarda los Premios -->
<HTML>
<head>
<title>Guarda Premios</title>
<Script Language="JavaScript">
function cargar() {
   window.open('cse_plan1.php','fr03','');
}
</Script>
<?php
//Aqui cargo las funciones 
include("funciones.php");
$link=conectarbd();
$desc_pre = mysqli_real_escape_string($link, $_POST['desc_pre']);

$sql = "INSERT INTO premio ( desc_pr) VALUES ( '$desc_pre')";

mysqli_query($link, $sql);
mysqli_close($link);
?>
<script language='javascript'>
  alert("registro"+<?echo pg_affected_rows();?>);
</script>
</head>
<body onload="javascript:cargar()">

</form>
</body>
</HTML>
