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
		if ($this->_request->has("id_store") && is_numeric($this->_request->get("id_store"))){
			$aFilters["id_store"] = (int) $this->_request->get("id_store");
		}
		if ($this->_request->has("id_category") && is_numeric($this->_request->get("id_category"))){
			$aFilters["id_category"] = (int) $this->_request->get("id_category");
		}
		if ($this->_request->has("offset") && is_numeric($this->_request->get("offset"))){
			$offset = (int) $this->_request->get("offset");
		}else{
			$offset = 0;
		}
		
		$aProducts = DI::get("Db")->products($aFilters,20,$offset);
		$this->view->products = $aProducts;
	}
	
	public function storesAction () {
		$aStores = DI::get("Db")->stores();
		$this->view->stores = $aStores;
	}
}
