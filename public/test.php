<?php
// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
realpath(APPLICATION_PATH . '/../library'),
get_include_path(),
)));

spl_autoload_register(function($classname){
	if (substr($classname,0,5) == "Zend_"){
	//	return false;
		require_once str_replace('_', "/", $classname) . '.php';
	}else{
		require_once str_replace('\\', '/', $classname) . '.php';
	}
	
});

/*
$ch = curl_init("http://jackjones.com/shop/sudaderas/jj-shop-sweatshirts,es_ES,sc.html?prefn1=qualifying-promotion-id&prefv1=searchfake-hidemarkdowns&prefn2=scopeFilter&prefv2=default&start=60&sz=12&forceScope=&parameterpaging=true&format=pageelement&productsperrow=3");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);      
        curl_close($ch);
        echo $output;
        
     */
ob_start();
echo "<html><head></head><body><pre>";
$fp = fsockopen("jackjones.com", 80, $errno, $errstr, 30);
$result = "";
if (!$fp) {
	echo "$errstr ($errno)<br />\n";
} else {
$out = "GET /shop/sudaderas/jj-shop-sweatshirts,es_ES,sc.html?prefn1=qualifying-promotion-id&prefv1=searchfake-hidemarkdowns&prefn2=scopeFilter&prefv2=default&start=60&sz=12&forceScope=&parameterpaging=true&format=pageelement&productsperrow=3 HTTP/1.1\r\n";
		$out .= "Host: jackjones.com\r\n";
    $out .= "Connection: Close\r\n\r\n";
    fwrite($fp, $out);
    while (!feof($fp)) {
   	 $result .= fgets($fp, 128);
    }
    
    fclose($fp);
}
echo "</pre></body></html>";

ob_end_flush();

$oResponse= Zend_Http_Response::fromString($result);

var_dump($oResponse->getHeader("Set-cookie"));
echo($oResponse->getHeader("Location"));
