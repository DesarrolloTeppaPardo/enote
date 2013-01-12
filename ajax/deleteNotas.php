<?php 

require("../classes/main.class.php");

if ( isset($_POST['values']) && !empty($_POST['values']) ) 
{		
		require_once '../google-api-php-client/src/Google_Client.php';
		require_once '../google-api-php-client/src/contrib/Google_DriveService.php';
		require_once '../google-api-php-client/src/contrib/Google_Oauth2Service.php';
		
		session_start();
		
		for($i = 0; $i < count($_POST['values']); $i++)
		{
			$nota = fabricaDao::getInstance()->createNotaDao()->getObject($_POST['values'][$i]);
			if($nota != NULL)
				fabricaGoogleDrive::getInstance()->createNotaGoogleDrive()->deleteFolder($nota->getGoogleDriveId());
		}
		
		fabricaDao::getInstance()->createNotaDao()->deleteObjects($_POST['values']);
		
		exit( json_encode( array('ok' => 1) ) );
}
else 	
{
	$log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	$log->LogInfo("ajax/deleteNotas: los campos no pueden estar vacios");
	exit( json_encode( array('error' => 'los campos no pueden estar vacios') ) );
}

?>