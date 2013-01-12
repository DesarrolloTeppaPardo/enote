<?php

class googleDrive
{
	/* Object vars */
	private $OAuth2;
	private $Client;
	private $Service;
	private $log;
	
	private static $googleDrive;
	
	//private $MySQL;
		
	private function __construct()
	{	
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
		
		$this->Client = new Google_Client();
			
		$this->Client->setClientId("656636749673.apps.googleusercontent.com");
		$this->Client->setClientSecret("Bw-VW9UaX0kX-Oybw7SYm5RT");
		$this->Client->setRedirectUri("http://localhost/enote/agendas.php");
		$this->Client->setScopes(array('https://www.googleapis.com/auth/drive.file','https://www.googleapis.com/auth/userinfo.profile'));
		
		$this->OAuth2 = new Google_Oauth2Service($this->Client);
		$this->Service 	= new Google_DriveService($this->Client);
	}
	
	public static function getGoogleDrive()
	{
		if (!self::$googleDrive)
			self::$googleDrive = new self;
			
		return self::$googleDrive;
	}
	
	public function authenticate($code)
	{
		$this->Client->setAccessToken($this->Client->authenticate($code));
		$_SESSION['access_token'] = $this->Client->getAccessToken();
		$this->log->LogInfo("googleDrive.class - authenticate");
	}
	
	public function getUserInfo ()
	{
		return $this->OAuth2->userinfo->get();
	}
	
	public function getAccessToken()
	{
		return $this->Client->getAccessToken();
	}
	
	public function setAccessToken($accessToken)
	{
		$this->Client->setAccessToken($accessToken);
	}
	
	public function refreshToken ($refreshToken)
	{	
		$this->Client->refreshToken($refreshToken);
		$_SESSION['access_token'] = $this->Client->getAccessToken();
		$this->log->LogInfo("googleDrive.class - refreshToken");
	}
	
	public function getDriveFile()
	{
		return new Google_DriveFile();
	}
	
	public function getParentReference()
	{
		return new Google_ParentReference();
	}
	
	public function getService()
	{
		return $this->Service;
	}
		
	/*
	
	public function createFolder($title,$parentId = NULL)
	{
			$file = new Google_DriveFile();
			$file->setTitle($title);
			$file->setMimeType('application/vnd.google-apps.folder');
			
			if ($parentId != NULL) {
				$parent = new Google_ParentReference();
				$parent->setId($parentId);
				$file->setParents(array($parent));
			}
			
			$createdFile = $this->Service->files->insert($file);
			
			return $createdFile['id'];
	}
	
	public function deleteFile ($id)
	{
		exit($this->Service->files->delete($id));
	}
	
	*/
}

class googleDriveException extends CustomException{}