<?php 

class etiqueta
{
	/* Object vars */
	private $id;
	private $nombre;
	private $fechaCreacion;
	private $fechaEdicion;
	private $usuarioId;
		
	public function __construct($nombre = NULL)
	{
		if ($nombre != NULL)
		{	
			$this->nombre = $nombre;
			$this->usuarioId = usuario::getUsuario()->getId();
		}
	}
	
	// SET FUNCTIONS
	
	public function setId ($id)
	{
		$this->id = $id;
	}
	
	public function setNombre ($nombre)
	{
		$this->nombre = $nombre;
	}
	
	public function setFechaCreacion ($fechaCreacion)
	{
		$this->fechaCreacion = $fechaCreacion;
	}
	
	public function setFechaEdicion ($fechaEdicion)
	{
		$this->fechaEdicion = $fechaEdicion;
	}
	
	public function setUsuarioId ($usuarioId)
	{
		$this->usuarioId = $usuarioId;
	}
	

	// GET FUNCTIONS
	
	public function getId ()
	{
		return $this->id;
	}
	
	public function getNombre ()
	{
		return $this->nombre;
	}
	
	public function getFechaCreacion ()
	{
		return $this->fechaCreacion;
	}
	
	public function getFechaEdicion ()
	{
		return $this->fechaEdicion;
	}
	
	public function getUsuarioId ()
	{
		return $this->usuarioId;
	}
		
}
	
?>