<?php 

interface interfaceAdjuntoDao
{
	public function getIdByGoogleDriveId($googleDriveId);
	
	public function getObject($id);
	
	public function save ($obj);
	
	public function getLastIdInserted();
	
	public function getList ($notaId, $inicio = NULL, $orderby = NULL, $descasc = NULL);
	
	public function deleteObjects($ids);
	
	public function getAdjuntosNota ($notaId);
}

class adjuntoDao implements interfaceAdjuntoDao
{
	/* Object vars */
	private static $instance;
	private $table = 'adjunto';
	private $log;
		
	public function __construct()
	{
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	}
	

		// VERIFICACION DE EXISTENCIA DEL REGISTRO CON EL ID SUMINISTRADO EN LA BD. RECIBE UN ENTERO Y DEVUELVE VERDADERO O FALSO SEGUN SEA EL CASO.
	private function exist($id)
	{
		$query = "SELECT COUNT(id) AS count FROM {$this->table} WHERE id = {$id};";
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		$Data = mysql_fetch_assoc($Sql);
		
		if($Data['count'] > 0)
			return true;
		else
			return 0;
	}
		// RECIBE EL ID DE GOOGLE DRIVE DEL OBJETO Y RETORNA EL ID EN LA BD
	public function getIdByGoogleDriveId($googleDriveId)
	{
		$query = "SELECT id FROM {$this->table} WHERE googleDriveId = '{$googleDriveId}';";
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		if(mysql_num_rows($Sql) == 0)
			return NULL;
		
		$Data = mysql_fetch_assoc($Sql);
		
		return $Data['id'];
	}
	// RECIBE EL ID Y RETORNA EL OBJETO REFERENTE A LA AGENDA
	public function getObject($id) 
	{
		if($this->exist($id))
		{
			$query = "SELECT * FROM {$this->table} WHERE id = ".$id;
			$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
			$Data = mysql_fetch_assoc($Sql);
						
			$obj = new adjunto();
			
			$obj->setId($Data['id']);
			$obj->setTitulo($Data['titulo']);
			$obj->setGoogleDriveId($Data['googleDriveId']);
			$obj->setFechaCreacion($Data['fechaCreacion']);
			$obj->setLinkDescarga($Data['linkDescarga']);
			$obj->setNotaId($Data['notaId']);
			
			return $obj;
		}
		else
		{
			$this->log->LogInfo("usuarioDao.class - getObject: No existe el objeto {$id} en Base de Datos");
			return NULL;
		}
    }
		// GUARDA EL OBJETO TIPO AGENDA PARA PODER SER CREADO O MODIFICADO SEGUN SEA EL CASO
	public function save ($obj)
	{
		$id = $obj->getId();
		
		if(empty($id) || !$this->exist($id))
			$this->create($obj);
		else
			$this->update($obj);
	}
	// RECIBE EL OBJETO DE LA FUNCION SAVE PARA CREAR Y GUARDAR LA AGENDA EN BASE DE DATOS
	private function create($obj)
	{
		$id = $obj->getId();
		
		$query = "INSERT INTO {$this->table} (".((!empty($id)) ? 'id, ' : '')."titulo, fechaCreacion, notaId, linkDescarga, googleDriveId) VALUES (";
		
		if(!empty($id))
			$query .= $obj->getId().", ";
		
		$query .= "'".$obj->getTitulo()."', ";
		
		$fechaCreacion = $obj->getFechaCreacion();
		if(!empty($fechaCreacion))
			$query .= "'".$obj->getFechaCreacion()."', ";
		else
			$query .= "NOW(), ";

		$query .= $obj->getNotaId().', ';
		$query .= "'".$obj->getLinkDescarga()."', ";
		$query .= "'".$obj->getGoogleDriveId()."'";
		
		
		$query .= ")";
		
		try
		{
			
			if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
			{
				$this->log->LogError("adjuntoDao.class - create - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new adjuntoDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
			}
			else
			{
				$this->log->LogInfo("adjuntoDao.class - Adjunto Creado ".$obj->getTitulo());
			}
		}
		catch (Exception $e) 
		{
			$this->log->LogError('adjuntoDao.class - create - Caught exception: ',  $e->getMessage(), "\n");
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
	public function getLastIdInserted()
	{
		return mysql_insert_id();
	}
	// RECIBE EL OBJETO DE LA FUNCION SAVE Y SE ENCARGA DE GUARDAR EN BASE DE DATOS LAS MODIFICACIONES REALIZADAS AL ADJUNTO
	private function update($obj)
	{
		$currentObj = $this->getObject($obj->getId());

		$query = "UPDATE {$this->table} SET ";
		
		$query .= "titulo = '".$obj->getTitulo()."', ";
		$query .= "linkDescarga = '".$obj->getLinkDescarga()."', ";
		$query .= "fechaCreacion = '".$currentObj->getFechaCreacion()."', ";
		$query .= "notaId = ".$currentObj->getNotaId().', ';
		$query .= "googleDriveId = '".$obj->getGoogleDriveId()."' ";
		
		$query .= "WHERE id = ".$obj->getId().";";
		
		try
		{
			if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
			{
				$this->log->LogError("adjuntoDao.class - Update - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new adjuntoDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
			}
			else
				$this->log->LogInfo("adjuntoDao.class - Update: Edicion Correcto");
		}
		catch (Exception $e) 
		{
			$this->log->LogError('adjuntoDao.class - Update: Caught exception: ',  $e->getMessage(), "\n");
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	// SE ORDENAN LAS NOTAS DE UN USUARIO ESPECIFICO EN ORDEN DESCENDIENTE DE CREACION Y RETORNA UN ARREGLO DE ADJUNTOS
	// CORRESPONDIENTES A LA NOTA PARA MOSTRAR 10 ADJUNTOS POR PAGINA
	public function getList ($notaId, $inicio = NULL, $orderby = NULL, $descasc = NULL)
	{	
		$query = "SELECT * FROM {$this->table} WHERE notaId = {$notaId} ORDER BY ".(($orderby!=NULL)?$orderby:'fechaCreacion')."  ".(($descasc!=NULL)?$descasc:'DESC').", fechaCreacion desc LIMIT ".(($inicio!=NULL)?$inicio:0).",10;";
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		$Adjuntos = NULL;
		
		for ($i = 0; $i < mysql_num_rows($Sql); $i++)
			$Adjuntos[] = mysql_fetch_assoc($Sql);
		
		$Lista = array('Adjuntos' => $Adjuntos, 'countPages' => ceil($this->getCount($notaId) / 10));
		
		return $Lista;
	}
	
	private function getCount($id)
	{
		$query = "SELECT COUNT(id) as count FROM {$this->table} WHERE notaId = {$id};";
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		$count = mysql_fetch_assoc($Sql);
		
		return $count['count'];
	}
	// RECIBE UN ARREGLO DE ID'S Y SE ENCARGA DE BORRARLOS
	public function deleteObjects($ids)
	{
		$query = "DELETE FROM {$this->table} WHERE id IN(".implode(',', $ids).");";
		
		if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
		{
			$this->log->LogError("adjuntoDao.class - deleteObjects - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new agendaDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
		}
		else
			$this->log->LogInfo("adjuntoDao.class - deleteObjects: Objetos Borrados");
		return true;
	}
	// RECIBE EL ID DE UNA NOTA Y RETORNA UN ARREGLO DE ADJUNTOS CORRESPONDIENTES A DICHA NOTA
	public function getAdjuntosNota ($notaId)
	{	
		$query = "SELECT * FROM {$this->table} WHERE notaId = {$notaId};";
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		$Adjuntos = NULL;
		
		for ($i = 0; $i < mysql_num_rows($Sql); $i++)
			$Adjuntos[] = mysql_fetch_assoc($Sql);
		
		return $Adjuntos;
	}
}

class adjuntoDaoException extends CustomException{}
	
?>