<?php

class Fianet_Core_Model_Mysql4_Configuration_Value_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract 
{
	protected function _construct()
	{
		parent::_construct();
		$this->_init('fianet/configuration_value');
	}
}