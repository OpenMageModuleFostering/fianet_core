<?php

class Fianet_Core_Model_Mysql4_Shipping_Association_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract 
{
	protected function _construct()
	{
		parent::_construct();
		$this->_init('fianet/shipping_association');
	}
}