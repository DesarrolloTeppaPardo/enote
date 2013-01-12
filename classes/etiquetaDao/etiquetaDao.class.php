<?php 

interface interfaceEtiquetaDao 
{
	public function getEtiquetaUsuarioId ($usuarioId, $nombre);
	
	public function getObject($id);
	
	public function save ($obj); 
	
	public function getList ($usuarioId, $inicio = NULL, $orderby = NULL, $descasc = NULL);
	
	public function deleteObjects($ids);
	
	public function deleteNotaEtiquetas($notaId);
	
	public function getLastIdInserted();
	
	public function insertEtiquetaNota($notaId, $etiquetaId);
	
	public function getEtiquetasNota($notaId);
	
	public function getMisEtiquetas ($usuarioId);
	
	public function getEtiquetasUsuario ($usuarioId);
}

class etiquetaDao implements interfaceEtiquetaDao
{
	/*DECLARACION DE OBJETOS */
	private static $singleton;
	private $table = 'etiqueta';
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
	// RECIBE EL ID Y NOMBRE DE USUARIO PARA RETORNAR LAS ETIQUETAS EN LA BD
	public function getEtiquetaUsuarioId ($usuarioId, $nombre)
	{ 
		$query = "SELECT id FROM {$this->table} WHERE usuarioId = {$usuarioId} AND nombre = '{$nombre}';";
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		$Data = mysql_fetch_assoc($Sql);
		return $Data['id'];
	}
	// RECIBE EL ID Y RETORNA EL OBJETO REFERENTE A LA ETIQUETA
	public function getObject($id) 
	{
		if($this->exist($id))
		{
			$query = "SELECT * FROM {$this->table} WHERE id = ".$id;
			$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
			$Data = mysql_fetch_assoc($Sql);
						
			$obj = new etiqueta();
			
			$obj->setId($Data['id']);
			$obj->setNombre($Data['nombre']);
			$obj->setFechaCreacion($Data['fechaCreacion']);
			$obj->setFechaEdicion($Data['fechaEdicion']);
			$obj->setUsuarioId($Data['usuarioId']);
			
			return $obj;
		}
		else
		{
			$this->log->LogInfo("etiquetaDao.class - getObject: No existe el objeto {$id} en Base de Datos");
			return NULL;
		}
    }
	// GUARDA EL OBJETO DE TIPO ETIQUETA PARA PODER SER CREADO O MODIFICADO SEGUN SEA EL CASO
	public function save ($obj)
	{
		$id = $obj->getId();
		
		if(empty($id) || !$this->exist($id))
			$this->create($obj);
		else
			$this->update($obj);
	}
	// RECIBE EL OBJETO DE LA FUNCION SAVE PARA CREAR Y GUARDAR LA ETIQUETA EN BASE DE DATOS
	private function create($obj)
	{
		$id = $obj->getId();
		
		$query = "INSERT INTO {$this->table} (".((!empty($id)) ? 'id, ' : '')."nombre, fechaCreacion, fechaEdicion, usuarioId) VALUES (";
		
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
			
		$query .= $obj->getUsuarioId();
		
		$query .= ")";
		
		try
		{
			
			if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
			{
				$this->log->LogError("etiquetaDao.class - create - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new etiquetaException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
			}
			else
			{
				$this->log->LogInfo("etiquetaDao.class - Etiqueta Creado ".$obj->getNombre());
			}
		}
		catch (Exception $e) 
		{
			$this->log->LogError('etiquetaDao.class - create - Caught exception: ',  $e->getMessage(), "\n");
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	// RECIBE EL OBJETO DE LA FUNCION SAVE Y PARA MODIFICAR LOS CAMPOS NECESARIOS DE LA AGENDA Y LUEGO GUARDAR EN BASE DE DATOS
	private function update($obj)
	{
		$currentObj = $this->getObject($obj->getId());

		$query = "UPDATE {$this->table} SET ";
		
		$query .= "nombre = '".$obj->getNombre()."', ";

		$query .= "fechaEdicion = NOW(), ";
		$query .= "usuarioId = ".$currentObj->getUsuarioId();
		
		$query .= " WHERE id = ".$obj->getId().";";
		
		try
		{			
			if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
			{
				$this->log->LogError("etiquetaDao.class - Update - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new etiquetaException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
			}
			else
				$this->log->LogInfo("etiquetaDao.class - Update: Edicion Correcto");
		}
		catch (Exception $e) 
		{
			$this->log->LogError('etiquetaDao.class - Update: Caught exception: ',  $e->getMessage(), "\n");
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	// SE ORDENAN LAS ETIQUETAS DE UN USUARIO ESPECIFICO EN ORDEN DESCENDIENTE DE CREACION PARA MOSTRAR 10 POR PAGINA
	public function getList ($usuarioId, $inicio = NULL, $orderby = NULL, $descasc = NULL)
	{	
		$query = "SELECT * FROM {$this->table} WHERE usuarioId = {$usuarioId} ORDER BY ".(($orderby!=NULL)?$orderby:'fechaCreacion')."  ".(($descasc!=NULL)?$descasc:'DESC').", fechaCreacion desc LIMIT ".(($inicio!=NULL)?$inicio:0).",10;";
		//echo $query; exit();
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		$Etiquetas = NULL;
		
		for ($i = 0; $i < mysql_num_rows($Sql); $i++)
			$Etiquetas[] = mysql_fetch_assoc($Sql);
		
		$Lista = array('Etiquetas' => $Etiquetas, 'countPages' => ceil($this->getCount($usuarioId) / 10));
		
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
			$this->log->LogError("etiquetaDao.class - deleteObjects - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
			throw new etiquetaException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
		}
		else
			$this->log->LogInfo("etiquetaDao.class - deleteObjects: Objetos Borrados");
				
		return true;
	}
	
	public function deleteNotaEtiquetas($notaId)
	{
		$query = "DELETE FROM nota_etiqueta WHERE notaId = {$notaId};";
		
		if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
		{
			$this->log->LogError("etiquetaDao.class - deleteNotaEtiquetas - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
			throw new etiquetaException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
		}
		else
			$this->log->LogInfo("etiquetaDao.class - deleteNotaEtiquetas: Objetos Borrados");
				
		return true;
	}
	
	public function getLastIdInserted()
	{
		return mysql_insert_id();
	}
	
	public function insertEtiquetaNota($notaId, $etiquetaId)
	{
		$query = "INSERT INTO nota_etiqueta values ({$notaId},{$etiquetaId},NOW());";
		
		try
		{
			if (!($Sql = mysql_query($query,mySqlDao::getInstance()->getLink())))
			{
				$this->log->LogError("etiquetaDao.class - insertEtiquetaNota - MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()));
				throw new etiquetaException("MySQL Error: ".mysql_error(mySqlDao::getInstance()->getLink()),4);
			}
			else
				$this->log->LogInfo("etiquetaDao.class - insertEtiquetaNota - notaId: ".$notaId." $etiquetaId: ".$etiquetaId);
		}
		catch (Exception $e) 
		{
			$this->log->LogError('etiquetaDao.class - insertEtiquetaNota: Caught exception: ',  $e->getMessage(), "\n");
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	// RECIBE EL ID DE UNA NOTA Y RETORNA EL ARREGLO DE ETIQUETAS CORRESPONDIENTES A DICHA NOTA
	public function getEtiquetasNota($notaId)
	{
		$query = "SELECT 
	e.nombre,
	e.id 
FROM 
	etiqueta e,
	nota_etiqueta ne
WHERE 
	ne.notaId = {$notaId} AND
	e.id = ne.etiquetaId
ORDER BY 
	e.nombre ASC;";
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		$Etiquetas = NULL;
		
		for ($i = 0; $i < mysql_num_rows($Sql); $i++)
			$Etiquetas[] = mysql_fetch_assoc($Sql);
		
		$Lista = array('Etiquetas' => $Etiquetas);
		
		return $Lista;
	}
	// RECIBE EL ID Y RETORNA EL OBJETO ETIQUETAS CORRESPONDIENTES A DICHO USUARIO
	public function getMisEtiquetas ($usuarioId)
	{
		$query = "SELECT nombre FROM etiqueta WHERE usuarioId = {$usuarioId};";
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		$Etiquetas = "[";
		
		for ($i = 0; $i < mysql_num_rows($Sql); $i++)
		{
			$data = mysql_fetch_assoc($Sql);
			$Etiquetas .= "'{$data['nombre']}', ";
		}
			
		
		return $Etiquetas."'']";
	}
	
	public function getEtiquetasUsuario ($usuarioId)
	{
		$query = "SELECT * FROM etiqueta WHERE usuarioId = {$usuarioId};";
		
		$Sql = mysql_query($query,mySqlDao::getInstance()->getLink());
		
		$Etiquetas = NULL;
		
		for ($i = 0; $i < mysql_num_rows($Sql); $i++)
			$Etiquetas[] = mysql_fetch_assoc($Sql);
			
		return $Etiquetas;
	}
}

class etiquetaException extends CustomException{}
	
?>