<?php

class Fianet_Core_Model_Configuration_Value extends Fianet_Core_Model_Scope_Abstract 
{
	protected function _construct()
	{
		parent::_construct();
		$this->_init('fianet/configuration_value');
	}	
}