<?php 

interface interfaceUsuarioXml 
{
	public function createUsuarioXml($dom,$Nodes,$obj);
}

class usuarioXml implements interfaceUsuarioXml
{
	/* Object vars */
	//private static $instance;
		
	public function __construct()
	{
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
	}
	
	public function createUsuarioXml($dom,$Node,$obj)
	{				
		$id = $Node->appendChild($dom->createElement('id'));
		$id->appendChild($dom->createTextNode($obj->getId()));
		
		$usuario = $Node->appendChild($dom->createElement('usuario'));
		$usuario->appendChild($dom->createTextNode($obj->getUserName()));
		
		$clave = $Node->appendChild($dom->createElement('clave'));
		$clave->appendChild($dom->createTextNode($obj->getClave()));
		
		$googleDriveId = $Node->appendChild($dom->createElement('googleDriveId'));
		$googleDriveId->appendChild($dom->createTextNode($obj->getGoogleDriveId()));
		
		$refresh_token = $Node->appendChild($dom->createElement('refresh_token'));
		$refresh_token->appendChild($dom->createTextNode($obj->getRefreshToken()));
		
		$access_token = $Node->appendChild($dom->createElement('access_token'));
		$access_token->appendChild($dom->createTextNode($obj->getAccessToken()));
		
		$created = $Node->appendChild($dom->createElement('created'));
		$created->appendChild($dom->createTextNode($obj->getCreated()));
		
		$expires_in = $Node->appendChild($dom->createElement('expires_in'));
		$expires_in->appendChild($dom->createTextNode($obj->getExpiresIn()));
		
		$fechaCreacion = $Node->appendChild($dom->createElement('fechaCreacion'));
		$fechaCreacion->appendChild($dom->createTextNode($obj->getFechaCreacion()));
		
		return $Node;
	}
}
	
?>