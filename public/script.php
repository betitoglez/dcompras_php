<?php
error_reporting(E_ALL);

use Dcompras\DI;
use Dcompras\Shop\Spf;
use Dcompras\Shop\JackJones;
use Dcompras\Mapping\Categories;
use Dcompras\Mapping;
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
    
// Define path to images directory
    defined('IMAGES_PATH')
    || define('IMAGES_PATH', realpath(dirname(__FILE__) . '/../images'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

spl_autoload_register(function($classname){
	if (substr($classname,0,5) == "Zend_"){
		return false;
	}
	require_once str_replace('\\', '/', $classname) . '.php';	
});

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

Zend_Registry::set("config",$application->getOptions());

//Synchronize Categories
Mapping::getInstance("categories")->synchronize();

if (isset($_SERVER["argv"])){
	$aArgs = $_SERVER["argv"];
	if (count($aArgs)==1){
		$sBasePath = realpath(APPLICATION_PATH . '/../library/Dcompras/Shop');
		$aFiles = scandir($sBasePath);
        foreach ($aFiles as $file){
        	if (is_file($sBasePath."/".$file)){
        		$file = str_ireplace(".php", "", $file);
        		exec("php script.php Dcompras\Shop\\$file",$aReturn,$iStatusCode);
        		$aReturn = null;
        	}
        }
	}else{
		$sClassName = $aArgs[1];
		$oStore = new $sClassName();
		$oStore->synchronize();
		$items = $oStore->getAllItems();
		foreach ($items as $item){
			DI::get("Db")->synchronizeItem($item);
		}
		
	}
}else{
	$oShow = new Spf();
	var_dump($oShow->getAllItems());
	//die();
}

