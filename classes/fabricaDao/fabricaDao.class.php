<?php 

class fabricaDao
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
	
	public function createUsuarioDao () {
		return new usuarioDao();
	}
	
	public function createAgendaDao () {
		return new agendaDao();
	}
	
	public function createEtiquetaDao () {
		return new etiquetaDao();
	}
	
	public function createNotaDao () {
		return new notaDao();
	}
	
	public function createAdjuntoDao () {
		return new adjuntoDao();
	}
}

?>