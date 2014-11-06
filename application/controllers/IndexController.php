<?php

use Dcompras\Item\Generic;
use Dcompras\Mapping;
use Dcompras\SelectorDOM;

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        
        var_dump(Mapping::getInstance("Categories")->get());
        
        $oDomSelector = new SelectorDOM("<html><body><ul><li></li><li class='juk'><a href='fsdfsdfsdf' data-rel='sdfsdf'>dsdsd</a></li></ul></body></html>");
        var_dump($oDomSelector->select("li.juk")[0]);
    }


}

