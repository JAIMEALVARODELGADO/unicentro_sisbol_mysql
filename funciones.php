<?php
//Aqui valido que las fechas sean v�lidas
function validafecha($xfecha_)
{
  $xdia_=substr($xfecha_,0,2);
  $xmes_=substr($xfecha_,3,2);
  $xanio_=substr($xfecha_,6,4);
  if ($xdia_*1==0 or $xmes_*1==0 or $xanio_*1==0)
    {return 0;}
  else{
    if (checkdate($xmes_,$xdia_,$xanio_))
      {return 1;}
    else{
      return 0;}
  }
}

//Funcion que retorna la fecha actual en el formato "dd/mm/aaaa"
function hoy()
{
   $hoy_=date("d").'/'.date("m").'/'.date("Y");
   return $hoy_;
}

//Funcion que cambia el formato de la fecha que recibe
//la recibe en formato "dd/mm/yyyy" y la retorna en formato "yyyy/mm/dd"
function cambiafecha($xfecha_)
{
  $xdia_=substr($xfecha_,0,2);
  $xmes_=substr($xfecha_,3,2);
  $xanio_=substr($xfecha_,6,4);
  $xfecha_=$xanio_."/".$xmes_."/".$xdia_;
  return $xfecha_;
}

//Funcion que cambia el formato de la fecha que recibe
//la recibe en formato "yyyy/mm/dd" y la retorna en formato  "dd/mm/yyyy"
function cambiafechadmy($xfecha_)
{
  $xanio_=substr($xfecha_,0,4);
  $xmes_=substr($xfecha_,5,2);
  $xdia_=substr($xfecha_,8,2);

  $xfecha_=$xdia_."/".$xmes_."/".$xanio_;
  return $xfecha_;
}

//Conexion con la base
function conectarbd()
{
  // Datos de conexión
  $host     = 'localhost';
  $puerto   = 3306; // Puerto por defecto de MySQL
  $bd       = 'sisbol_unicentro';
  $usuario  = 'root';
  $password = '';

  // Conexión con mysqli
  $conexion = new mysqli($host, $usuario, $password, $bd, $puerto);

  // Verificar errores de conexión
  if ($conexion->connect_error) {
      die('No se ha podido conectar: ' . $conexion->connect_error);
  }

  // Establecer charset UTF-8
  $conexion->set_charset('utf8');

  return $conexion;
}

//Conexion con la base
function conectarbdg()
{
   mysql_connect("localhost","root");
   //selecci�n de la base de datos con la que vamos a trabajar 
   mysql_select_db("lyr_general");
}

//Funcion que debuelve el estado de las personas en la relacion comercial
function estadocom($xestado_)
{
  switch($xestado_){
    case 'A':
	  $nestado_='Activ@';
	  break;
    case 'I':
	  $nestado_='Inactiv@';
	  break;
    case 'E':
	  $nestado_='Espor�dic@';
	  break;
  }
  return $nestado_;  
}

//Funcion que debuelve el estado de los movimientos de facturaci�n
function estadofac($xestado_)
{
  switch($xestado_){
    case 'A':
	  $nestado_='Activ@';
	  break;
    case 'C':
	  $nestado_='Cerrad@';
	  break;
    case 'N':
	  $nestado_='Anulad@';
	  break;
  }
  return $nestado_;  
}

function linea($col_,$fil_,$cant_,$car_,&$pdf)
{
  for($n=0;$n<$cant_;$n++){
    $pdf->SetXY($col_+$n,$fil_);
	$pdf->Cell(40,5,$car_,0);
  }
}

?>
