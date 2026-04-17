<?php
$opcion='';

$datos = json_decode(file_get_contents("php://input"), true);
$opcion = $datos['opcion'];
$email  = $datos['email'];

if(isset($_GET['opcion'])){
    $opcion=$_GET['opcion'];
}
//echo $opcion;
switch($opcion){
    case 'tipoIdentificacion':
        consultarTpId();
        break;
    case 'barrio':
        consultarBarrio();
        break;
    case 'enviarCorreo':
        enviarCorreo($email);
        break;
    /*case 'insertar':
        include('insertarCliente.php');
        break;
    case 'eliminar':
        include('eliminarCliente.php');
        break;
    case 'actualizar':
        include('actualizarCliente.php');
        break;
    case 'consultar':
        include('consultarCliente.php');
        break;*/
    default:
        echo "Opción no válida";
}

function consultarTpId(){
    include("funciones.php");
    $link=conectarbd();

    $sql = "SELECT codi_tip,codi_gru,desc_tip,valo_tip FROM tipo 
    WHERE codi_gru = '01'
    ORDER BY desc_tip";
    $result = mysqli_query($link, $sql);
    $datos = []; 
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $datos[] = [
            "codi_tip" => $row['codi_tip'],
            "codi_gru" => $row['codi_gru'],
            "desc_tip" => $row['desc_tip'],
            "valo_tip" => $row['valo_tip']
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($datos);
}

function consultarBarrio(){
    include("funciones.php");
    $link=conectarbd();

    $sql = "SELECT id_barrio,comuna_bar,nombre_bar FROM barrio 
    ORDER BY nombre_bar";
    $result = mysqli_query($link, $sql);
    $datos = []; 
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $datos[] = [
            "id_barrio" => $row['id_barrio'],
            "comuna_bar" => $row['comuna_bar'],
            "nombre_bar" => $row['nombre_bar']
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($datos);
}

function enviarCorreo($email) {
    
    $para    = $email;
    $asunto  = "Confirmación de registro de cliente Unicentro";
    $mensaje = "Código 88958: ¡Gracias por registrarte en Unicentro! Tu cuenta ha sido creada exitosamente. Bienvenido a nuestra comunidad de clientes.";
    $headers = "From: jaimealvarodelgado@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if (mail($para, $asunto, $mensaje, $headers)) {
        echo json_encode([
            "success" => true,
            "email"   => $email,
            "message" => "Correo enviado correctamente"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "email"   => $email,
            "message" => "Error al enviar el correo"
        ]);
    }
}
?>