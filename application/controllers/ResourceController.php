<?php

/**
 * ResourceController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';
class ResourceController extends Zend_Controller_Action {
	
	public function init ()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}
	
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated ResourceController::indexAction() default action
		$this->imageAction();
	}

	
	public function imageAction () {
		$id = $this->_request->getParam("id");
		header("Content-type: image/png");
		$image = APPLICATION_PATH."/../images/$id.png";
		if (file_exists($image)){
			echo file_get_contents($image);
		}else{
			echo file_get_contents(APPLICATION_PATH . "/configs/404.png");
		}
	}
}
