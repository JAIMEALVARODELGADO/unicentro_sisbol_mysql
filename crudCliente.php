<?php
$opcion='';
if(isset($_GET['opcion'])){
    $opcion=$_GET['opcion'];
}
else{
    $datos = json_decode(file_get_contents("php://input"), true);
    $opcion = $datos['opcion'];
    $email  = $datos['email'];
    if(isset($datos['codigo'])){
        $codigo = $datos['codigo'];
    }
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
        validarCorreo($email);
        break;
    case 'validarCodigo':
        validarCodigo($email, $codigo);
        break;
    case 'insertar':
        insertar($datos);
        break;
    case 'actualizar':
        actualizarCliente($datos);
        break;
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

function validarCorreo($email){
    include("funciones.php");
    $link=conectarbd();

    $sql = "SELECT * FROM cliente WHERE emai_cli = '$email'";
    $result = mysqli_query($link, $sql);
    
    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    } 
    
    enviarCorreo($email,$row);
   
}

function enviarCorreo($email,$cliente) {

    $link=conectarbd();
    //Aqui se valida que el correo de salida esté configurado en la base de datos, si no se encuentra se devuelve un mensaje de error
    $sql = "SELECT correo_salida FROM entidad";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $correo_salida = $row['correo_salida'];
    } else {
        echo json_encode([
            "success" => false,
            "email"   => $email,
            "message" => "No se pudo obtener el correo de salida"
        ]);
        return;
    }
    
    $codigo = random_int(0, 999999);

    $sql="INSERT INTO validation_codes (email_val, code_val, expires_at) 
    VALUES ('$email', '$codigo', DATE_ADD(NOW(), INTERVAL 15 MINUTE))";
    if (!mysqli_query($link, $sql)) {
        echo json_encode([
            "success" => false,
            "email"   => $email,
            "message" => "Error al guardar el código de validación"
        ]);
        return;
    }

    $para    = $email;
    $asunto  = "Confirmación de registro de cliente Unicentro";
    $mensaje = "Código: ".$codigo." ¡Gracias por registrarte en Unicentro! Tu cuenta ha sido creada exitosamente. Bienvenido a nuestra comunidad de clientes.";
    $headers = "From: ".$correo_salida."\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    if (mail($para, $asunto, $mensaje, $headers)) {
        echo json_encode([
            "success" => true,
            "email"   => $email,
            "message" => "Correo enviado correctamente",
            "codigo"  => $codigo,
            "cliente"    => $cliente
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "email"   => $email,
            "message" => "Error al enviar el correo"
        ]);
    }
}

function validarCodigo($email, $codigo) {
    include("funciones.php");
    $link=conectarbd();

    $sql = "SELECT * FROM validation_codes 
    WHERE email_val = '$email' 
    AND code_val = '$codigo' 
    AND expires_at > NOW()
    AND used = 0";

    $result = mysqli_query($link, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        echo json_encode([
            "success" => true,
            "email"   => $email,
            "message" => "Código válido"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "email"   => $email,
            "message" => "Código inválido o expirado"
        ]);
    }
}

function insertar($datos){
    include("funciones.php");
    $link=conectarbd();

    $sql = "INSERT INTO cliente (tpid_cli,nrod_cli,exped_cli,nomb_cli,apel_cli,dire_cli,tele_cli,fnac_cli,sexo_cli,emai_cli,id_barrio) 
    VALUES ('".$datos['tpid_cli']."','".$datos['nrod_cli']."','".$datos['exped_cli']."','".$datos['nomb_cli']."','".$datos['apel_cli']."','".$datos['dire_cli']."','".$datos['tele_cli']."','".$datos['fnac_cli']."','".$datos['sexo_cli']."','".$datos['email']."','".$datos['id_barrio']."')";
    //ECHO $sql;

    if (mysqli_query($link, $sql)) {
        echo json_encode([
            "success" => true,
            "message" => "Cliente insertado correctamente"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al insertar el cliente: " . mysqli_error($link)
        ]);
    }

    $sql="UPDATE validation_codes SET used = 1 WHERE email_val = '".$datos['email']."' AND code_val = '".$datos['codigo']."'";
    mysqli_query($link, $sql);
    mysqli_close($link);
}

function actualizarCliente($datos){
    include("funciones.php");
    $link=conectarbd();

    $sql = "UPDATE cliente SET tpid_cli='".$datos['tpid_cli']."',nrod_cli='".$datos['nrod_cli']."',exped_cli='".$datos['exped_cli']."',nomb_cli='".$datos['nomb_cli']."',apel_cli='".$datos['apel_cli']."',dire_cli='".$datos['dire_cli']."',tele_cli='".$datos['tele_cli']."',fnac_cli='".$datos['fnac_cli']."',sexo_cli='".$datos['sexo_cli']."',emai_cli='".$datos['email']."' 
    WHERE emai_cli = '".$datos['email']."'";
    //echo $sql;
    if (mysqli_query($link, $sql)) {
        echo json_encode([
            "success" => true,
            "message" => "Cliente actualizado correctamente"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al actualizar el cliente: " . mysqli_error($link)
        ]);
    }

    mysqli_close($link);
}
?>