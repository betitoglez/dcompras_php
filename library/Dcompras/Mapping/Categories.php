<?php

namespace Dcompras\Mapping;

use Dcompras\Mapping;
use Dcompras\DI;

class Categories extends Mapping {
	
	protected $_aMap = array(
		"maps" => array(
				30 => array(
						"name" => "CAMISETAS",
						"parent" => null
				) ,
				31 => array(
						"name" => "CAMISETAS_MUJER",
						"parent" => 30
				) ,
				32 => array(
						"name" => "CAMISETAS_HOMBRE",
						"parent" => 30
				) ,
				50 => array(
						"name" => "SUDADERAS",
						"parent" => null
				) ,
				51 => array(
						"name" => "SUDADERAS_MUJER",
						"parent" => 50
				) ,
				52 => array(
						"name" => "SUDADERAS_HOMBRE",
						"parent" => 50
					 ) ,
				
		)
	);
	
	public function synchronize ()
	{
		foreach ($this->_aMap["maps"] as $id => $value){
		  if (DI::get("Db")->count("categories","id",$id) == 0){
		  	  DI::get("Db")->insert("categories",array(
		  	  		"id" => $id ,
		  	  		"name" => $value["name"] ,
		  	  		"parent_id" => $value["parent"]
		  	  ));
		  }
		}
	}
}

?>