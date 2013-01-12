<?php 

require("../classes/main.class.php");

$log = new KLogger ( "../errores.log" , KLogger::DEBUG );

if(isset($_POST['id']) && !empty($_POST['id']))
{

	if ( isset($_POST['nombre']) && !empty($_POST['nombre'])) 
	{		
			require_once '../google-api-php-client/src/Google_Client.php';
			require_once '../google-api-php-client/src/contrib/Google_DriveService.php';
			require_once '../google-api-php-client/src/contrib/Google_Oauth2Service.php';
			
			session_start();
			
			$agenda = fabricaDao::getInstance()->createAgendaDao()->getObject($_POST['id']);
			
			if($agenda == NULL)
			{
				$log->LogInfo("ajax/updateAgenda: No existe la agenda");
				exit( json_encode( array('error' => 'No existe la agenda') ) );
			}
			
			$googleDriveId = $agenda->getGoogleDriveId();
			
			$agenda->setNombre($_POST['nombre']);
			
			fabricaDao::getInstance()->createAgendaDao()->save($agenda);
			
			$title = 'Agenda_'.$_POST['nombre'].'_'.$_POST['id'];
			fabricaGoogleDrive::getInstance()->createAgendaGoogleDrive()->updateFolder($googleDriveId,$title);
			
			exit( json_encode( array('ok' => 1) ) );
	}
	else 
	{
		$log->LogInfo("ajax/updateAgenda: El nombre no puede estar vacio");
		exit( json_encode( array('error' => 'El nombre no puede estar vacio') ) );
	}
}
else
	$log->LogInfo("ajax/updateAgenda: el id de la agenda no puede estar vacio");

?>