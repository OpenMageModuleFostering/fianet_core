<?php

class Fianet_Core_Model_Mysql4_Shipping_Association extends Fianet_Core_Model_Mysql4_Abstract 
{
	protected function _construct()
	{
		$this->_init('fianet/shipping_association', 'shipping_code');
	}
}