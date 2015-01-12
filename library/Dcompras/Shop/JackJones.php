<?php

namespace Dcompras\Shop;

use Dcompras\Shop;
use Dcompras;
use Dcompras\DI;
use Dcompras\Item\Generic;
use Dcompras\SelectorDOM;
use Dcompras\Image;

final class JackJones extends Shop {
	
	protected $id = 30;
	protected $name = "Jack and Jones";
	
	protected $_cookies = array();
	
	protected $categories = array(
		52 => array(
			"url" => "http://jackjones.com/shop/sudaderas/jj-shop-sweatshirts,es_ES,sc.html?sz=12&format=ajax&" //Añadir start=XX
		)		,
		
		27 => array(
			"url" => "http://jackjones.com/shop/camisas/jj-shop-shirts,es_ES,sc.html?prefn1=qualifying-promotion-id&prefv1=searchfake-hidemarkdowns&prefn2=scopeFilter&prefv2=default&sz=12&forceScope=&parameterpaging=true&format=ajax&productsperrow=3&" //Añadir start=XX
		)		,	 
	);
	
	protected function _formatCategoryUrl ($url){
		return $url."&start=".$this->currentPage;
	}
	
	protected function _nextCategoryPage($sCurrentUrl) {
		if ((12+$this->currentPage) < $this->totalPages && $this->internalCount < 50){
			$this->currentPage += 12;
			$this->internalCount++;
			return $sCurrentUrl;
		}else{
			return false;
		}		
	}
	
	private function _setCookies ()
	{
		$fp = fsockopen("jackjones.com", 80, $errno, $errstr, 30);
		$result = "";
		if (!$fp) {
			echo "$errstr ($errno)<br />\n";
		} else {
			$out = "GET / HTTP/1.1\r\n";
			$out .= "Host: jackjones.com\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($fp, $out);
			while (!feof($fp)) {
				$result .= fgets($fp, 128);
			}
		
			fclose($fp);
		}
		$oResponse= \Zend_Http_Response::fromString($result);
		$this->_cookies = $oResponse->getHeader("Set-cookie");

		foreach ($this->_cookies as &$cookie){
			$cookie .= "; domain=jackjones.com";
			if (stripos($cookie, "my_country=NONE") !== false){
				$cookie = str_ireplace("my_country=NONE", "my_country=ES", $cookie);
			}
		}
	}
	
	public function getHTML($sUri){
		$oHttpClient = new \Zend_Http_Client();

		if (empty($this->_cookies)){
			$this->_setCookies();
		}

    	$oHttpClient->setUri($sUri);
    	
    	foreach ($this->_cookies as $cookie){
    		$oHttpClient->setCookie(\Zend_Http_Cookie::fromString($cookie));
    	}

    	$this->_lastRequest = $oHttpClient;
    	$oResponse = $oHttpClient->request(); 
    	$this->_lastResponse = $oResponse;    	

    	if ($oResponse->isSuccessful()){
    		return $oResponse->getBody();
    	}else{
    		$this->_error($oResponse,$oHttpClient);
    		return null;
    	}
	}
	
	
	
	protected function _searchItems($sBody){
		$oDomSelector = new Dcompras\SelectorDOM($sBody);
    	$aProducts = $oDomSelector->select("div.single_product",false);
    	
    	//Total amount
    	if ($this->currentPage == 0){
	    	$sAmount = $oDomSelector->select("div.sorthitscontainer > div.resultshits")[0]["text"];
	    	sscanf($sAmount, "%d resultados disponibles",$iAmount);
	    	$this->totalPages = $iAmount;
    	}
    	
    	$aResult = array();
    	foreach ($aProducts as $product){
    		$aResult[] = $this->_parseItem($product);
    	}
    	return $aResult;
	}
	

	
	/* (non-PHPdoc)
	 * @see \Dcompras\Shop::_parseItem()
	 */
	protected function _parseItem($oItem) {	

		$oGen = parent::_parseItem($oItem);	
		
		$a = $this->_itemSelector->select("div.name a")[0];
			
		
		$name = isset($a["text"])?utf8_decode(trim($a["text"])):null;
		$url  = isset($a["attributes"]["href"])?$a["attributes"]["href"]:null;
		
		
		$price = isset($this->_itemSelector->select("div.salesprice span")[0]["text"])?$this->_itemSelector->select("div.salesprice span")[0]["text"]:null;
		$price = str_ireplace(array("?"), "", utf8_decode($price));
		$price = floatval(str_replace(",", ".", $price));
		
		
		$oldprice = isset($this->_itemSelector->select("div.strikethrough")[0]["text"])?$this->_itemSelector->select("div.strikethrough")[0]["text"]:null;
		
		if ($oldprice){
			$oldprice = str_ireplace(array("?"), "", utf8_decode($oldprice));
			$oldprice = floatval(str_replace(",", ".", $oldprice));
		}
		
		
		$img = isset($this->_itemSelector->select("img.product_thumbnail")[0]["attributes"]["src"])?$this->_itemSelector->select("img.product_thumbnail")[0]["attributes"]["src"]:null;
		
		
		$oGen->price = $price;
		$oGen->oldprice = $oldprice;
		$oGen->name  = utf8_encode($name);
		$oGen->url   = utf8_encode($url);
		
		$id = explode( "/",$url);
		$extid = explode(",",array_pop($id))[0]; 
		$oGen->extid = $this->id."-".$extid;
		
		$oGen->imgcusurl = utf8_encode($img);
		$this->saveImage($img , $oGen->extid);
			
		return $oGen;	
	}



}

?>