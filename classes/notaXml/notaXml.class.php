<?php 

interface interfaceNotaXml 
{
	public function createNotaXml($dom,$Nodes,$obj);
}

class notaXml implements interfaceNotaXml
{
	/* Object vars */
	//private static $instance;
		
	public function __construct()
	{
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	}
	
	public function createNotaXml($dom,$Nodes,$obj)
	{		
		$Node = $Nodes->appendChild($dom->createElement('nota')); 
		
		$id = $Node->appendChild($dom->createElement('id'));
		$id->appendChild($dom->createTextNode($obj->getId()));
		
		$titulo = $Node->appendChild($dom->createElement('titulo'));
		$titulo->appendChild($dom->createTextNode($obj->getTitulo()));
		
		$texto = $Node->appendChild($dom->createElement('texto'));
		$texto->appendChild($dom->createTextNode($obj->getTexto()));
		
		$fechaCreacion = $Node->appendChild($dom->createElement('fechaCreacion'));
		$fechaCreacion->appendChild($dom->createTextNode($obj->getFechaCreacion()));
		
		$fechaEdicion = $Node->appendChild($dom->createElement('fechaEdicion'));
		$fechaEdicion->appendChild($dom->createTextNode($obj->getFechaEdicion()));
		
		$agendaId = $Node->appendChild($dom->createElement('agendaId'));
		$agendaId->appendChild($dom->createTextNode($obj->getAgendaId()));
		
		$googleDriveId = $Node->appendChild($dom->createElement('googleDriveId'));
		$googleDriveId->appendChild($dom->createTextNode($obj->getGoogleDriveId()));
		
		return $Node;
	}
}
	
?>