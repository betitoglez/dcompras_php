<?php

namespace Dcompras;

use Dcompras\Mapping;

abstract class Mapping {
	
	protected $_aMap; 
	
	public function get(){
		return $this->_aMap;
	}
	
	public static function getInstance ($type){
		$class = "Dcompras\\Mapping\\$type";
		if (!class_exists($class)){
			throw new Exception("La clase $type no existe");
			return false;
		}
		return new $class;
	}
}

?>