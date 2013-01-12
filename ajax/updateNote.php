<?php 

require("../classes/main.class.php");

$log = new KLogger ( "../errores.log" , KLogger::DEBUG );

if(isset($_POST['id']) && !empty($_POST['id']))
{
	if ( isset($_POST['titulo']) && !empty($_POST['titulo']) && isset($_POST['texto']) && !empty($_POST['texto']) ) 
	{		
		require_once '../google-api-php-client/src/Google_Client.php';
		require_once '../google-api-php-client/src/contrib/Google_DriveService.php';
		require_once '../google-api-php-client/src/contrib/Google_Oauth2Service.php';
		
		session_start();
		
		$etiquetas = $_POST['etiquetas'];
		
		$nota = fabricaDao::getInstance()->createNotaDao()->getObject($_POST['id']);
			
		if($nota == NULL)
		{
			$log->LogInfo("ajax/updateNote: No existe la nota");
			exit( json_encode( array('error' => 'No existe la nota') ) );
		}
		
		$googleDriveId = $nota->getGoogleDriveId();
		
		$nota->setTitulo($_POST['titulo']);
		$nota->setTexto($_POST['texto']);
		
		fabricaDao::getInstance()->createNotaDao()->save($nota);
		
		$etiquetaDao = fabricaDao::getInstance()->createEtiquetaDao();
		
		$etiquetaDao->deleteNotaEtiquetas($_POST['id']);
		
		for($i = 0; $i < count($etiquetas); $i++)
		{
			$etiquetaId = $etiquetaDao->getEtiquetaUsuarioId($_SESSION['userId'],$etiquetas[$i]);
			
			if($etiquetaId == NULL)
			{
				$etiqueta = fabricaDominio::getInstance()->createEtiqueta();
				$etiqueta->setNombre($etiquetas[$i]);
				$etiqueta->setUsuarioId($_SESSION['userId']);
				$etiquetaDao->save($etiqueta);
				$etiquetaId = $etiquetaDao->getLastIdInserted();
			}
			
			$etiquetaDao->insertEtiquetaNota($_POST['id'],$etiquetaId);
		}
			
		
		$title = 'Nota_'.$_POST['titulo'].'_'.$_POST['id'];
		fabricaGoogleDrive::getInstance()->createNotaGoogleDrive()->updateFolder($googleDriveId,$title);
		
		exit( json_encode( array('ok' => 1) ) );	
	}
	else 
		exit( json_encode( array('error' => 'los campos no pueden estar vacios') ) );
}
else
	$log->LogInfo("ajax/updateNote: el id de la nota no puede estar vacio");

?>