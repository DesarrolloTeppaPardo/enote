<?php 

class fabricaXml
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
	
	public function createUsuarioXml () {
		return new usuarioXml();
	}
	
	public function createAgendaXml () {
		return new agendaXml();
	}
	
	public function createEtiquetaXml () {
		return new etiquetaXml();
	}
	
	public function createNotaXml () {
		return new notaXml();
	}
	
	public function createAdjuntoXml () {
		return new adjuntoXml();
	}
	
	public function createExportarXml () {
		return new ExportarXml();
	}
	
	public function createImportarXml () {
		return new ImportarXml();
	}
}

?>