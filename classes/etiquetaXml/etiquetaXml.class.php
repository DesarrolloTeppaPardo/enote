<?php 

interface interfaceEtiquetaXml 
{
	public function createEtiquetaXml($dom,$Nodes,$obj);
}

class etiquetaXml implements interfaceEtiquetaXml
{
	/* Object vars */
	//private static $instance;
		
	public function __construct()
	{
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	}
	
	public function createEtiquetaXml($dom,$Nodes,$obj)
	{		
		$Node = $Nodes->appendChild($dom->createElement('etiqueta')); 
		
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
			
		return $Nodes;
	}
}
	
?>