<?php

namespace Dcompras;

abstract class Shop {
	
	protected $name;
	protected $id;
	
	protected $categories = array();
	
	abstract public function getItemsCategory ($idCategory);
	
    public function getAllItems(){
	   $aItems = array();
       foreach ($this->categories as $category){
		   array_merge($aItems,$this->getItemsCategory($category));	   	
	   }    	
	   return $aItems;
    }
    
    
    public function getHTML ($sUri){
    	$oHttpClient = DI::get("HttpClient");
    	$oHttpClient->setUri($sUri);
    	$oResponse = $oHttpClient->request();
    	return $oResponse->getBody();
    }
	
}

?>