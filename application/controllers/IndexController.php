<?php

use Dcompras\Item\Generic;
use Dcompras\Mapping;
use Dcompras\SelectorDOM;
use Dcompras\DI;

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	
    	$oHttpClient = DI::get("HttpClient");
    	$oHttpClient = new \Zend_Http_Client();
    	
    	$oHttpClient->setUri("http://localhost/test/category.htm");
    	$oResponse = $oHttpClient->request();
    	$sBody = html_entity_decode($oResponse->getBody());

 
    	$oDomSelector = new SelectorDOM($sBody);
    	$aImages = $oDomSelector->select("#products a.item img");
    	
    	var_dump($aImages);
    
    	
        // action body
        /*
        var_dump(Mapping::getInstance("Categories")->get());
        
        $oDomSelector = new SelectorDOM("<html><body><ul><li></li><li class='juk'><a href='fsdfsdfsdf' data-rel='sdfsdf'>dsdsd</a></li></ul></body></html>");
        var_dump($oDomSelector->select("li.juk")[0]);
       */
    }


}

