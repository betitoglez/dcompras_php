<?php

namespace Dcompras\Shop;

use Dcompras\Shop;
use Dcompras;

final class Zara extends Shop {
	
	protected $id = 20;
	protected $name = "zara";
	
	protected $categories = array(
		50 => array(
			"url" => "http://www.zara.com/es/es/hombre/sudaderas-c309502.html"
		)		
	);
	
	protected function _searchItems($sBody){
		$oDomSelector = new Dcompras\SelectorDOM($sBody);
    	$aImages = $oDomSelector->select("#products a.item img");
    	return $aImages;
	}

}

?>