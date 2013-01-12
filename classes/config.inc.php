<?php
/*
* The line below is to prevent any malicious attack via GET or POST
* when register_globals is set to TRUE
*/

if (ini_get('register_globals') == '1')
	$DOCTPATH = NULL;

/*
* If you ever need to use a path not under $_SERVER['DOCUMENT_ROOT'],
* then you will have to set the variable $DOCTPATH with the path on subject
* Uncomment the line below to do so
*/

//$DOCTPATH = '/';

/*
* Define path to classes... End with "/"
*/

define("DOC_PATH",dirname(@$DOCTPATH ? $DOCTPATH.'/.' : $_SERVER['DOCUMENT_ROOT'].'/.').'/enote/');
define("HTML_PATH",dirname(@$DOCTPATH ? $DOCTPATH.'/.' : $_SERVER['DOCUMENT_ROOT'].'/.').'/enote/');
define("LIB_PATH",DOC_PATH.'lib/');
define("STATIC_PATH",DOC_PATH.'static/');
define("CLASSES",DOC_PATH.'classes/');
define("URL_PATH",'/enote');
?>
