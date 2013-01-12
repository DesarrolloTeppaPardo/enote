<?php 

class adjuntoGoogleDrive
{
	private $log;
	
	public function __construct()
	{
		
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
		
		$usuario = fabricaDao::getInstance()->createUsuarioDao()->getObject($_SESSION['userId']);
		
		if ($usuario->isAccessTokenExpired())
		{	
			$this->log->LogInfo("adjuntoGoogleDrive.class - Access Token Expired");
			googleDrive::getGoogleDrive()->refreshToken($usuario->getRefreshToken());
			$this->saveUserInfo(true);
		}
		else
			googleDrive::getGoogleDrive()->setAccessToken($_SESSION['access_token']);
	}
	// RECIBE EL NOMBRE Y ID DEL ADJUNTO PARA SUBIRLO Y GUARDARLO
	public function uploadFile($title,$parentId)
	{ 
		$file = googleDrive::getGoogleDrive()->getDriveFile();
		
		$file->setTitle($title);
		
		$parent = googleDrive::getGoogleDrive()->getParentReference();
		$parent->setId($parentId);
		$file->setParents(array($parent));
		
		try 
		{
			$data = file_get_contents('../uploads/'.$title);
			$createdFile = googleDrive::getGoogleDrive()->getService()->files->insert($file, array('data' => $data));
			$this->log->LogInfo("adjuntoGoogleDrive.class - uploadFile: SUCCESS".$createdFile->getId());
			return $createdFile->getId();
		} 
		catch (Exception $e) 
		{
			$this->log->LogError("adjuntoGoogleDrive.class - uploadFile".$e->getMessage());
			print "An error occurred: " . $e->getMessage();
		}
	}
	//RETORNA EL OBJETO REFERENTE AL LINK PARA PODER DESCARGAR EL ADJUNTO 
	public function getLinkDescarga($id)
	{
		$file = googleDrive::getGoogleDrive()->getService()->files->get($id);
		$this->log->LogInfo("adjuntoGoogleDrive.class - getLinkDescarga: SUCCESS");
		return $file->getWebContentLink();
	}
	
	public function deleteFile($id)
	{
		googleDrive::getGoogleDrive()->getService()->files->delete($id);
		$this->log->LogInfo("adjuntoGoogleDrive.class - deleteFile: SUCCESS");
	}

	private function saveUserInfo($refresh = false)
	{	
		$googleDriveId = googleDrive::getGoogleDrive()->getUserInfo();
		
		$_SESSION['userGoogleDriveId'] = $googleDriveId->id;
		
		$this->log->LogInfo("adjuntoGoogleDrive.class - saveUserInfo ".$_SESSION['userGoogleDriveId']);
		
		$accessToken = json_decode(googleDrive::getGoogleDrive()->getAccessToken());
		
		$Usuario = fabricaDao::getInstance()->createUsuarioDao()->getObject($_SESSION['userId']);
		
		$Usuario->setGoogleDriveId($googleDriveId->id);
		
		if(!$refresh)
			$Usuario->setRefreshToken($accessToken->refresh_token);
		
		$Usuario->setAccessToken($accessToken->access_token);
		$Usuario->setCreated($accessToken->created);
		$Usuario->setExpiresIn($accessToken->expires_in);
		
		fabricaDao::getInstance()->createUsuarioDao()->save($Usuario);
	}
}


?>