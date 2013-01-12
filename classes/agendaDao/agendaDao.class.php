<?php 

interface interfaceAgendaDao 
{
	public function getIdByGoogleDriveId($googleDriveId);
	
	public function getObject($id);
	
	public function save ($obj);
	
	public function getLastIdInserted();
	
	public function getList ($usuarioId, $inicio = NULL, $orderby = NULL, $descasc = NULL);
	
	public function deleteObjects($ids);
	
	public function getAgendasUsuario ($usuarioId);
}

class agendaDao implements interfaceAgendaDao
{
	/* DECLARACION DE OBJETOS */
	private static $singleton;
	private $table = 'agenda';
	private $log;
		
	public function __construct()
	{
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	}
	
	// VERIFICACION DE EXISTENCIA DEL REGISTRO CON EL ID SUMINISTRADO EN LA BD.
	// RECIBE UN ENTERO Y DEVUELVE VERDADERO O FALSO SEGUN SEA EL CASO.
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
	 
	public function getGoogleDriveIdById($id)
	{
		$query = "SELECT googleDriveId FROM {$this->table} WHERE id = '{$id}';";
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		if(mysql_num_rows($Sql) == 0)
			return NULL;
		
		$Data = mysql_fetch_assoc($Sql);
		
		return $Data['googleDriveId'];
	}
	// RECIBE EL ID Y RETORNA EL OBJETO REFERENTE A LA AGENDA
	public function getObject($id) 
	{
		if($this->exist($id))
		{
			$query = "SELECT * FROM {$this->table} WHERE id = ".$id;
			$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
			$Data = mysql_fetch_assoc($Sql);
						
			$obj = new agenda();
			
			$obj->setId($Data['id']);
			$obj->setNombre($Data['nombre']);
			$obj->setFechaCreacion($Data['fechaCreacion']);
			$obj->setFechaEdicion($Data['fechaEdicion']);
			$obj->setUsuarioId($Data['usuarioId']);
			$obj->setGoogleDriveId($Data['googleDriveId']);
			
			return $obj;
		}
		else
		{
			$this->log->LogInfo("agendaDao.class - getObject: No existe el objeto {$id} en Base de Datos");
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
		
		$query = "INSERT INTO {$this->table} (".((!empty($id)) ? 'id, ' : '')."nombre, fechaCreacion, fechaEdicion, usuarioId, googleDriveId) VALUES (";
		
		if(!empty($id))
			$query .= $obj->getId().", ";
		
		$query .= "'".$obj->getNombre()."', ";
		
		$fechaCreacion = $obj->getFechaCreacion();
		if(!empty($fechaCreacion))
			$query .= "'".$obj->getFechaCreacion()."', ";
		else
			$query .= "NOW(), ";
		
		$fechaEdicion = $obj->getFechaCreacion();
		if(!empty($fechaEdicion))
			$query .= "'".$obj->getFechaEdicion()."', ";
		else
			$query .= "NOW(), ";
		
		$query .= $obj->getUsuarioId().', ';
		
		$query .= "'".$obj->getGoogleDriveId()."'";
		
		
		$query .= ")";
		
		try
		{
			
			if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
			{
				$this->log->LogError("agendaDao.class - create - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new agendaDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
			}
			else
			{
				$this->log->LogInfo("agendaDao.class - Agenda Creado ".$obj->getNombre());
			}
		}
		catch (Exception $e) 
		{
			$this->log->LogError('agendaDao.class - create - Caught exception: ',  $e->getMessage(), "\n");
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
	public function getLastIdInserted()
	{
		return mysql_insert_id();
	}
		// RECIBE EL OBJETO DE LA FUNCION SAVE Y SE ENCARGA DE MODIFICAR LOS CAMPOS DE LA AGENDA PARA LUEGO GUARDAR EN BASE DE DATOS
	private function update($obj)
	{
		$currentObj = $this->getObject($obj->getId());

		$query = "UPDATE {$this->table} SET ";
		
		$query .= "nombre = '".$obj->getNombre()."', ";
		$query .= "fechaCreacion = '".$currentObj->getFechaCreacion()."', ";
		$query .= "fechaEdicion = NOW(), ";
		$query .= "usuarioId = ".$currentObj->getUsuarioId().', ';
		$query .= "googleDriveId = '".$obj->getGoogleDriveId()."' ";
		
		$query .= "WHERE id = ".$obj->getId().";";

		try
		{
			if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
			{
				$this->log->LogError("agendaDao.class - Update - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new agendaDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
			}
			else
				$this->log->LogInfo("agendaDao.class - Update: Edicion Correcto");
		}
		catch (Exception $e) 
		{
			$this->log->LogError('agendaDao.class - Update: Caught exception: ',  $e->getMessage(), "\n");
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	// SE ORDENAN LAS AGENDAS DE UN USUARIO ESPECIFICO EN ORDEN DESCENDIENTE DE CREACION PARA MOSTRAR 10 POR PAGINA
	public function getList ($usuarioId, $inicio = NULL, $orderby = NULL, $descasc = NULL)
	{	
		$query = "SELECT * FROM {$this->table} WHERE usuarioId = {$usuarioId} ORDER BY ".(($orderby!=NULL)?$orderby:'fechaCreacion')."  ".(($descasc!=NULL)?$descasc:'DESC').", fechaCreacion desc LIMIT ".(($inicio!=NULL)?$inicio:0).",10;";
	
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		$Agendas = NULL;
		
		for ($i = 0; $i < mysql_num_rows($Sql); $i++)
			$Agendas[] = mysql_fetch_assoc($Sql);
		
		$Lista = array('Agendas' => $Agendas, 'countPages' => ceil($this->getCount($usuarioId) / 10));
		
		return $Lista;
	}
	
	private function getCount($usuarioId)
	{
		$query = "SELECT COUNT(id) as count FROM {$this->table} WHERE usuarioId = ".$usuarioId;
		
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
			$this->log->LogError("agendaDao.class - deleteObjects - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
			throw new agendaDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
		}
		else
			$this->log->LogInfo("agendaDao.class - deleteObjects: Objetos Borrados");
				
		return true;
	}
	// RECIBE UN ID Y RETORNA TODAS LAS AGENDAS CORRESPONDIENTES 
	public function getAgendasUsuario ($usuarioId)
	{	
		$query = "SELECT * FROM {$this->table} WHERE usuarioId = {$usuarioId};";

		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		$Agendas = NULL;
		
		for ($i = 0; $i < mysql_num_rows($Sql); $i++)
			$Agendas[] = mysql_fetch_assoc($Sql);
					
		return $Agendas;
	}
}

class agendaDaoException extends CustomException{}
	
?>