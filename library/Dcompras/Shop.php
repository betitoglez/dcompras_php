<?php

namespace Dcompras;

use Dcompras\Item\Generic;
abstract class Shop {
	
	protected $name;
	protected $id;
	
	protected $currentCatId;
	
	protected $categories = array();
	
	protected $_lastRequest;
	protected $_lastResponse;
	
	//Internal
	protected $_itemSelector;
	
	protected $currentPage = 0;
	protected $totalPages;
	protected $internalCount = 0;
	
	/**@Overridable**/
	protected function _formatCategoryUrl ($url){
		return $url;
	}
	
	public function getItemsCategory ($idCategory,$id){
		$this->currentCatId = $id;
		//Get URL and proceed
		if (is_array($idCategory)){
			$sCurrentUrl = $this->_formatCategoryUrl($idCategory["url"]);
			$sBody = $this->getHTML($sCurrentUrl);
		}else{
			$sCurrentUrl = $idCategory;
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
			$sUrl = $this->_nextCategoryPage($sCurrentUrl);
			if ($sUrl === false){
				return $aItems;
			}else{
				return array_merge($aItems,$this->getItemsCategory($sUrl, $id));
			}						
		}
		
	}
	
	abstract protected function _searchItems ($sBody);
	protected function _parseItem ($oItem){
		$oDocument = new \DOMDocument;
		$cloned = $oItem->cloneNode(TRUE);
		$oDocument->appendChild($oDocument->importNode($cloned,TRUE));
		$oAuxSelector = new SelectorDOM($oDocument);
		$this->_itemSelector = $oAuxSelector;
		
		$oGeneric = new Generic();
		$oGeneric->shopid = $this->id;
		$oGeneric->catid  = $this->currentCatId;
		return $oGeneric; 
	}
	abstract protected function _nextCategoryPage ($sCurrentUrl);
	
    public function getAllItems(){
	   $aItems = array();
	   
       foreach ($this->categories as $id=>$category){
       	   $this->internalCount = 0;
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