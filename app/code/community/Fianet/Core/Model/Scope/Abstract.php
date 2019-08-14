<?php

class Fianet_Core_Model_Scope_Abstract extends Mage_Core_Model_Abstract 
{
	public $_scopeField;
	
	protected function _construct()
	{
		parent::_construct();
		$this->_scopeField = Mage::getModel('fianet/configuration_global')->load('CONFIGURATION_SCOPE')->Value;
	}
	
	public function setScope($value)
	{
		//Zend_Debug::dump('setScope ' . $value);
		if ($this->_scopeField == 'store_id')
		{
			$this->Store_id = (integer)$value;
		}
		elseif ($this->_scopeField == 'group_id')
		{
			$this->Group_id = (integer)$value;
		}
		elseif ($this->_scopeField == 'website_id')
		{
			$this->Website_id = (integer)$value;
		}
		return ($this);
	}
	
	public function getScope()
	{
		//Zend_Debug::dump('getScope');
		if ($this->_scopeField == 'store_id')
		{
			return $this->Store_id;
		}
		elseif ($this->_scopeField == 'group_id')
		{
			return $this->Group_id;
		}
		elseif ($this->_scopeField == 'website_id')
		{
			return $this->Website_id;
		}
	}
	
	public function setScopeField($value)
	{
		$this->_scopeField = $value;
		return $this;
	}
	
}