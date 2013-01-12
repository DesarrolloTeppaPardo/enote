<?php 

interface interfaceAdjuntoXml 
{
	public function createAdjuntoXml($dom,$Nodes,$obj);
}

class adjuntoXml implements interfaceAdjuntoXml
{
	/* Object vars */
	//private static $instance;
		
	public function __construct()
	{
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	}
	// RECIBE EL ARCHIVO DONDE SE VA A ESCRIBIR LA DATA CONTENIDA EN EL XML Y RETORNA EL NODO PARA CREAR EL ADJUNTO
	public function createAdjuntoXml($dom,$Nodes,$obj)
	{		
		$Node = $Nodes->appendChild($dom->createElement('adjunto')); 
		
		$id = $Node->appendChild($dom->createElement('id'));
		$id->appendChild($dom->createTextNode($obj->getId()));
		
		$googleDriveId = $Node->appendChild($dom->createElement('googleDriveId'));
		$googleDriveId->appendChild($dom->createTextNode($obj->getGoogleDriveId()));
		
		$fechaCreacion = $Node->appendChild($dom->createElement('fechaCreacion'));
		$fechaCreacion->appendChild($dom->createTextNode($obj->getFechaCreacion()));
		
		$notaId = $Node->appendChild($dom->createElement('notaId'));
		$notaId->appendChild($dom->createTextNode($obj->getNotaId()));
		
		$titulo = $Node->appendChild($dom->createElement('titulo'));
		$titulo->appendChild($dom->createTextNode($obj->getTitulo()));
		
		$linkDescarga = $Node->appendChild($dom->createElement('linkDescarga'));
		$linkDescarga->appendChild($dom->createTextNode($obj->getLinkDescarga()));

		return $Nodes;
	}
}
	
?>