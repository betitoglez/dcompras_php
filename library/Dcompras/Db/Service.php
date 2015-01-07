<?php

namespace Dcompras\Db;

use Dcompras\Item\Generic;
class Service {

	private $adapter;

	/**
	 * 
	 * @param array $config
	 * 
	 *  dbname => (string) The name of the database to user

	 	username => (string) Connect to the database as this username.

		password => (string) Password associated with the username.

		host => (string) What host to connect to, defaults to localhost

	 */
	public function __construct(Array $config){		
		$this->adapter = new \Zend_Db_Adapter_Pdo_Mysql($config);
	}
	
	public function insert ($table, Array $aColumns)
	{
		return $this->adapter->insert($table, $aColumns);
	}
	
	public function count ($table,$column,$value)
	{
		return (int) $this->adapter->fetchCol("SELECT COUNT(*) FROM " . $this->adapter->quoteTableAs($table) . 
				" WHERE ". $this->adapter->quoteIdentifier($column) ." = " . $this->adapter->quote($value))[0];
	}
	
	public function stores ()
	{
		$sSql = "SELECT * FROM stores";
		
		$aResult = $this->adapter->fetchAssoc($sSql);
		
		return $aResult;
		
	}
	
	public function products (Array $aFilter = array(),$count = 20 , $offset = 0 , $order = "id")
	{
		$aAllowedFields = array ("id","name","price","price_min", "price_max","id_store","month","year","id_category");
		$aAllowedFieldsOrder = array ("id","name" ,"name_desc","price","price_desc", "id_store","date","date_desc" ,"id_category");
		
		$oSelect = new \Zend_Db_Select($this->adapter);
		$oSelect->from("products")->joinLeft(array("B"=>"product_category"), "products.id = B.id_product");
		
		foreach ($aFilter as $key=>$value){
			if (in_array($key, $aAllowedFields)){
				if ($key == "id" && is_numeric($value)){
					$oSelect->where("id = ?", (int) $value);
				}
				else if ($key == "name"){
					$oSelect->where("name LIKE ?" , "%".$value."%");
				}
				else if ($key == "price_min" && is_numeric($value)){
					$oSelect->where("price >= ?" , floatval($value));
				}
				
				else if ($key == "price_max" && is_numeric($value)){
					$oSelect->where("price <= ?" , floatval($value));
				}
			}
		}
		
		//Order
		if (in_array($order, $aAllowedFieldsOrder)){
			if ($order == "id"){
				$order = "products.id";
			}else if ($order == "name"){
				$order = "name";
			}else if ($order == "name_desc"){
				$order = "name DESC";
			}else if ($order == "price"){
				$order = "price";
			}else if ($order == "price_desc"){
				$order = "price DESC";
			}
		}else{
			$order = "products.id";
		}
	
		$oSelect->limit($count,$offset)->order($order);
		
		$aResult = $this->adapter->fetchAll($oSelect);
	
		return $aResult;
	}
	
	public function synchronizeItem (Generic $oItem)
	{
		if ($this->count("products", "extid", $oItem->extid) == 0){
			$this->insert("products", array(
				"id_store"=> $oItem->shopid,
				"url"=> $oItem->url ,
				"image"=> $oItem->imgcusurl,
				"price"=> $oItem->price,
				"extid"=> $oItem->extid ,
				"name"=> $oItem->name
			));
			$id = $this->adapter->lastInsertId("products","id");
			$catid = $oItem->catid;
			$this->insert("product_category", array(
					"id_product" => $id,
					"id_category" => $catid
			));
			
		}
	}


}