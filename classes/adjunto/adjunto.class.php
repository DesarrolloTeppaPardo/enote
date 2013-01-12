<?php 

class adjunto
{
	/* Object vars */
	private $id;
	private $titulo;
	private $fechaCreacion;
	private $googleDriveId;
	private $notaId;
	private $linkDescarga;
		
	public function __construct($googleDriveId = NULL, $notaId = NULL, $titulo = NULL)
	{
		if ($googleDriveId != NULL && $notaId != NULL && $titulo != NULL)
		{
	
			$this->googleDriveId = $googleDriveId;
			$this->notaId = $notaId;
			$this->titulo = $titulo;
		}
	}
	
	// SET FUNCTIONS
	
	public function setId ($id)
	{
		$this->id = $id;
	}
	
	public function setGoogleDriveId ($googleDriveId)
	{
		$this->googleDriveId = $googleDriveId;
	}
	
	public function setFechaCreacion ($fechaCreacion)
	{
		$this->fechaCreacion = $fechaCreacion;
	}
	
	public function setNotaId ($notaId)
	{
		$this->notaId = $notaId;
	}
	
	public function setTitulo ($titulo)
	{
		$this->titulo = $titulo;
	}
	
	public function setLinkDescarga ($linkDescarga)
	{
		$this->linkDescarga = $linkDescarga;
	}
	
	
	// GET FUNCTIONS
	
	public function getId ()
	{
		return $this->id;
	}
		
	public function getGoogleDriveId ()
	{
		return $this->googleDriveId;
	}

	public function getFechaCreacion ()
	{
		return $this->fechaCreacion;
	}
	
	public function getNotaId ()
	{
		return $this->notaId;
	}
		
	public function getTitulo ()
	{
		return $this->titulo;
	}
	
	public function getLinkDescarga ()
	{
		return $this->linkDescarga;
	}
	
}
	
?>