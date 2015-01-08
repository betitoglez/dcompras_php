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
		$aProducts = DI::get("Db")->products($aFilters);
		$this->view->products = $aProducts;
	}
	
	public function storesAction () {
		$aStores = DI::get("Db")->stores();
		$this->view->stores = $aStores;
	}
}
