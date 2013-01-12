<?php 

class fabricaGoogleDrive
{
	private static $instance;
	private $log;
	 
	private function __construct()
	{
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	}
	
	public static function getInstance()
	{
		if (!self::$instance)
			self::$instance = new self;
			
		return self::$instance;
	}
	
	public function createUsuarioGoogleDrive () {
		return new usuarioGoogleDrive();
	}
	
	public function createAgendaGoogleDrive () {
		return new agendaGoogleDrive();
	}
	
	public function createEtiquetaGoogleDrive () {
		return new etiquetaGoogleDrive();
	}
	
	public function createNotaGoogleDrive () {
		return new notaGoogleDrive();
	}
	
	public function createAdjuntoGoogleDrive () {
		return new adjuntoGoogleDrive();
	}
}

?>