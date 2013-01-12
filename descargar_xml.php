<?php 

require("classes/main.class.php");

session_start();

if ( !isset($_SESSION['userId']) || !isset($_SESSION['userGoogleDriveId']) )
	header( 'Location: index.php' ) ;
	
$ExportarXml = fabricaXml::getInstance()->createExportarXml();

$ExportarXml->startExport($_SESSION['userId']);

header($ExportarXml->getZipFileUrl()) ;

?>