<?php

namespace Dcompras;

use Dcompras\Db\Service;
class DI {
	
	private static $self;
	private $config;
	
	public static function get ($type){
		if (!isset(self::$self)){
			self::$self = new DI;
			self::$self->config = \Zend_Registry::isRegistered("config")?\Zend_Registry::get("config"):null;
		}
		$aMethods = get_class_methods(self::$self);
		if (!in_array($type, $aMethods)){
			throw new \Exception("DI method does not exist");
			return false;
		}
		return self::$self->$type();
	}
	
	 
	/**
	 * 
	 * @return mixed|\Zend_Log
	 */
	public function Log ()
	{
		if (\Zend_Registry::isRegistered("Log")){
			return \Zend_Registry::get("Log");
		}
		
		/** Database Logging **/
		$params = array ('host'     => $this->config["database"]["host"],
				'username' => $this->config["database"]["username"],
				'password' => $this->config["database"]["password"],
				'dbname'   => $this->config["database"]["dbname"]);
		$db = \Zend_Db::factory('PDO_MYSQL', $params);
		
		$columnMapping = array('priority' => 'priority', 'message' => 'message',
				'priority_name'=>'priorityName','datetime'=>'timestamp');
		$dbWriter = new \Zend_Log_Writer_Db($db, 'logging', $columnMapping);
		
		
		$oLog = new \Zend_Log();
		$oFile = fopen(APPLICATION_PATH."/../logs/".date("d_m_Y").".txt", "a+");
		$oXMLFormatter = new \Zend_Log_Formatter_Xml();
		$oLogWriter = new \Zend_Log_Writer_Stream($oFile);	
		$oLog->addWriter($oLogWriter);
		
		$oLog->addWriter($dbWriter);
		
		
		\Zend_Registry::set("Log", $oLog);
		return $oLog;
	}
	
	/**
	 * @adapter Socket
	 * @return mixed|\Zend_Http_Client
	 */
	public function HttpClient ()
	{
		if (\Zend_Registry::isRegistered("HttpClient")){
			return \Zend_Registry::get("HttpClient");
		}
		
		$oHttpClient = new \Zend_Http_Client();
		$oHttpClient->setAdapter(new \Zend_Http_Client_Adapter_Curl());

		\Zend_Registry::set("HttpClient", $oHttpClient);
		return $oHttpClient;
	}
	
	
	public function Db ()
	{
		if (\Zend_Registry::isRegistered("Db")){
			return \Zend_Registry::get("Db");
		}
		$config = \Zend_Registry::get("config")["database"];
		$oService = new Service($config);
		\Zend_Registry::set("Db", $oService);
		return $oService;
	}
}

?>