<?php 

require("../classes/main.class.php");

if ( isset($_POST['nombre']) && !empty($_POST['nombre']) ) 
{		
		require_once '../google-api-php-client/src/Google_Client.php';
		require_once '../google-api-php-client/src/contrib/Google_DriveService.php';
		require_once '../google-api-php-client/src/contrib/Google_Oauth2Service.php';
		
		session_start();
		
		$agenda = new agenda();
		
		$agenda->setNombre($_POST['nombre']);
		$agenda->setUsuarioId($_SESSION['userId']);
		$agenda->setGoogleDriveId('');
		
		$agendaDao = fabricaDao::getInstance()->createAgendaDao();
		
		$agendaDao->save($agenda);
		
		$lastId = $agendaDao->getLastIdInserted();
		
		$googleDriveId = fabricaGoogleDrive::getInstance()->createAgendaGoogleDrive()->createFolder('Agenda_'.$_POST['nombre'].'_'.$lastId);
		
		$agenda->setId($lastId);
		$agenda->setGoogleDriveId($googleDriveId);
		
		$agendaDao->save($agenda);
		
		exit( json_encode( array('ok' => 1) ) );
}
else 
{
	$log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	$log->LogInfo("ajax/createAgenda: El nombre no puede estar vacio");
	exit( json_encode( array('error' => 'El nombre no puede estar vacio') ) );
}

?>