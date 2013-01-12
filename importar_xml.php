<?php 

require("classes/main.class.php");

$allowedExts = array("xml");

$extension = end(explode(".", $_FILES["file"]["name"]));

if( ($_FILES["file"]["type"] == "text/xml") && in_array($extension, $allowedExts) )
{
  if ($_FILES["file"]["error"] > 0)
    echo "Error: " . $_FILES["file"]["error"] . "<br>";
  else
  	move_uploaded_file($_FILES["file"]["tmp_name"],"xml/importar/" . $_FILES["file"]["name"]);
}
else
  echo "Invalid file";
  
$ImportarXml = fabricaXml::getInstance()->createImportarXml();

$ImportarXml->setXmlFile('xml/importar/'.$_FILES["file"]["name"]);

$ImportarXml->startImport();

header( 'Location: index.php' ) ;
exit();
?>