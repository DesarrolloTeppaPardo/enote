<?php

require("classes/CustomException/CustomException.class.php");
require("classes/mySqlDao/mySqlDao.class.php");
require("classes/KLogger/KLogger.class.php");

require("classes/fabricaDominio/fabricaDominio.class.php");
require("classes/fabricaDao/fabricaDao.class.php");
require("classes/fabricaXml/fabricaXml.class.php");

require("classes/usuarioDao/usuarioDao.class.php");
require("classes/Usuario/Usuario.class.php");
require("classes/UsuarioXml/UsuarioXml.class.php");

require("classes/etiquetaXml/etiquetaXml.class.php");
require("classes/etiquetaDao/etiquetaDao.class.php");
require("classes/etiqueta/etiqueta.class.php");

require("classes/agendaXml/agendaXml.class.php");
require("classes/agendaDao/agendaDao.class.php");
require("classes/agenda/agenda.class.php");

require("classes/notaXml/notaXml.class.php");
require("classes/notaDao/notaDao.class.php");
require("classes/nota/nota.class.php");

require("classes/adjuntoXml/adjuntoXml.class.php");
require("classes/adjuntoDao/adjuntoDao.class.php");
require("classes/adjunto/adjunto.class.php");

require("classes/ImportarXml/ImportarXml.class.php");

class testImportarXml extends PHPUnit_Framework_TestCase
{
	private $ImportarXml;
	
	public function setUp()
	{
		$this->ImportarXml = new ImportarXml();
		$this->ImportarXml->setXmlFile('xml/importar/juan.xml');
	}
	
	public function testStartImport()
	{
		$this->ImportarXml->startImport();
		
		$this->assertTrue(fabricaDao::getInstance()->createUsuarioDao()->exist($this->ImportarXml->getUsuarioId()));
	}
}

?>