<?php

use Dcompras\Mapping;
use Dcompras\DI;
/**
 * ApiController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';
class ApiController extends Zend_Controller_Action {
	
	public function init() {
		Zend_Layout::getMvcInstance()->disableLayout();
		header("Content-type: application/json");
	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated ApiController::indexAction() default action
	}
	
	public function categoriesAction () {
		$aMap = Mapping::getInstance("categories")->get();
		$this->view->map = $aMap;
	}
	
	public function productsAction () {
		$aFilters = array();

		if ($this->_request->has("id_store") && $this->_checkIn($this->_request->get("id_store"))){
			$aFilters["id_store"] = htmlspecialchars($this->_request->get("id_store"));
		}
		if ($this->_request->has("id_category")&& $this->_checkIn($this->_request->get("id_category")) ){
			$aFilters["id_category"] = htmlspecialchars($this->_request->get("id_category"));
		}
		
		if ($this->_request->has("price_min") && is_numeric($this->_request->get("price_min"))){
			$aFilters["price_min"] = floatval($this->_request->get("price_min"));
		}
		
		if ($this->_request->has("price_max") && is_numeric($this->_request->get("price_max"))){
			$aFilters["price_max"] = floatval($this->_request->get("price_max"));
		}
		
		if ($this->_request->has("discount") && is_numeric($this->_request->get("discount"))){
			$aFilters["discount"] = floatval($this->_request->get("discount"));
		}
		
		if ($this->_request->has("name")){
			$aFilters["name"] = htmlentities($this->_request->get("name"));
		}
		
		if ($this->_request->has("order")){
			$order = $this->_request->get("order");
		}else{
			$order = "id_desc";
		}
		
		
		if ($this->_request->has("offset") && is_numeric($this->_request->get("offset"))){
			$offset = (int) $this->_request->get("offset");
		}else{
			$offset = 0;
		}
		
		$aProducts = DI::get("Db")->products($aFilters,20,$offset,$order);
		$this->view->products = $aProducts;
	}
	
	public function storesAction () {
		$aStores = DI::get("Db")->stores();
		$this->view->stores = $aStores;
	}
	
	private function _checkIn ($string){
		$aExplode = explode(",",$string);
		foreach ($aExplode as $mValue){
			if (!is_numeric($mValue))
				return false;
		}
		return true;
	}
}
