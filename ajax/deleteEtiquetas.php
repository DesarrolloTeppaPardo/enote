<?php 
require("../classes/main.class.php");

if ( isset($_POST['values']) && !empty($_POST['values']) ) 
{						
		session_start();
		
		fabricaDao::getInstance()->createEtiquetaDao()->deleteObjects($_POST['values']);
		
		exit( json_encode( array('ok' => 1) ) );
}
else 
{
	$log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	$log->LogInfo("ajax/deleteEtiquetas: los campos no pueden estar vacios");
	exit( json_encode( array('error' => 'los campos no pueden estar vacios') ) );
}

?>