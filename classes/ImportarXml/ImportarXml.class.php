<?php 

class ImportarXml 
{
	private $xml;
	private $usuarioId;
	
	public function __construct(){
	}
	
	public function setXmlFile($fileXml)
	{
		$this->xml = simplexml_load_file($fileXml);
	}
	
	public function startImport()
	{
		$this->getUsuarios();
	}
	
	private function getUsuarios()
	{
		foreach($this->xml->children() as $usuario)
		{
			
			fabricaDao::getInstance()->createUsuarioDao()->deleteObjects($usuario->id);
			
			$obj = array(
				'id' 				=>	$usuario->id,
				'userName'			=>	$usuario->usuario,
				'clave'				=>	$usuario->clave,
				'googleDriveId'		=>	$usuario->googleDriveId,
				'refreshToken'		=>	$usuario->refresh_token,
				'accessToken'		=>	$usuario->access_token,
				'created'			=>	$usuario->created,
				'expiresIn'			=>	$usuario->expires_in,
				'fechaCreacion'		=>	$usuario->fechaCreacion
			);
			
			$this->usuarioId = $usuario->id;
			
			$this->setUsuario($obj);
			
			$this->getEtiquetas($usuario);
			$this->getAgendas($usuario);
		}
	}
	
	private function getEtiquetas($usuario)
	{
		foreach($usuario->Etiquetas->children() as $etiqueta)
		{
			$obj = array(
			'id' 				=>	$etiqueta->id,
			'nombre'			=>	$etiqueta->nombre,
			'fechaCreacion'		=>	$etiqueta->fechaCreacion,
			'fechaEdicion'		=>	$etiqueta->fechaEdicion,
			'usuarioId'			=>	$etiqueta->usuarioId
			);
			
			$this->setEtiqueta($obj);
		}
	}
	
	private function getAgendas($usuario)
	{
		foreach($usuario->Agendas->children() as $agenda)
		{
			$obj = array(
			'id' 				=>	$agenda->id,
			'nombre'			=>	$agenda->nombre,
			'fechaCreacion'		=>	$agenda->fechaCreacion,
			'fechaEdicion'		=>	$agenda->fechaEdicion,
			'usuarioId'			=>	$agenda->usuarioId,
			'googleDriveId'		=>	$agenda->googleDriveId
			);
			
			$this->setAgenda($obj);
			
			$this->getNotas($agenda);
		}
	}
	
	private function getNotas($agenda)
	{
		foreach($agenda->Notas->children() as $nota)
		{
			$obj = array(
			'id' 				=>	$nota->id,
			'titulo'			=>	$nota->titulo,
			'texto'				=>	$nota->texto,
			'fechaCreacion'		=>	$nota->fechaCreacion,
			'fechaEdicion'		=>	$nota->fechaEdicion,
			'agendaId'			=>	$nota->agendaId,
			'googleDriveId'		=>	$nota->googleDriveId
			);
						
			$this->setNota($obj);
			
			$this->getEtiquetasNota($nota);
			$this->getAdjuntos($nota);
		}
	}
	
	private function getEtiquetasNota($nota)
	{
		foreach($nota->Etiquetas_Nota->children() as $etiqueta_nota)									
			$this->setEtiquetaNota($nota->id, $etiqueta_nota->id);
	}
	
	private function getAdjuntos($nota)
	{
		foreach($nota->Adjuntos->children() as $adjunto)
		{
			$obj = array(
			'id' 				=>	$adjunto->id,
			'googleDriveId'		=>	$adjunto->googleDriveId,
			'fechaCreacion'		=>	$adjunto->fechaCreacion,
			'notaId'			=>	$adjunto->notaId,
			'titulo'			=>	$adjunto->titulo,
			'linkDescarga'		=>	$adjunto->linkDescarga
			);
								
			$this->setAdjunto($obj);
		}
	}
	
	private function setUsuario($data)
	{
		$Obj = fabricaDominio::getInstance()->createUsuario();
		
		$Obj->setId($data['id']);
		$Obj->setUserName($data['userName']);
		$Obj->setClaveXml($data['clave']);
		$Obj->setGoogleDriveId($data['googleDriveId']);
		$Obj->setRefreshToken($data['refreshToken']);
		$Obj->setAccessToken($data['accessToken']);
		$Obj->setCreated($data['created']);
		$Obj->setExpiresIn($data['expiresIn']);
		$Obj->setFechaCreacion($data['fechaCreacion']);
		
		fabricaDao::getInstance()->createUsuarioDao()->save($Obj);
		
	}
	
	private function setEtiqueta($data)
	{
		$Obj = fabricaDominio::getInstance()->createEtiqueta();
		
		$Obj->setId($data['id']);
		$Obj->setNombre($data['nombre']);
		$Obj->setFechaCreacion($data['fechaCreacion']);
		$Obj->setFechaEdicion($data['fechaEdicion']);
		$Obj->setUsuarioId($data['usuarioId']);
		
		fabricaDao::getInstance()->createEtiquetaDao()->save($Obj);
	}
	
	private function setAgenda($data)
	{
		$Obj = fabricaDominio::getInstance()->createAgenda();
		
		$Obj->setId($data['id']);
		$Obj->setNombre($data['nombre']);
		$Obj->setFechaCreacion($data['fechaCreacion']);
		$Obj->setFechaEdicion($data['fechaEdicion']);
		$Obj->setUsuarioId($data['usuarioId']);
		$Obj->setGoogleDriveId($data['googleDriveId']);
		
		fabricaDao::getInstance()->createAgendaDao()->save($Obj);
	}
	
	private function setNota($data)
	{
		$Obj = fabricaDominio::getInstance()->createNota();
		
		$Obj->setId($data['id']);
		$Obj->setTitulo($data['titulo']);
		$Obj->setTexto($data['texto']);
		$Obj->setFechaCreacion($data['fechaCreacion']);
		$Obj->setFechaEdicion($data['fechaEdicion']);
		$Obj->setAgendaId($data['agendaId']);
		$Obj->setGoogleDriveId($data['googleDriveId']);
		
		fabricaDao::getInstance()->createNotaDao()->save($Obj);
	}
	
	private function setAdjunto($data)
	{
		$Obj = fabricaDominio::getInstance()->createAdjunto();
		
		$Obj->setId($data['id']);
		$Obj->setGoogleDriveId($data['googleDriveId']);
		$Obj->setFechaCreacion($data['fechaCreacion']);
		$Obj->setNotaId($data['notaId']);
		$Obj->setTitulo($data['titulo']);
		$Obj->setLinkDescarga($data['linkDescarga']);
		
		fabricaDao::getInstance()->createAdjuntoDao()->save($Obj);
	}
	
	private function setEtiquetaNota($notaId, $etiquetaId)
	{
		fabricaDao::getInstance()->createEtiquetaDao()->insertEtiquetaNota($notaId, $etiquetaId);
	}
	
	public function getUsuarioId()
	{
		return $this->usuarioId;
	}
}

?>