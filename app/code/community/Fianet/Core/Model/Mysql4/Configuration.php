<?php

class Fianet_Core_Model_Mysql4_Configuration extends Mage_Core_Model_Mysql4_Abstract 
{
	protected function _construct()
	{
		$this->_init('fianet/configuration', 'code');
	}
}