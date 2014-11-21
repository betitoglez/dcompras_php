<?php

namespace Dcompras\Item;

use Dcompras\Item;

class Generic extends Item {
	
	
	private $_aAttrs = array();
	
	
	public function __set($attribute,$value){
		$this->_aAttrs[$attribute] = $value;
	}
	
	public function __get($attribute){
		return isset($this->_aAttrs[$attribute])?$this->_aAttrs[$attribute]:null;
	}

	
	public function exist ($attribute){
		return array_key_exists($attribute, $this->_aAttrs);
	}

}

