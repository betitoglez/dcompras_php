<?php

namespace Dcompras\Shop;

use Dcompras\Shop;
use Dcompras;
use Dcompras\Item\Generic;
use Dcompras\SelectorDOM;
use Dcompras\Image;

final class Zara extends Shop {
	
	protected $id = 20;
	protected $name = "Zara";
	
	protected $categories = array(
		52 => array(
			"url" => "http://www.zara.com/es/es/hombre/sudaderas-c309502.html"
		)		,
			
		31 => array(
			"url" => "http://www.zara.com/es/es/mujer/camisetas-c269189.html"		
		)
	);
	
	
	
	
	protected function _searchItems($sBody){
		$oDomSelector = new Dcompras\SelectorDOM($sBody);
    	$aProducts = $oDomSelector->select("#products li",false);
    	$aResult = array();
    	foreach ($aProducts as $product){
    		$aResult[] = $this->_parseItem($product);
    	}
    	return $aResult;
	}
	
	/* (non-PHPdoc)
	 * @see \Dcompras\Shop::_nextCategoryPage()
	 */
	protected function _nextCategoryPage($sCurrentUrl) {
		return false;
	}
	
	/* (non-PHPdoc)
	 * @see \Dcompras\Shop::_parseItem()
	 */
	protected function _parseItem($oItem) {	

		$oGen = parent::_parseItem($oItem);	
		$a = $this->_itemSelector->select("a.name.item")[0];
		
		$name = isset($a["text"])?$a["text"]:null;
		$url  = isset($a["attributes"]["href"])?$a["attributes"]["href"]:null;
		
		$a = $this->_itemSelector->select("a.gaProductDetailsLink.item")[0];
		$extid = isset($a["attributes"]["data-item"])?$a["attributes"]["data-item"]:null;
		
		$price = isset($this->_itemSelector->select("span.price span")[0]["attributes"]["data-ecirp"])?$this->_itemSelector->select("span.price span")[0]["attributes"]["data-ecirp"]:null;
		$price = floatval(str_replace(",", ".", $price));
		
		$img = isset($this->_itemSelector->select("img.product-img")[0]["attributes"]["data-src"])?$this->_itemSelector->select("img.product-img")[0]["attributes"]["data-src"]:null;
		
		
		$oGen->price = $price;
		$oGen->name  = utf8_decode($name);
		$oGen->url   = $url;
		$oGen->extid = $this->id . "-" . $extid;
		$oGen->imgcusurl = $img;
		
		//Save the image url + name
		$this->saveImage("http:".$img ,$oGen->extid);
			
		return $oGen;	
	}



}

?>