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
	
	public function update ($table,Array $aColumns, $where)
	{
		return $this->adapter->update($table, $aColumns,$where); 
	}
	
	public function count ($table,$column,$value)
	{
		return (int) $this->adapter->fetchCol("SELECT COUNT(*) FROM " . $this->adapter->quoteTableAs($table) . 
				" WHERE ". $this->adapter->quoteIdentifier($column) ." = " . $this->adapter->quote($value))[0];
	}
	
	public function stores ()
	{
		$sSql = "SELECT * FROM stores order by name";
		
		$aResult = $this->adapter->fetchAll($sSql);
		
		return $aResult;
		
	}
	
	public function products (Array $aFilter = array(),$count = 20 , $offset = 0 , $order = "id")
	{
		$aAllowedFields = array ("id","name","price","price_min", "price_max","id_store","month","year","id_category" ,"discount");
		$aAllowedFieldsOrder = array ("id","name" ,"name_desc","price","price_desc", "id_store","date","date_desc" ,"id_category" , "discount");
		
		$oSelect = new \Zend_Db_Select($this->adapter);
		$oSelect->from("products")->columns(array("discount"=>new \Zend_Db_Expr("ABS(ROUND(100*(price-oldprice)/oldprice,0))")))->joinLeft(array("B"=>"product_category"), "products.id = B.id_product",array("id_category"))
		->joinLeft(array("C"=>"stores"), "products.id_store = C.id", array("storename"=>"C.name"))
		->joinLeft(array("D"=>"categories"), "B.id_category = D.id", array("categoryname"=>"D.name"));
		
		foreach ($aFilter as $key=>$value){
			if (in_array($key, $aAllowedFields)){
				if ($key == "id" && is_numeric($value)){
					$oSelect->where("id = ?", (int) $value);
				}
				else if ($key == "name"){
					$oSelect->where("products.name LIKE ?" , "%".$value."%");
				}
				else if ($key == "price_min" && is_numeric($value)){
					$oSelect->where("products.price >= ?" , floatval($value));
				}
				
				else if ($key == "price_max" && is_numeric($value)){
					$oSelect->where("products.price <= ?" , floatval($value));
				}
				
				else if ($key == "id_category"){
					$oSelect->where("B.id_category IN ($value) OR D.parent_id IN ($value)");				
				}
				else if ($key == "id_store"){
					$oSelect->where("products.id_store IN ($value)" );
				}
				else if ($key == "discount" && is_numeric($value)){
				 	$oSelect->having("discount >= ?" , $value);
				}
			}
		}
		
		//Order
		if (in_array($order, $aAllowedFieldsOrder)){
			if ($order == "id"){
				$order = "products.id";
			}else if ($order == "name"){
				$order = "products.name";
			}else if ($order == "name_desc"){
				$order = "products.name DESC";
			}else if ($order == "price"){
				$order = "products.price";
			}else if ($order == "price_desc"){
				$order = "products.price DESC";
			}else if ($order == "discount"){
				$oSelect->having("discount > 0");
				$order = "discount DESC";
			}else if ($order == "id_desc"){
				$order = "products.id DESC";
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
				"oldprice"=> $oItem->oldprice,
				"extid"=> $oItem->extid ,
				"name"=> $oItem->name
			));
			$id = $this->adapter->lastInsertId("products","id");
			$catid = $oItem->catid;
			$this->insert("product_category", array(
					"id_product" => $id,
					"id_category" => $catid
			));
			
		}else{
			$this->update("products", array(
				"id_store"=> $oItem->shopid,
				"url"=> $oItem->url ,
				"price"=> $oItem->price,
				"oldprice"=> $oItem->oldprice,
				"date_updated" => date("Y-m-d H:i:s"),
				"name"=> $oItem->name
			), array("extid = ?"=>$oItem->extid));
			$this->update("product_category",array(
					"id_category" => $oItem->catid
			), array("id_product = (SELECT id FROM products WHERE extid = ?)"=>$oItem->extid));
		}
	}


}
