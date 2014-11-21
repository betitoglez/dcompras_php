<?php

namespace Dcompras;

abstract class Shop {
	
	protected $name;
	protected $id;
	
	protected $categories = array();
	
	public function getItemsCategory ($idCategory,$id){
		//Get URL and proceed
		$sBody = $this->getHTML($idCategory["url"]);		
		if (null === $sBody){
			$this->_errorCategory($id);
			return array();
		}else{		
			$aItems = $this->_searchItems($sBody);
			return $aItems;
		}
		
	}
	
	abstract protected function _searchItems ($sBody);
	
    public function getAllItems(){
	   $aItems = array();
       foreach ($this->categories as $id=>$category){
		   $aItems = array_merge($aItems,$this->getItemsCategory($category,$id));	   	
	   }    	
	   return $aItems;
    }
    
    protected function _error (\Zend_Http_Response $oResponse , \Zend_Http_Client $oRequest){
    	
    } 
    
    protected function _errorCategory ($idCategory)
    {
    	
    }
    
    public function getHTML ($sUri){
    	$oHttpClient = DI::get("HttpClient");
    	$oHttpClient->setUri($sUri);
    	$oResponse = $oHttpClient->request();   	   	
    	if ($oResponse->isSuccessful()){
    		return $oResponse->getBody();
    	}else{
    		$this->_error($oResponse,$oHttpClient);
    		return null;
    	}
    }
	
}

?>