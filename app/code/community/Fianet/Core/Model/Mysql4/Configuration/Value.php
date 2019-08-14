<?php

class Fianet_Core_Model_Mysql4_Configuration_Value extends Fianet_Core_Model_Mysql4_Scope_Abstract 
{
	
	public function _construct()
	{
		$this->_init('fianet/configuration_value', 'code');
	}
}