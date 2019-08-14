<?php

class Fianet_Core_Model_Configuration_Global extends Mage_Core_Model_Abstract 
{
	protected function _construct()
	{
		parent::_construct();
		$this->_init('fianet/configuration_global');
	}
}