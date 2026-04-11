<?php
session_start();
if(!isset($_SESSION['gcodi_gru'])){$_SESSION['gcodi_gru']='';}
if(!empty($_POST['codi_gru'])){$_SESSION['gcodi_gru']=$_POST['codi_gru'];}
if(isset($_POST['codi_ucs'])){$codi_ucs=$_POST['codi_ucs'];}
if(isset($_GET['codi_ucs'])){$codi_ucs=$_GET['codi_ucs'];}

?>
<!-- Listado de Opciones del Menu -->
<HTML>
<head>
<title>Listado de Opciones del Menu</title>
<meta http-equiv=”Content-Type” content=”text/html; charset=UTF-8″ />
<link rel="stylesheet" href="css/style.css" type="text/css" />
<script language='javascript'>
function permiso(opc_,codi_men,codi_ucs){
  window.open("cse_admderecho111.php?opc_="+opc_+"&codi_men="+codi_men+"&codi_ucs="+codi_ucs,"fr05");
}
</script>
<?php
//Aqui cargo las funciones 
include("funciones.php");
$link=conectarbd();

//echo "<br>".$gcodi_gru;
//echo "<br>".$codi_ucs;
	  
?>
</head>
<body>
<form name='form1' method='post' action=''>
<table class='Tbl0'>
  <tr><td class='Td1' align='center'>Listado de Opciones del Usuario del Sistema</td></tr>
</table>
<table class='Tbl0' width='100%' border='0'>
  <th class='Th0' width='10%'>Seleccionar</th>
  <th class='Th0' width='80%'>Opcion</th>
  <?php
  $codi_ucs = mysqli_real_escape_string($link, $codi_ucs);
  $gcodi_gru = mysqli_real_escape_string($link, $_SESSION['gcodi_gru']);
  
  $consulta = "SELECT m.codi_men, m.desc_men, um.codi_umc 
               FROM menu AS m 
               LEFT JOIN um_cliseb AS um ON um.codi_men = m.codi_men AND um.codi_ucs = '$codi_ucs'
               WHERE m.depe_men = '$gcodi_gru' ORDER BY m.codi_men";
  
  $consulta = mysqli_query($link, $consulta);
  
  while ($row = mysqli_fetch_array($consulta)) {
      echo "<tr>";
      echo "<td class='Td2' align='center'>";
      if ($row['codi_umc'] == NULL) {
          echo "<input type='checkbox' name='chkopc' onclick='permiso(1,{$row['codi_men']},$codi_ucs)'>";
      } else {
          echo "<input type='checkbox' name='chkopc' checked onclick='permiso(2,{$row['codi_men']},$codi_ucs)'>";
      }
      echo "</td>";
      echo "<td class='Td2'>{$row['desc_men']}</td>";
      echo "</tr>";
  }
  ?>
  </table>
  </form>
  <?php
  mysqli_free_result($consulta);
  mysqli_close($link);
?>
</body>
</HTML>