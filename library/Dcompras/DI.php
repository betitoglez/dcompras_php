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
	 * @adapter Socket
	 * @return mixed|\Zend_Http_Client
	 */
	public function HttpClient ()
	{
		if (\Zend_Registry::isRegistered("HttpClient")){
			return \Zend_Registry::get("HttpClient");
		}
		
		$oHttpClient = new \Zend_Http_Client();
		\Zend_Registry::set("HttpClient", $oHttpClient);
		return $oHttpClient;
	}
}

?>