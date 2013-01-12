<?php 

class usuarioGoogleDrive
{
	//private static $instance;
	private $log;
	
	public function __construct($usuario = NULL)
	{
		if($usuario == NULL)
			session_start();
		
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
		
		if ( !isset($_SESSION['userId']) && $usuario == NULL)
			header( 'Location: index.php' ) ;
		
		if ($usuario == NULL)
			$usuario = fabricaDao::getInstance()->createUsuarioDao()->getObject($_SESSION['userId']);
		else
			$usuario = $usuario;
		
		if ($usuario->getGoogleDriveId() == 'NULL')
			$this->authenticate();
		else if ($usuario->isAccessTokenExpired())
		{	
			$this->log->LogInfo("usuarioGoogleDrive.class - Access Token Expired");
			googleDrive::getGoogleDrive()->refreshToken($usuario->getRefreshToken());
			$this->saveUserInfo(true);
		}
		else if ($usuario == NULL)
			googleDrive::getGoogleDrive()->setAccessToken($_SESSION['access_token']);
	}
	
	/*
	public static function getInstance()
	{
		if (!self::$instance)
			self::$instance = new self;
			
		return self::$instance;
	}
	*/
	
	public function authenticate()
	{
		if (isset($_GET['error']) && !empty($_GET['error']))
		{
			$this->log->LogInfo("usuarioGoogleDrive.class - authenticate".$_GET['error']);
			exit($_GET['error']);
		}
		
		if (isset($_GET['code']) && !empty($_GET['code']))
			$code = $_GET['code'];
			
		googleDrive::getGoogleDrive()->authenticate($code);
		
		$this->saveUserInfo();
	}
	
	private function saveUserInfo($refresh = false)
	{		
		$googleDriveId = googleDrive::getGoogleDrive()->getUserInfo();
		
		$_SESSION['userGoogleDriveId'] = $googleDriveId->id;
		
		$this->log->LogInfo("usuarioGoogleDrive.class - saveUserInfo ".$_SESSION['userGoogleDriveId']);
		
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
class usuarioDriveException extends CustomException{}
?>