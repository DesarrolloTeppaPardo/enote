<?php 

require("../classes/main.class.php");

if ( isset($_POST['titulo']) && !empty($_POST['titulo']) && isset($_POST['texto']) && !empty($_POST['texto']) && isset($_POST['agendaId']) && !empty($_POST['agendaId'])) 
{
		require_once '../google-api-php-client/src/Google_Client.php';
		require_once '../google-api-php-client/src/contrib/Google_DriveService.php';
		require_once '../google-api-php-client/src/contrib/Google_Oauth2Service.php';
		
		session_start();
		
		$etiquetas = $_POST['etiquetas'];
		
		$parentId = fabricaDao::getInstance()->createAgendaDao()->getGoogleDriveIdById($_POST['agendaId']);
			
		$nota = new nota();

		$nota->setTitulo($_POST['titulo']);
		$nota->setTexto($_POST['texto']);
		$nota->setAgendaId($_POST['agendaId']);
		$nota->setGoogleDriveId('');
		

		$notaDao = fabricaDao::getInstance()->createNotaDao();
		
		$notaDao->save($nota);
		
		$lastId = $notaDao->getLastIdInserted();
		
		$etiquetaDao = fabricaDao::getInstance()->createEtiquetaDao();
		
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
			
			$etiquetaDao->insertEtiquetaNota($lastId,$etiquetaId);
		}
		
		$googleDriveId = fabricaGoogleDrive::getInstance()->createNotaGoogleDrive()->createFolder('Nota_'.$_POST['titulo'].'_'.$lastId,$parentId);
		
		$nota->setId($lastId);
		$nota->setGoogleDriveId($googleDriveId);
		
		$notaDao->save($nota);
		
		exit( json_encode( array('ok' => 1) ) );

}
else 
{
	$log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	$log->LogInfo("ajax/createNote: los campos no pueden estar vacios");
	exit( json_encode( array('error' => 'los campos no pueden estar vacios') ) );
}

?>