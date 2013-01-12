<?php 

interface interfaceAgendaXml 
{
	public function createAgendaXml($dom,$Nodes,$obj);
}

class agendaXml implements interfaceAgendaXml
{
	/* Object vars */
	//private static $instance;
		
	public function __construct()
	{
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	}
	
	public function createAgendaXml($dom,$Nodes,$obj)
	{		
		$Node = $Nodes->appendChild($dom->createElement('agenda')); 
		
		$id = $Node->appendChild($dom->createElement('id'));
		$id->appendChild($dom->createTextNode($obj->getId()));
		
		$nombre = $Node->appendChild($dom->createElement('nombre'));
		$nombre->appendChild($dom->createTextNode($obj->getNombre()));
		
		$fechaCreacion = $Node->appendChild($dom->createElement('fechaCreacion'));
		$fechaCreacion->appendChild($dom->createTextNode($obj->getFechaCreacion()));
		
		$fechaEdicion = $Node->appendChild($dom->createElement('fechaEdicion'));
		$fechaEdicion->appendChild($dom->createTextNode($obj->getFechaEdicion()));
		
		$usuarioId = $Node->appendChild($dom->createElement('usuarioId'));
		$usuarioId->appendChild($dom->createTextNode($obj->getUsuarioId()));
		
		$googleDriveId = $Node->appendChild($dom->createElement('googleDriveId'));
		$googleDriveId->appendChild($dom->createTextNode($obj->getGoogleDriveId()));
		
		return $Node;
	}
}
	
?>