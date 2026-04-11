<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>

<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>SisBol</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>

<br>
<table class='Tbl0' width='100%'>
  <tr>
    <td class='Td1' width='100%'>MENU</td>
  </tr>
</table>

<table class='Tbl0' width='100%'>
<?php
//Aqui cargo las funciones 
include("funciones.php");
$link = conectarbd();

$consultatp = "SELECT tipo_ucs FROM u_cliseb WHERE codi_ucs='" . mysqli_real_escape_string($link, $_SESSION['Gcodi_ucs']) . "'";

$consultatp = mysqli_query($link, $consultatp);
$rowtp = mysqli_fetch_array($consultatp);
$tipo_ucs = $rowtp['tipo_ucs'];

$consulta = "SELECT codi_men, desc_men FROM menu WHERE nive_men='1'";

$consulta = mysqli_query($link, $consulta);

while ($row = mysqli_fetch_array($consulta)) {
    echo "<tr>";
    echo "<td class='Td1' width='100%'>{$row['desc_men']}</td>";
    echo "</tr>";

    if ($tipo_ucs == '1') {
        $consulta2 = "SELECT codi_men, desc_men, url_men FROM menu 
                      WHERE nive_men='2' AND depe_men='{$row['codi_men']}'";
        $consulta2 = mysqli_query($link, $consulta2);
    } else {
        $consulta2 = "SELECT m.codi_men, m.desc_men, m.url_men FROM menu AS m
                      INNER JOIN um_cliseb AS um ON um.codi_men = m.codi_men
                      INNER JOIN u_cliseb AS u ON u.codi_ucs = um.codi_ucs
                      WHERE m.nive_men='2' 
                        AND um.codi_ucs='" . mysqli_real_escape_string($link, $_SESSION['Gcodi_ucs']) . "' 
                        AND m.depe_men='{$row['codi_men']}'";
        $consulta2 = mysqli_query($link, $consulta2);
    }

    while ($row2 = mysqli_fetch_array($consulta2)) {
        echo "<tr><td class='Th1' width='100%'>
                <a href='{$row2['url_men']}' target='fr03'>{$row2['desc_men']}</a>
              </td></tr>";
    }
    mysqli_free_result($consulta2);
}

mysqli_free_result($consultatp);
mysqli_free_result($consulta);
mysqli_close($link);
?>
</table>
</body>
</html>
