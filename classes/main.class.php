<?php

date_default_timezone_set('America/Caracas');

/**
* This is very important to fix bugs setting cookies and sessions into iframes in IE and Safari
*/

header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

/**
* We are gonna require some files such as the configuration file (config.inc.php)
* and the CustomException class (CustomException/CustomException.class.php)
*/

require_once('config.inc.php');
require_once('CustomException/CustomException.class.php');

/**
* Function to autoload classes
*/

class MAIN_Autoloader
{
	public static function load($class_name)
	{
		/*
		* Common class
		*/
		
		$class_file = CLASSES.$class_name.".class.php";
				
		if (!is_dir(CLASSES))
			return false;
		
		if (!($Handler = @opendir(CLASSES)) || !is_resource($Handler))
			return false;
		
		//-- Get packages if any

		$Packages = array();
		
		while ($File = readdir($Handler))
		{
			if ($File != '.' && $File != '..' && is_dir(CLASSES.$File))
				$Packages[] = $File;
		}
		
		//-- Packages
		
		if (preg_match("/^(".implode('|',$Packages).")((\_.+)|$)/",$class_name,$Package))
			$class_file = CLASSES.$Package[1].'/'.$Package[0].".class.php";
		
		if (!file_exists($class_file))
			return false;
		
		require_once($class_file);
	}
	
	public static function register()
	{
		/*
		* Specify extensions that may be loaded
		*/
		
		spl_autoload_extensions('.php, .class.php, .lib.php');
		
		/*
		* register the loader functions
		*/
		
		spl_autoload_register(array('MAIN_Autoloader', 'load'));
	}
}

MAIN_Autoloader::register();

class Main
{	
	private static $ERROR					= array();
	private static $URL_PATTERN				= '/(?=^|[\s])http[s]?\:\/\/((([w]{3}\.)?([a-z0-9\-\.]+\.[a-z]{2,4}))|[0-9\.]+)(:[0-9]{,4})?(\/[^\s]*)?(?=$|[\s])/msi';
	
	private static $CURL_DEFAULT_SETTINGS	= array
	(
		/*CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_AUTOREFERER => true,*/
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 10,
		CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8'
	);
	
	/**
     * @static
     * @param $Url
     * @param array $Options
     * @return bool|mixed
     * @throws Exception
     */
    public static function getUrl($Url,$Options = array())
	{
		if (!($ch = curl_init($Url)))
			throw new Exception("Couldn't initialize cURL library",100);
		
		if (is_array(self::$CURL_DEFAULT_SETTINGS) && count(self::$CURL_DEFAULT_SETTINGS) > 0)
			curl_setopt_array($ch,self::$CURL_DEFAULT_SETTINGS);
		
		if ($_SERVER['WINDIR'] && preg_match('/^http[s]?:\/\//i',$Url))
			curl_setopt($ch,CURLOPT_CAINFO,LIB_PATH.'cacert.pem');
		
		if (is_array($Options) && count($Options) > 0)
		{
			foreach ($Options as $k => $v)
			{
				curl_setopt($ch,$k,$v);
			}
		}
		
		$Data = curl_exec($ch);
		$Error = curl_error($ch);
		curl_close($ch);
		
		if (!$Data)
		{
			if ($Error)
				throw new Exception($Error);
			
			return false;
		}

		return $Data;
	}
	
	/* Is number */
	public static function isInteger($v)
	{
		return (preg_match('/^[0-9]+$/',$v));
	}
	
	/* Retrieve last errors */
	public static function getError($e = 'l')
	{
		$e = trim(strtolower($e));
		return $e == 'l' ? end($this->ERROR) : $l == 'f' ? $this->ERROR[0] : $this->ERROR;
	}

	/* Fix continuos spaces */
	public static function fixStrSpaces($Str)
	{
		return trim(preg_replace('/[\s]+/',' ',$Str));
	}
	
	public static function logError($Error,$Log = false)
	{
		$Debug = debug_backtrace();
		$this->ERROR[] = $ERROR;
		$UID = $this->_getUID();
		
		/*
		mysql_query("INSERT INTO `log` (`IP`,`string`,`timestamp`,`function`,`line`,`file`,`args`,`uid`) VALUES ('".$_SERVER['REMOTE_ADDR']."','".addslashes($Error)."',UNIX_TIMESTAMP(),'{$Debug[1]['function']}','{$Debug[1]['line']}','{$Debug[1]['file']}','".implode(' - ',$Debug[1]['args'])."','$UID');",$this->MySQL);
		*/
	}
}
?>