<?php 

require("../classes/main.class.php");

if ( isset($_POST['nombre']) && !empty($_POST['nombre']) ) 
{		
		session_start();
		
		$etiqueta = new etiqueta();
		
		$etiqueta->setNombre($_POST['nombre']);
		$etiqueta->setUsuarioId($_SESSION['userId']);
		
		$etiquetaDao = fabricaDao::getInstance()->createEtiquetaDao();
		
		$etiquetaDao->save($etiqueta);
		
		exit( json_encode( array('ok' => 1) ) );
}
else 
{
	$log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	$log->LogInfo("ajax/createEtiqueta: El nombre no puede estar vacio");
	exit( json_encode( array('error' => 'El nombre no puede estar vacio') ) );
}

?>