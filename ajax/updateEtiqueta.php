<?php 

require("../classes/main.class.php");

$log = new KLogger ( "../errores.log" , KLogger::DEBUG );

if(isset($_POST['id']) && !empty($_POST['id']))
{

	if ( isset($_POST['nombre']) && !empty($_POST['nombre'])) 
	{		
			session_start();
			
			$etiqueta = fabricaDao::getInstance()->createEtiquetaDao()->getObject($_POST['id']);
			
			if($etiqueta == NULL)
			{
				$log->LogInfo("ajax/updateEtiqueta: No existe la etiqueta");
				exit( json_encode( array('error' => 'No existe la etiqueta') ) );
			}
			
			$etiqueta->setNombre($_POST['nombre']);
			
			fabricaDao::getInstance()->createEtiquetaDao()->save($etiqueta);
			
			exit( json_encode( array('ok' => 1) ) );
	}
	else 
	{
		$log->LogInfo("ajax/updateEtiqueta: El nombre no puede estar vacio");
		exit( json_encode( array('error' => 'El nombre no puede estar vacio') ) );
	}
}
else
	$log->LogInfo("ajax/updateEtiqueta: el id de la etiqueta no puede estar vacio");

?>