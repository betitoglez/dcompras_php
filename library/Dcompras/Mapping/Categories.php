<?php

namespace Dcompras\Mapping;

use Dcompras\Mapping;
use Dcompras\DI;

class Categories extends Mapping {
	
	protected $_aMap = array(
		"maps" => array(
				25 => array(
						"name" => "CAMISAS",
						"parent" => null
				) ,
				26 => array(
						"name" => "CAMISAS_MUJER",
						"parent" => 25
				) ,
				27 => array(
						"name" => "CAMISAS_HOMBRE",
						"parent" => 25
				) ,
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
				80 => array(
						"name" => "ABRIGOS",
						"parent" => null
				) ,
				81 => array(
						"name" => "ABRIGOS_MUJER",
						"parent" => 80
				) ,
				82 => array(
						"name" => "ABRIGOS_HOMBRE",
						"parent" => 80
				) ,
				85 => array(
						"name" => "CHAQUETAS",
						"parent" => null
				) ,
				86 => array(
						"name" => "CHAQUETAS_MUJER",
						"parent" => 85
				) ,
				87 => array(
						"name" => "CHAQUETAS_HOMBRE",
						"parent" => 85
				) ,
				
				90 => array(
						"name" => "AMERICANAS",
						"parent" => null
				) ,
				91 => array(
						"name" => "AMERICANAS_MUJER",
						"parent" => 90
				) ,
				92 => array(
						"name" => "AMERICANAS_HOMBRE",
						"parent" => 90
				) ,
				
				100 => array(
						"name" => "VESTIDOS",
						"parent" => null
				) ,
				101 => array(
						"name" => "TRAJES",
						"parent" => null
				) ,
				
				110 => array(
						"name" => "JEANS",
						"parent" => null
				) ,
				111 => array(
						"name" => "JEANS_MUJER",
						"parent" => 110
				) ,
				112 => array(
						"name" => "JEANS_HOMBRE",
						"parent" => 110
				) ,
				
				120 => array(
						"name" => "PANTALONES",
						"parent" => null
				) ,
				121 => array(
						"name" => "PANTALONES_MUJER",
						"parent" => 120
				) ,
				122 => array(
						"name" => "PANTALONES_HOMBRE",
						"parent" => 120
				) ,
				
				130 => array(
						"name" => "PUNTO",
						"parent" => null
				) ,
				131 => array(
						"name" => "PUNTO_MUJER",
						"parent" => 130
				) ,
				132 => array(
						"name" => "PUNTO_HOMBRE",
						"parent" => 130
				) ,
				
				140 => array(
						"name" => "ACCESORIOS",
						"parent" => null
				) ,
				141 => array(
						"name" => "ACCESORIOS_MUJER",
						"parent" => 140
				) ,
				142 => array(
						"name" => "ACCESORIOS_HOMBRE",
						"parent" => 140
				) ,
				
				
				150 => array(
						"name" => "CALZADO",
						"parent" => null
				) ,
				151 => array(
						"name" => "CALZADO_MUJER",
						"parent" => 150
				) ,
				152 => array(
						"name" => "CALZADO_HOMBRE",
						"parent" => 150
				) ,
				
				160 => array(
						"name" => "PIJAMAS",
						"parent" => null
				) ,
				161 => array(
						"name" => "PIJAMAS_MUJER",
						"parent" => 160
				) ,
				162 => array(
						"name" => "PIJAMAS_HOMBRE",
						"parent" => 160
				) ,
				
				170 => array(
						"name" => "ROPA_INTERIOR",
						"parent" => null
				) ,
				171 => array(
						"name" => "ROPA_INTERIOR_MUJER",
						"parent" => 170
				) ,
				172 => array(
						"name" => "ROPA_INTERIOR_HOMBRE",
						"parent" => 170
				) ,
				
				180 => array(
						"name" => "JERSEIS_CARDIGANS",
						"parent" => null
				) ,
				181 => array(
						"name" => "JERSEIS_CARDIGANS_MUJER",
						"parent" => 180
				) ,
				182 => array(
						"name" => "JERSEIS_CARDIGANS_HOMBRE",
						"parent" => 180
				) ,
				
				190 => array(
						"name" => "FALDAS",
						"parent" => null
				) ,
				200 => array(
						"name" => "TOPS",
						"parent" => null
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