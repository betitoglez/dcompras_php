<?php

namespace Dcompras;

abstract class Shop {
	
	protected $name;
	protected $id;
	
	protected $categories = array();
	
	protected $_lastRequest;
	protected $_lastResponse;
	
	public function getItemsCategory ($idCategory,$id){
		//Get URL and proceed
		if (is_array($idCategory)){
			$sBody = $this->getHTML($idCategory["url"]);
		}else{
			$sBody = $this->getHTML($idCategory);
		}		
		/*
		$oLog = DI::get("Log");
		$oLog->log("Hola", \Zend_Log::INFO);
		*/
		if (null === $sBody){
			$this->_errorCategory($id);
			return array();
		}else{	
			$aItems = $this->_searchItems($sBody);
			if (($sUrl = $this->_nextCategoryPage()) === false){
				return $aItems;
			}else{
				return array_merge($aItems,$this->getItemsCategory($sUrl, $id));
			}						
		}
		
	}
	
	abstract protected function _searchItems ($sBody);
	abstract protected function _nextCategoryPage ();
	
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
	
}

?>