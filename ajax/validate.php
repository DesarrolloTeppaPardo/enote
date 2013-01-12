<?php 

session_start();

require("../classes/main.class.php");

$log = new KLogger ( "../errores.log" , KLogger::DEBUG );

if ( isset($_POST['usuario']) && !empty($_POST['usuario']) && isset($_POST['clave']) && !empty($_POST['clave']))
{
	if (isset($_POST['accion']) && !empty($_POST['accion']))
	{
		$usuarioDao = fabricaDao::getInstance()->createUsuarioDao();

		if ($_POST['accion'] == 'sesion')
			return $usuarioDao->login($_POST['usuario'],$_POST['clave']);			
		
		else if ($_POST['accion'] == 'registro')
			return $usuarioDao->validarRegistro($_POST['usuario'],$_POST['clave']);
		else
		{
			$log->LogInfo("ajax/validate: accion no valida");
			exit( json_encode( array('error' => 'accion no valida') ) );
		}
	}
	else
	{
		$log->LogInfo("ajax/validate: la accion debe estar seteada y no puede estar vacia");
		exit( json_encode( array('error' => 'la accion debe estar seteada y no puede estar vacia') ) );
	}
}
else
{
	$log->LogInfo("ajax/validate: Campos Vacios");
	exit( json_encode( array('error' => 'los campos no pueden estar vacios') ) );
}
?>