<?php 

class fabricaDominio
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
	
	public function createUsuario () {
		return new usuario();
	}
	
	public function createAgenda () {
		return new agenda();
	}
	
	public function createEtiqueta () {
		return new etiqueta();
	}
	
	public function createNota () {
		return new nota();
	}
	
	public function createAdjunto () {
		return new adjunto();
	}
}

?>