<?php

class Fianet_Core_Model_Fianet_Order_Info_Productlist
{
	protected $products_list = array();
	
	public function __construct()
	{
	}
	
	public function add_product($product)
	{
		if (Mage::getModel('fianet/functions')->var_is_object_of_class($product, 'Fianet_Core_Model_Fianet_Order_Info_ProductList_Product'))
		{
			$this->products_list[] = $product;
		}
		else
		{
			Mage::throwException("Mage_Fianet_Model_Fianet_Order_Info_Productlist::add_product() - Data are not a valid Mage_Fianet_Model_Fianet_Order_Info_Productlist_Product type");
		}
	}
	
	public function get_xml()
	{
		$xml = '';
		if (count($this->products_list) > 0)
		{
			
			$xml .= "\t\t". '<list nbproduit="'.$this->count_nbproduct().'">' . "\n";
			foreach ($this->products_list as $product)
			{
				$xml .= $product->get_xml();
			}
			$xml .= "\t\t". '</list>' . "\n";
		}
		return ($xml);
	}
	
	protected function count_nbproduct()
	{
		$n = 0;
		foreach ($this->products_list as $product)
		{
			$n += $product->nb;
		}
		return ($n);
	}
}
