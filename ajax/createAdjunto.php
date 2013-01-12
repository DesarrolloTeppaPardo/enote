<?php 

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require("../classes/main.class.php");

if ( isset($_GET['notaId']) && !empty($_GET['notaId'])) 
{
	
		require_once '../google-api-php-client/src/Google_Client.php';
		require_once '../google-api-php-client/src/contrib/Google_DriveService.php';
		require_once '../google-api-php-client/src/contrib/Google_Oauth2Service.php';
		
		session_start();
		
		$allowedExtensions = NULL;
		$sizeLimit = NULL;
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload('../uploads/',$uploader->getName()); 
		
		$parentId = fabricaDao::getInstance()->createNotaDao()->getGoogleDriveIdById($_GET['notaId']);
		
		$adjunto = new adjunto();

		$adjunto->setTitulo($uploader->getName());
		$adjunto->setNotaId($_GET['notaId']);
		$adjunto->setGoogleDriveId('');
		

		$adjuntoDao = fabricaDao::getInstance()->createAdjuntoDao();
		
		$adjuntoDao->save($adjunto);
		
		$lastId = $adjuntoDao->getLastIdInserted();
		
		$googleDriveId = fabricaGoogleDrive::getInstance()->createAdjuntoGoogleDrive()->uploadFile($adjunto->getTitulo(),$parentId);
		
		$linkDescarga = fabricaGoogleDrive::getInstance()->createAdjuntoGoogleDrive()->getLinkDescarga($googleDriveId);
		
		$adjunto->setId($lastId);
		$adjunto->setGoogleDriveId($googleDriveId);
		$adjunto->setLinkDescarga($linkDescarga);
		
		$adjuntoDao->save($adjunto);
		
		//exit( json_encode( array('ok' => 1) ) );
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

}
else 
{
	$log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	$log->LogInfo("ajax/createAdjunto: Campos Vacios");
	exit( json_encode( array('error' => 'los campos no pueden estar vacios') ) );
}

?>