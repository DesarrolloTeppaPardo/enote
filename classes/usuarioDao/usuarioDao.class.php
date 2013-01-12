<?php 

interface interfaceUsuarioDao 
{
	public function exist($id);
	
	public function login($usuario,$clave);
	
	public function validarRegistro ($userName,$clave);
	
	public function getIdByGoogleDriveId($googleDriveId);
	
	public function getObject($id);
	
	public function save ($obj);
	
	public function deleteObjects($id);
}

class usuarioDao implements interfaceUsuarioDao
{
	/* Object vars */
	//private static $instance;
	private $table = 'usuario';
	private $log;
		
	public function __construct()
	{
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	}
	
	/*
	public static function getInstance()
	{
		if (!self::$instance)
			self::$instance = new self;
			
		return self::$instance;
	}
	*/
	
	private function sendMessage($message)
	{
		exit(json_encode($message));
	}
	
	public function exist($id)
	{
		$query = "SELECT COUNT(id) AS count FROM {$this->table} WHERE id = {$id};";
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		$Data = mysql_fetch_assoc($Sql);
		
		if($Data['count'] > 0)
			return true;
		else
			return 0;
	}
	
	public function login($usuario,$clave)
	{
		$query = "SELECT id, googleDriveId FROM usuario WHERE usuario = '{$usuario}' AND clave = '".md5($clave)."'"; 
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		$data = mysql_fetch_assoc($Sql);
	
		if (!$data)
		{
			$this->log->LogInfo("usuarioDao.class - Login: Usuario y Clave Invalidos");
			$this->sendMessage(array('error' => 'Usuario y Clave Invalidos'));
		}
			
		$_SESSION['userId'] = $data['id'];
		$_SESSION['userGoogleDriveId'] = $data['googleDriveId'];
		$_SESSION['user'] = $usuario;
		
		$this->log->LogInfo("usuarioDao.class - Login: Credenciales correctas");
		
		$this->sendMessage(array('ok' => 1));
	}
	
	public function validarRegistro ($userName,$clave)
	{
		$query = "SELECT COUNT(usuario) AS usuarios FROM usuario WHERE usuario = '{$userName}'";
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		$Count = mysql_fetch_assoc($Sql);
		
		if ($Count['usuarios'] > 0)
		{
			$this->log->LogInfo("usuarioDao.class - validarRegistro: Ya existe este usuario");
			$this->sendMessage(array('error' => 'Ya existe este usuario'));
		}
		else
		{
			$usuario = fabricaDominio::getInstance()->createUsuario();
			
			$usuario->setUserName($userName);
			$usuario->setClave($clave);
			$usuario->setGoogleDriveId('NULL');
			$usuario->setRefreshToken('NULL');
			$usuario->setAccessToken('NULL');
			$usuario->setCreated('NULL');
			$usuario->setExpiresIn('NULL');
			$this->save($usuario);
			
			$this->sendMessage(array('ok' => 1));
		}
	}
	
	public function getIdByGoogleDriveId($googleDriveId)
	{
		$query = "SELECT id FROM {$this->table} WHERE googleDriveId = '{$googleDriveId}';";
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		if(mysql_num_rows($Sql) == 0)
			return NULL;
		
		$Data = mysql_fetch_assoc($Sql);
		
		return $Data['id'];
	}
	
	public function getObject($id) 
	{
		if($this->exist($id))
		{
			$query = "SELECT * FROM {$this->table} WHERE id = ".$id;
			$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
			$Data = mysql_fetch_assoc($Sql);
			
			$usuario = fabricaDominio::getInstance()->createUsuario();
			
			$usuario->setId($Data['id']);
			$usuario->setUserName($Data['usuario']);
			$usuario->setClaveXml($Data['clave']);
			$usuario->setGoogleDriveId($Data['googleDriveId']);
			$usuario->setRefreshToken($Data['refresh_token']);
			$usuario->setAccessToken($Data['access_token']);
			$usuario->setCreated($Data['created']);
			$usuario->setExpiresIn($Data['expires_in']);
			$usuario->setFechaCreacion($Data['fechaCreacion']);
			
			return $usuario;
		}
		else
		{
			$this->log->LogInfo("usuarioDao.class - getObject: No existe el objeto {$id} en Base de Datos");
			return NULL;
		}
    }
	
	public function save ($obj)
	{
		$id = $obj->getId();
		
		if(empty($id) || !$this->exist($id))
			$this->create($obj);
		else
			$this->update($obj);
	}
	
	private function create($obj)
	{
		$id = $obj->getId();
		
		$query = "INSERT INTO {$this->table} (".((!empty($id)) ? 'id, ' : '')."usuario, clave, googleDriveId, refresh_token, access_token, created, expires_in, fechaCreacion) VALUES (";
		
		if(!empty($id))
			$query .= $obj->getId().", ";
		
		$query .= "'".$obj->getUserName()."', ";
		$query .= "'".$obj->getClave()."', ";
		$query .= "'".$obj->getGoogleDriveId()."', ";
		$query .= "'".$obj->getRefreshToken()."', ";
		$query .= "'".$obj->getAccessToken()."', ";
		$query .= $obj->getCreated().', ';
		$query .= $obj->getExpiresIn().', ';
		
		$fechaCreacion = $obj->getFechaCreacion();
		if(!empty($fechaCreacion))
			$query .= "'".$obj->getFechaCreacion()."'";
		else
			$query .= "NOW()";
		
		$query .= ")";

		try
		{
				
			if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
			{
				$this->log->LogError("usuarioDao.class - Create: MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new usuarioDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
			}
			else
			{
				$this->log->LogInfo("usuarioDao.class - Create: Registro Correcto");
				$_SESSION['user'] = $obj->getUserName();
				$_SESSION['userId']= $this->getLastIdInserted();
			}
		}
		catch (Exception $e) 
		{
			$this->log->LogError('usuarioDao.class - Create: Caught exception: ',  $e->getMessage(), "\n");
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
	private function getLastIdInserted()
	{
		return mysql_insert_id();
	}
	
	private function update($obj)
	{
		$query = "UPDATE {$this->table} SET ";
		
		$query .= "googleDriveId = '".$obj->getGoogleDriveId()."', ";
		$query .= "refresh_token = '".$obj->getRefreshToken()."', ";
		$query .= "access_token = '".$obj->getAccessToken()."', ";
		$query .= "created = ".$obj->getCreated().', ';
		$query .= "expires_in = ".$obj->getExpiresIn().', ';
		$query .= "fechaCreacion = '".$obj->getFechaCreacion()."' ";
		
		$query .= "WHERE id = ".$obj->getId().";";
		
		try
		{
			if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
			{
				$this->log->LogError("usuarioDao.class - Update: MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new usuarioDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
			}
			else
				$this->log->LogInfo("usuarioDao.class - Update: Edicion Correcto");
		}
		catch (Exception $e) 
		{
			$this->log->LogError('usuarioDao.class - Update: Caught exception: ',  $e->getMessage(), "\n");
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
	public function deleteObjects($id)
	{
		$query = "DELETE FROM {$this->table} WHERE id = {$id};";
		
		if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
		{
			$this->log->LogError("UsuarioDao.class - deleteObjects - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
			throw new usuarioDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
		}
		else
			$this->log->LogInfo("UsuarioDao.class - deleteObjects: Objetos Borrados");
				
		return true;
	}
}

class usuarioDaoException extends CustomException{}
	
?>