<?php 

class ExportarXml 
{
	private $dom;
	private $usuarioId;
	private $usuarioObj;
	private $usuarioNode;
		
	public function __construct()
	{
		$this->dom = new DomDocument('1.0');
	}
	
	public function startExport($usuarioId)
	{
		$this->usuarioId = $usuarioId;
		$this->usuarioObj = fabricaDao::getInstance()->createUsuarioDao()->getObject($this->usuarioId);
		
		$this->createUsuarioXml();
		$this->saveXml();
	}
	
	private function createUsuarioXml()
	{
		$usuariosNode = $this->dom->appendChild($this->dom->createElement('Usuarios'));
		$this->usuarioNode = $usuariosNode->appendChild($this->dom->createElement('usuario'));
		$this->usuarioNode = fabricaXml::getInstance()->createUsuarioXml()->createUsuarioXml($this->dom,$this->usuarioNode,$this->usuarioObj);
		
		$this->createUsuarioEtiquetasXml();
		$this->createAgendaXml();
	}
	
	private function createUsuarioEtiquetasXml()
	{
		$etiquetasNode = $this->usuarioNode->appendChild($this->dom->createElement('Etiquetas'));
		$ListaEtiquetas = fabricaDao::getInstance()->createEtiquetaDao()->getEtiquetasUsuario($this->usuarioId);
		
		for ($i = 0; $i < count($ListaEtiquetas); $i++)
		{
			$etiquetaObj = fabricaDao::getInstance()->createEtiquetaDao()->getObject($ListaEtiquetas[$i]['id']);
			$etiquetasNode = fabricaXml::getInstance()->createEtiquetaXml()->createEtiquetaXml($this->dom,$etiquetasNode,$etiquetaObj);
		}
	}
	
	private function createAgendaXml()
	{
		$agendasNode = $this->usuarioNode->appendChild($this->dom->createElement('Agendas'));
		$ListaAgendas = fabricaDao::getInstance()->createAgendaDao()->getAgendasUsuario($this->usuarioId);
		
		for ($i = 0; $i < count($ListaAgendas); $i++)
		{
			$agendaObj = fabricaDao::getInstance()->createAgendaDao()->getObject($ListaAgendas[$i]['id']);
			$agendaNode = fabricaXml::getInstance()->createAgendaXml()->createAgendaXml($this->dom,$agendasNode,$agendaObj);
		
			$this->createNotaXml($agendaNode,$ListaAgendas[$i]['id']);
		}
	}
	
	private function createNotaXml($Node,$agendaId)
	{
		$notasNode = $Node->appendChild($this->dom->createElement('Notas'));
		$ListaNotas = fabricaDao::getInstance()->createNotaDao()->getNotasAgenda($agendaId);
		
		for ($i = 0; $i < count($ListaNotas); $i++)
		{
			$notaObj = fabricaDao::getInstance()->createNotaDao()->getObject($ListaNotas[$i]['id']);
			$notaNode = fabricaXml::getInstance()->createNotaXml()->createNotaXml($this->dom,$notasNode,$notaObj);
			
			$this->dom = $this->createEtiquetaXml($this->dom,$notaNode,$ListaNotas[$i]['id']);
			$this->dom = $this->createAdjuntoXml($this->dom,$notaNode,$ListaNotas[$i]['id']);
		}
	}
	
	private function createAdjuntoXml($dom,$Node,$notaId)
	{
		$adjuntosNode = $Node->appendChild($dom->createElement('Adjuntos'));
		$ListaAdjuntos = fabricaDao::getInstance()->createAdjuntoDao()->getAdjuntosNota($notaId);
		
		for ($i = 0; $i < count($ListaAdjuntos); $i++)
		{
			$adjuntoObj = fabricaDao::getInstance()->createAdjuntoDao()->getObject($ListaAdjuntos[$i]['id']);
			$adjuntosNode = fabricaXml::getInstance()->createAdjuntoXml()->createAdjuntoXml($dom,$adjuntosNode,$adjuntoObj);
		}
		
		return $dom;
	}
	
	private function createEtiquetaXml($dom,$Node,$notaId)
	{
		$etiquetasNode = $Node->appendChild($dom->createElement('Etiquetas_Nota'));
		$ListaEtiquetas = fabricaDao::getInstance()->createEtiquetaDao()->getEtiquetasNota($notaId);
	
		for ($i = 0; $i < count($ListaEtiquetas['Etiquetas']); $i++)
		{
			$etiquetaObj = fabricaDao::getInstance()->createEtiquetaDao()->getObject($ListaEtiquetas['Etiquetas'][$i]['id']);
			$etiquetasNode = fabricaXml::getInstance()->createEtiquetaXml()->createEtiquetaXml($dom,$etiquetasNode,$etiquetaObj);
		}
		
		return $dom;
	}
	
	private function saveXml()
	{
		$this->dom->formatOutput = true;
		
		$this->dom->save('xml/exportar/'.$this->usuarioObj->getUserName().'.xml');
		
		$zip = new ZipArchive();
		
		if($zip->open('xml/exportar/'.$this->usuarioObj->getUserName().'.zip',ZIPARCHIVE::OVERWRITE) !== true)
			return false;
		
		$zip->addFile('xml/exportar/'.$this->usuarioObj->getUserName().'.xml',$this->usuarioObj->getUserName().'.xml');
		$zip->close();
	}
	
	public function getZipFileUrl()
	{
		return 'Location: xml/exportar/'.$this->usuarioObj->getUserName().'.zip';
	}
} 
?>