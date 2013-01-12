<?php 

class mySqlDao
{
	/* Object vars */
	private $DEFAULT_HOST	= 'localhost';
	private $DEFAULT_PORT	= '3306';
    private $DEFAULT_USER	= 'root';
    private $DEFAULT_PASS	= '';
	private $DEFAULT_DB		= 'enote';
	private $DEFAULT_LINK;
	
	private $log;
	
	private static $instance;
		
	private function __construct()
	{
		$this->log = new KLogger ( "../errores.log" , KLogger::DEBUG );
		
		$this->connect();
	}
	
	public static function getInstance()
	{
		if (!self::$instance)
			self::$instance = new self;
			
		return self::$instance;
	}
	
	final private function connect()
	{
		
		if (empty($this->DEFAULT_HOST) || empty($this->DEFAULT_USER) || empty($this->DEFAULT_DB))
		{
			$this->log->LogError("mySqlDao.class - connect - MySQLException: You must provide a valid host, databse and username to connect to MySQL");
			throw new MySQLException("You must provide a valid host, databse and username to connect to MySQL",6);
		}
		
		if (!($this->DEFAULT_LINK = mysql_connect($this->DEFAULT_HOST.($this->DEFAULT_PORT ? ':'.$this->DEFAULT_PORT : ''),$this->DEFAULT_USER,$this->DEFAULT_PASS)))
		{
			$this->log->LogError("mySqlDao.class - connect - MySQLException: Couldn't initialize class ".get_class($this).". Couldn't connect to MySql on host ".$this->DEFAULT_USER."@".$this->DEFAULT_HOST." using password ".($this->DEFAULT_PASS ? "YES" : "NO"));
			throw new MySQLException("Couldn't initialize class ".get_class($this).". Couldn't connect to MySql on host ".$this->DEFAULT_USER."@".$this->DEFAULT_HOST." using password ".($this->DEFAULT_PASS ? "YES" : "NO"),1);
		}
		
		if ($this->DEFAULT_DB && !(mysql_select_db($this->DEFAULT_DB,$this->DEFAULT_LINK)))
		{
			$this->log->LogError("mySqlDao.class - connect - MySQLException: Coudln't connect to MySql database ".$this->DEFAULT_DB." on host ".$this->DEFAULT_USER."@".$this->DEFAULT_HOST." using password ".($this->DEFAULT_PASS ? "YES" : "NO"));
			throw new MySQLException("Coudln't connect to MySql database ".$this->DEFAULT_DB." on host ".$this->DEFAULT_USER."@".$this->DEFAULT_HOST." using password ".($this->DEFAULT_PASS ? "YES" : "NO"),2);
		}
		
		mysql_set_charset('utf8',$this->DEFAULT_LINK);
		
		$this->log->LogInfo("mySqlDao.class - connect: Success");
		
		return true;
	}
	
	final public function getLink()
	{
		return $this->DEFAULT_LINK;
	}
}

class mySqlDaoException extends CustomException{}
	
?>