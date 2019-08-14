<?php

class Fianet_Core_Model_Mysql4_Configuration_Global extends Fianet_Core_Model_Mysql4_Abstract 
{
	protected function _construct()
	{
		$this->_init('fianet/configuration_value', 'code');
	}
	
	public function load(Mage_Core_Model_Abstract $object, $code, $storeid = 0, $field = null)
	{
		parent::load($object, $code, $storeid, $field);
		
		
		
		if ($object->Value == '')
		{
			$object->Value = Mage::getModel('fianet/configuration')->load($code)->Default_value;
		}
		$this->_afterLoad($object);
		return $this;
	}
}