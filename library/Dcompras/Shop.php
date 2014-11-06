<?php

namespace Dcompras;

abstract class Shop {
	
	protected $name;
	protected $id;
	
	protected $categories = array();
	
	abstract public function getItemsCategory ($idCategory);
	
	
}

?>