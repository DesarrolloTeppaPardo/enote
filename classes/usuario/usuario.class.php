<?php 

class usuario
{
	/* Object vars */
	private $id;
	private $userName;
	private $clave;
	private $googleDriveId;
    private $refreshToken;
    private $accessToken;
	private $created;
	private $expiresIn;
	private $fechaCreacion;
	
	//private static $usuario;
		
	public function __construct()
	{
	
	}
	
	/*
	public static function getSingleton()
	{
		if (!self::$usuario)
			self::$usuario = new self;
			
		return self::$usuario;
	}
	*/
	
	// SET FUNCTIONS
	
	public function setId ($id)
	{
		$this->id = $id;
	}
	
	public function setUserName ($userName)
	{
		$this->userName = $userName;
	}
	
	public function setClave ($clave)
	{
		$this->clave = md5($clave);
	}
	
	public function setClaveXml ($clave)
	{
		$this->clave = $clave;
	}
	
	public function setGoogleDriveId ($googleDriveId)
	{
		$this->googleDriveId = $googleDriveId;
	}
	
	public function setRefreshToken ($refreshToken)
	{
		$this->refreshToken = $refreshToken;
	}
	
	public function setAccessToken ($accessToken)
	{
		$this->accessToken = $accessToken;
	}
	
	public function setCreated ($created)
	{
		$this->created = $created;
	}
	
	public function setExpiresIn ($expiresIn)
	{
		$this->expiresIn = $expiresIn;
	}
	
	public function setFechaCreacion ($fechaCreacion)
	{
		$this->fechaCreacion = $fechaCreacion;
	}
	
	// GET FUNCTIONS
	
	public function getId ()
	{
		return $this->id;
	}
	
	public function getUserName ()
	{
		return $this->userName;
	}
	
	public function getClave ()
	{
		return $this->clave;
	}
	
	public function getGoogleDriveId ()
	{
		return $this->googleDriveId;
	}
	
	public function getRefreshToken ()
	{
		return $this->refreshToken;
	}
	
	public function getAccessToken ()
	{
		return $this->accessToken;
	}
	
	public function getCreated ()
	{
		return $this->created;
	}
	
	public function getExpiresIn ()
	{
		return $this->expiresIn;
	}
	
	public function getFechaCreacion ()
	{
		return $this->fechaCreacion;
	}
	
	public function isAccessTokenExpired()
	{
		if ( time() > ($this->getCreated() + $this->getExpiresIn()) || (!@$_SESSION['access_token']) )
			return true;
		else
			return false;
	}
	
}
	
?>