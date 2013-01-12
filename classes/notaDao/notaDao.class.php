<?php 

interface interfaceNotaDao 
{
	public function getIdByGoogleDriveId($googleDriveId);
	
	public function getGoogleDriveIdById($id);
	
	public function getObject($id);
	
	public function save ($obj);
	
	public function getLastIdInserted();
	
	public function getList ($agendaId, $inicio = NULL, $orderby = NULL, $descasc = NULL);
	
	public function deleteObjects($ids);
	
	public function deleteNotasAgenda($agendaId);
	
	public function buscarNotas ($usuarioId, $palabras, $inicio = NULL, $orderby = NULL, $descasc = NULL);
	
	public function getNotasAgenda ($agendaId);
}

class notaDao implements interfaceNotaDao
{
	/* Object vars */
	private static $singleton;
	private $table = 'nota';
	private $log;
		
	public function __construct()
	{
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	}
	
	/*
	public static function getInstance()
	{
		if (!self::$singleton)
			self::$singleton = new self;
			
		return self::$singleton;
	}
	*/
	
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
	
	public function getObject($id) 
	{
		if($this->exist($id))
		{
			$query = "SELECT * FROM {$this->table} WHERE id = ".$id;
			$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
			$Data = mysql_fetch_assoc($Sql);
						
			$obj = new nota();
			
			$obj->setId($Data['id']);
			$obj->setTitulo($Data['titulo']);
			$obj->setTexto($Data['texto']);
			$obj->setFechaCreacion($Data['fechaCreacion']);
			$obj->setFechaEdicion($Data['fechaEdicion']);
			$obj->setAgendaId($Data['agendaId']);
			$obj->setGoogleDriveId($Data['googleDriveId']);
			
			return $obj;
		}
		else
		{
			$this->log->LogInfo("notaDao.class - getObject: No existe el objeto {$id} en Base de Datos");
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
		
		$query = "INSERT INTO {$this->table} (".((!empty($id)) ? 'id, ' : '')."titulo, texto, fechaCreacion, fechaEdicion, agendaId, googleDriveId) VALUES (";
		
		if(!empty($id))
			$query .= $obj->getId().", ";
		
		$query .= "'".$obj->getTitulo()."', ";
		$query .= "'".$obj->getTexto()."', ";
		
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
			
		$query .= $obj->getAgendaId().', ';
		$query .= "'".$obj->getGoogleDriveId()."'";
		
		
		$query .= ")";
		
		try
		{
			
			if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
			{
				$this->log->LogError("notaDao.class - create - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new notaDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
			}
			else
			{ 
				$this->log->LogInfo("notaDao.class - Nota Creado ".$obj->getTitulo());
			}
		}
		catch (Exception $e) 
		{
			$this->log->LogError('notaDao.class - create - Caught exception: ',  $e->getMessage(), "\n");
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
	public function getLastIdInserted()
	{
		return mysql_insert_id();
	}
	
	private function update($obj)
	{
		$currentObj = $this->getObject($obj->getId());

		$query = "UPDATE {$this->table} SET ";
		
		$query .= "titulo = '".$obj->getTitulo()."', ";
		$query .= "texto = '".$obj->getTexto()."', ";
		$query .= "fechaCreacion = '".$currentObj->getFechaCreacion()."', ";
		$query .= "fechaEdicion = NOW(), ";
		$query .= "agendaId = ".$currentObj->getAgendaId().', ';
		$query .= "googleDriveId = '".$obj->getGoogleDriveId()."' ";
		
		$query .= "WHERE id = ".$obj->getId().";";
		
		try
		{
			if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
			{
				$this->log->LogError("notaDao.class - Update - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new notaDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
			}
			else
				$this->log->LogInfo("notaDao.class - Update: Edicion Correcto");
		}
		catch (Exception $e) 
		{
			$this->log->LogError('notaDao.class - Update: Caught exception: ',  $e->getMessage(), "\n");
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
	public function getList ($agendaId, $inicio = NULL, $orderby = NULL, $descasc = NULL)
	{	
		$query = "SELECT * FROM {$this->table} WHERE agendaId = {$agendaId} ORDER BY ".(($orderby!=NULL)?$orderby:'fechaCreacion')."  ".(($descasc!=NULL)?$descasc:'DESC').", fechaCreacion desc LIMIT ".(($inicio!=NULL)?$inicio:0).",10;";
		//echo $query; exit();
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		$Notas = NULL;
		
		for ($i = 0; $i < mysql_num_rows($Sql); $i++)
			$Notas[] = mysql_fetch_assoc($Sql);
		
		$Lista = array('Notas' => $Notas, 'countPages' => ceil($this->getCount($agendaId) / 10));
		
		return $Lista;
	}
	
	private function getCount($id)
	{
		$query = "SELECT COUNT(id) as count FROM {$this->table} WHERE agendaId = {$id};";
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		$count = mysql_fetch_assoc($Sql);
		
		return $count['count'];
	}
	
	public function deleteObjects($ids)
	{
		$query = "DELETE FROM {$this->table} WHERE id IN(".implode(',', $ids).");";
		
		if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
		{
			$this->log->LogError("notaDao.class - deleteObjects - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new notaDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
		}
		else
			$this->log->LogInfo("notaDao.class - deleteObjects: Objetos Borrados");
				
		return true;
	}
	
	public function deleteNotasAgenda($agendaId)
	{
		$query = "DELETE FROM {$this->table} WHERE agendaId = {$agendaId}";
		
		if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
		{
			$this->log->LogError("notaDao.class - deleteNotasAgenda - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new notaDaoException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
		}
		else
			$this->log->LogInfo("notaDao.class - deleteNotasAgenda: Objetos Borrados");
				
		return true;
	}
	
	public function buscarNotas ($usuarioId, $palabras, $inicio = NULL, $orderby = NULL, $descasc = NULL)
	{	
		$palabras = str_replace(",","|",$palabras);
		
		$query = "SELECT A.* FROM (
SELECT n.id, n.titulo, n.fechaCreacion, n.fechaEdicion, n.agendaId FROM agenda a, nota n WHERE a.usuarioId = {$usuarioId} AND a.id = n.agendaId AND n.titulo RLIKE '{$palabras}' OR n.texto RLIKE '{$palabras}' GROUP BY n.id
UNION
SELECT n.id, n.titulo, n.fechaCreacion, n.fechaEdicion, n.agendaId FROM agenda a, nota n, adjunto ad WHERE a.usuarioId = {$usuarioId} AND a.id = n.agendaId AND n.id = ad.notaId AND ad.titulo RLIKE '{$palabras}' GROUP BY n.id
UNION
SELECT n.id, n.titulo, n.fechaCreacion, n.fechaEdicion, n.agendaId FROM agenda a, nota n, etiqueta e, nota_etiqueta ne WHERE a.usuarioId = {$usuarioId} AND a.id = n.agendaId AND e.nombre RLIKE '{$palabras}' AND n.id = ne.notaId AND e.id = ne.etiquetaId GROUP BY n.id
) A ORDER BY ".(($orderby!=NULL)?'A.'.$orderby:'A.fechaCreacion')."  ".(($descasc!=NULL)?$descasc:'DESC').", A.fechaCreacion desc ".
(($inicio!=NULL) ? 'LIMIT '.$inicio.', 10' : '' ).";";
		
		//echo $query; exit();
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		$count = mysql_num_rows($Sql);
		
		$Notas = NULL;
		
		if ($count > 10)
			$fin = 10;
		else
			$fin = $count;
		
		for ($i = 0; $i < $fin; $i++)
			$Notas[] = mysql_fetch_assoc($Sql);
		
		$Lista = array('Notas' => $Notas, 'countPages' => ceil($count/ 10));
		
		return $Lista;
	}
	
	public function getNotasAgenda ($agendaId)
	{	
		$query = "SELECT * FROM {$this->table} WHERE agendaId = {$agendaId};";
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		$Notas = NULL;
		
		for ($i = 0; $i < mysql_num_rows($Sql); $i++)
			$Notas[] = mysql_fetch_assoc($Sql);
		
		return $Notas;
	}
}

class notaDaoException extends CustomException{}
	
?>