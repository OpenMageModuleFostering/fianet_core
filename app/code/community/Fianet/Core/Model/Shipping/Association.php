<?php

class Fianet_Core_Model_Shipping_Association extends Mage_Core_Model_Abstract 
{
	protected function _construct()
	{
		parent::_construct();
		$this->_init('fianet/shipping_association');
	}
}