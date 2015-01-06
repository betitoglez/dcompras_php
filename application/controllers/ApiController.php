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
		$aProducts = DI::get("Db")->products(array("price_min"=>"19.90", "price_max"=>"29.99"));
		$this->view->products = $aProducts;
	}
	
	public function storesAction () {
		$aStores = DI::get("Db")->stores();
		$this->view->stores = $aStores;
	}
}
