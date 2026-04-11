<!-- Valida la campaña activa -->
<?php
//Aqui consulto la campaña
$concampa = "SELECT id_camp, nombre_camp FROM campania WHERE estado_camp='A'";
$concampa = mysqli_query($link, $concampa);

if (mysqli_num_rows($concampa) > 1) {
    echo "<script>alert('Existe mas de una campaña activa');</script>";
    exit();
}
if (mysqli_num_rows($concampa) == 0) {
    echo "<script>alert('NO existe una campaña activa');</script>";
    exit();
}

$rowcamp = mysqli_fetch_array($concampa);
$id_camp    = $rowcamp['id_camp'];
$nombre_camp = $rowcamp['nombre_camp'];
?>
