<?php
$host = "localhost";   // Ej: 123.45.67.89
//$user = "sisbol_root2";     // Ej: sisbol_root2
$user = "root";    
//$pass = "7Syc2HAdHLLSbnAA";     // La que configuraste en aaPanel
$pass = "";
$db   = "sisbol_unicentro";       // Ej: sisbol_unicentro

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$input = file_get_contents("php://input");

// Convertirlo a arreglo asociativo
$cliente = json_decode($input, true);

// Ahora puedes acceder a cada campo del objeto
/*echo "Código: " . $cliente['codi_cli'] . "<br>";
echo "Nombre: " . $cliente['nomb_cli'] . "<br>";
echo "Apellido: " . $cliente['apel_cli'] . "<br>";
echo "Email: " . $cliente['emai_cli'] . "<br>";*/

$emai_cli = $cliente['emai_cli'];
$nrod_cli = $cliente['nrod_cli'];

$encontratoid=0;
$encontradoemail=0;

if($emai_cli=='NULL' || $emai_cli=='' || $emai_cli==null){
    echo "El campo email está vacío.";
    return;
}
$consultaid="SELECT * FROM cliente where nrod_cli='$nrod_cli'";
$resultadoid = $conn->query($consultaid);
if($resultadoid->num_rows > 0){
    $encontratoid=1;
}

$consulta="SELECT * FROM cliente where emai_cli='$emai_cli'";
//echo $consulta;
$resultado = $conn->query($consulta);
if ($resultado->num_rows > 0) {
    $encontradoemail=1;
}

if($encontradoemail==0 && $encontratoid==0){
    $fecha_creacion = SUBSTR($cliente['fecha_creacion'],0,19);
    $fuco_cli = $cliente['fuco_cli'];
    $pet=$cliente['pet'];
    $terms_conditions=$cliente['terms_conditions'];
    if($cliente['fuco_cli']=='NULL' || $cliente['fuco_cli']=='' || $cliente['fuco_cli']==null){
        $fuco_cli = '1980-01-01 00:00:00';
    }
    if($cliente['terms_conditions']=='NULL' || $cliente['terms_conditions']=='' || $cliente['terms_conditions']==null) {
        $terms_conditions = 0;
    }
    
    if($cliente['pet']=='NULL' || $cliente['pet']=='' || $cliente['pet']==null){
        $pet=0;
    }

    $sql = "INSERT INTO cliente (
        codi_cli,
        tpid_cli,
        nrod_cli,
        exped_cli,
        nomb_cli,
        apel_cli,
        dire_cli,
        tele_cli,
        fnac_cli,
        sexo_cli,
        emai_cli,
        prof_cli,
        punt_cli,
        fuco_cli,
        id_barrio,
        customer_type,
        password,
        id_role,
        pet,
        terms_conditions,
        fecha_creacion)
        VALUES (NULL, '".
        $cliente['tpid_cli']."', '".
        $cliente['nrod_cli']."', '".
        $cliente['exped_cli']."','".
        $cliente['nomb_cli']."', '".
        $cliente['apel_cli']."', '".
        $cliente['dire_cli']."', '".
        $cliente['tele_cli']."', '".
        $cliente['fnac_cli']."', '".
        $cliente['sexo_cli']."', '".
        $cliente['emai_cli']."', '".
        $cliente['prof_cli']."', '".
        $cliente['punt_cli']."', '".
        $fuco_cli."', '".
        $cliente['id_barrio']."', '".
        $cliente['customer_type']."', '".
        $cliente['password']."', '".
        $cliente['id_role']."', '".
        $pet."', '".
        $terms_conditions."', '".
        $fecha_creacion."')";
        //echo $sql;
        if ($conn->query($sql) === TRUE) {
            echo "Cliente registrado exitosamente";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
}

mysqli_free_result($resultado);
mysqli_close($conn);

?>