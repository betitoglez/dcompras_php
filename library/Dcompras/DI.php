<?php

namespace Dcompras;

class DI {
	
	private static $self;
	
	public static function get ($type){
		if (!isset(self::$self)){
			self::$self = new DI;
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
		$oLog = new \Zend_Log();
		$oFile = fopen("C:/Prueba.txt", "w+");
		$oXMLFormatter = new \Zend_Log_Formatter_Xml();
		$oLogWriter = new \Zend_Log_Writer_Stream($oFile);	
		$oLog->addWriter($oLogWriter);
		
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
}

?>