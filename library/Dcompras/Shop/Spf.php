<?php

namespace Dcompras\Shop;

use Dcompras\Shop;
use Dcompras;
use Dcompras\Item\Generic;
use Dcompras\SelectorDOM;
use Dcompras\Image;

final class Spf extends Shop {
	
	protected $id = 25;
	protected $name = "Springfield";
	
	protected $categories = array(
		52 => array(
					"url" => "http://spf.com/es/tienda/man/sudaderas/s?per_page=999"
		) ,
		82 => array(
					"url" => "http://spf.com/es/tienda/man/abrigos-y-cazadoras/s?per_page=999"
		) ,
		87 => array(
					"url" => "http://spf.com/es/tienda/man/chaquetas/s?per_page=999"
		) ,
		182 => array(
					"url" => "http://spf.com/es/tienda/man/jerseis/s?per_page=999"
		) ,
		27 => array(
					"url" => "http://spf.com/es/tienda/man/camisas/s?per_page=999"
		) ,
		32 => array(
					"url" => "http://spf.com/es/tienda/man/camisetas/s?per_page=999"
		) ,
			
		122 => array(
					"url" => "http://spf.com/es/tienda/man/pantalones/s?per_page=999"
		) ,
			
		
	);
	
	
	
	
	protected function _searchItems($sBody){
		$oDomSelector = new Dcompras\SelectorDOM($sBody);
    	$aProducts = $oDomSelector->select("ul.product-listing > li",false);
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
		
		$a = $this->_itemSelector->select("div.product_info > a.sb_trackable")[0];		
		
		$name = isset($a["children"][0]["text"])?$a["children"][0]["text"]:null;
		$url  = isset($a["attributes"]["href"])?"http://spf.com".$a["attributes"]["href"]:null;


		$extid = isset($this->_itemSelector->select("*")[0]["attributes"]["id"])?sscanf($this->_itemSelector->select("*")[0]["attributes"]["id"], "product_%d"):null;
		if (is_array($extid)){
			$extid = $extid[0];
		}
		
		
		if (isset($this->_itemSelector->select("span.price")[0]["text"])){
			$_prices = utf8_decode($this->_itemSelector->select("span.price")[0]["text"]);
			$_count = substr_count($_prices, "?");
			if ($_count == 2){
				$_aPrices = explode("?",$_prices);
				$price = floatval(str_ireplace(",", ".", $_aPrices[1]));
				$oldprice = floatval(str_ireplace(",", ".", $_aPrices[0]));
			}else{
				$price = floatval(str_ireplace(",", ".", str_replace("?","",$_prices)));
				$oldprice = null;
			}
		}
		
		$img = isset($this->_itemSelector->select("img.front")[0]["attributes"]["src"])?$this->_itemSelector->select("img.front")[0]["attributes"]["src"]:null;
		
		$img = "http:".$img ;
		
		$oGen->price = $price;
		$oGen->oldprice = $oldprice;
		$oGen->name  = $name;
		$oGen->url   = utf8_encode($url);
		$oGen->extid = $this->id . "-" . $extid;
		$oGen->imgcusurl = utf8_encode($img);
		
		//Save the image url + name
		$this->saveImage($img ,$oGen->extid);
			
		return $oGen;	
	}



}

?>