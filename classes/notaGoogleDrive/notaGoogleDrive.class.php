<?php 

class notaGoogleDrive
{
	private $log;
	
	public function __construct()
	{
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
		
		$usuario = fabricaDao::getInstance()->createUsuarioDao()->getObject($_SESSION['userId']);
		
		if ($usuario->isAccessTokenExpired())
		{	
			$this->log->LogInfo("notaGoogleDrive.class - Access Token Expired");	
			googleDrive::getGoogleDrive()->refreshToken($usuario->getRefreshToken());
			$this->saveUserInfo(true);
		}
		else
			googleDrive::getGoogleDrive()->setAccessToken($_SESSION['access_token']);
	}
	
	public function createFolder($title,$parentId)
	{ 
		$file = googleDrive::getGoogleDrive()->getDriveFile();
		
		$file->setTitle($title);
		$file->setMimeType('application/vnd.google-apps.folder');
		
		$parent = googleDrive::getGoogleDrive()->getParentReference();
		$parent->setId($parentId);
		$file->setParents(array($parent));
		
		$createdFolder = googleDrive::getGoogleDrive()->getService()->files->insert($file);
		$this->log->LogInfo("notaGoogleDrive.class - createFolder: SUCCESS".$title);
		
		return $createdFolder->id;
	}
	
	public function deleteFolder($id)
	{
		googleDrive::getGoogleDrive()->getService()->files->delete($id);
		$this->log->LogInfo("notaGoogleDrive.class - deleteFolder: SUCCESS");
	}
	
	public function updateFolder($id,$title)
	{
		$file = googleDrive::getGoogleDrive()->getService()->files->get($id);
		$file->setTitle($title);
		$updatedFile = googleDrive::getGoogleDrive()->getService()->files->update($id, $file);
		$this->log->LogInfo("notaGoogleDrive.class - updateFolder: SUCCESS ".$id);
	}
	
	private function saveUserInfo($refresh = false)
	{	
		$googleDriveId = googleDrive::getGoogleDrive()->getUserInfo();
		
		$_SESSION['userGoogleDriveId'] = $googleDriveId->id;
		
		$this->log->LogInfo("notaGoogleDrive.class - saveUserInfo ".$_SESSION['userGoogleDriveId']);
		
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