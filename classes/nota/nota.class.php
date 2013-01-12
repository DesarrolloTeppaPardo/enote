<?php 

class nota
{
	/* Object vars */
	private $id;
	private $titulo;
	private $texto;
	private $fechaCreacion;
	private $fechaEdicion;
	private $agendaId;
	private $googleDriveId;
		
	public function __construct($titulo = NULL, $texto = NULL, $agendaId = NULL, $googleDriveId = NULL)
	{
		if ($titulo != NULL && $texto != NULL && $agendaId != NULL && $googleDriveId != NULL)
		{
	
			$this->titulo = $titulo;
			$this->texto = $texto;
			$this->agendaId = $agendaId;
			$this->googleDriveId = $googleDriveId;
		}
	}
	
	// SET FUNCTIONS
	
	public function setId ($id)
	{
		$this->id = $id;
	}
	
	public function setTitulo ($titulo)
	{
		$this->titulo = $titulo;
	}
	
	public function setTexto ($texto)
	{
		$this->texto = $texto;
	}
	
	public function setFechaCreacion ($fechaCreacion)
	{
		$this->fechaCreacion = $fechaCreacion;
	}
	
	public function setFechaEdicion ($fechaEdicion)
	{
		$this->fechaEdicion = $fechaEdicion;
	}
	
	public function setAgendaId ($agendaId)
	{
		$this->agendaId = $agendaId;
	}
	
	public function setGoogleDriveId ($googleDriveId)
	{
		$this->googleDriveId = $googleDriveId;
	}
	
	// GET FUNCTIONS
	
	public function getId ()
	{
		return $this->id;
	}
	
	public function getTitulo ()
	{
		return $this->titulo;
	}
	
	public function getTexto ()
	{
		return $this->texto;
	}
	
	public function getFechaCreacion ()
	{
		return $this->fechaCreacion;
	}
	
	public function getFechaEdicion ()
	{
		return $this->fechaEdicion;
	}
	
	public function getAgendaId ()
	{
		return $this->agendaId;
	}
	
	public function getGoogleDriveId ()
	{
		return $this->googleDriveId;
	}
	
}
	
?>