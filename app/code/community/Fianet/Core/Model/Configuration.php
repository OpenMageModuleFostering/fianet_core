<?php

class Fianet_Core_Model_Configuration extends Mage_Core_Model_Abstract 
{
	protected function _construct()
	{
		parent::_construct();
		$this->_init('fianet/configuration');
	}
	
	public function getGlobalValue($key)
	{
		$value = null;
		$value = Mage::getModel('fianet/Configuration_global')
			->load($key)
			->Value;
		//Zend_Debug::dump($key . ' : '. $value);
		if (is_null($value))
		{
			Mage::getModel('fianet/log')->Log('Configuration global value ' . $key . ' not found.');
			Mage::throwException($this->__('Unable to load FIA-NET configuration.'));
		}
		return ((string)$value);
	}	
	
	public function getStoreValue($key, $store_id = 0)
	{
		$value = null;
		$value = Mage::getModel('fianet/Configuration_value')
					->setScope((integer)$store_id)
					->load($key)
					->Value;
		//Zend_Debug::dump($key . ' : ' . $value);
		if (is_null($value) && $store_id == 0)
		{
			Mage::getModel('fianet/log')->Log('Configuration store value ' . $key . ' for '.Mage::helper('fianet')->__(Mage::getModel('fianet/configuration_global')->load('CONFIGURATION_SCOPE')->Value).' '.$store_id.' not found.');
			Mage::throwException($this->__('Unable to load FIA-NET configuration.'));
		}
		return ((string)$value);
	}
	
	public function getValue($key, $store_id = 0)
	{
		return $this->getStoreValue($key, $store_id);
	}
	
	public static function CheckModuleIsInstalled($Name)
	{
		$modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
		sort($modules);
		foreach ($modules as $moduleName)
		{
			if ($moduleName == $Name)
			{
				return (true);
			}
		}
		return (false);
	}
	
	public static function SetDefaultConfig(array $values)
	{
		$config = Mage::getModel('fianet/configuration_value');
		$config->_scope_field;
		$config->setScope(0);
		//Zend_Debug::dump($values);
		$scopes = array('store_id', 'group_id', 'website_id');
		
		if (is_array($values))
		{
			foreach ($values as $key_config => $default_value)
			{
				$message = '';
				$is_set = false;
				foreach ($scopes as $scope)
				{
					$config->_scope_field = $scope;
					$value = $config->getValue($key_config);
					
					if ($value != null && $value != "")
					{
						$is_set = true;
					}
				}
				if (!$is_set)
				{
					$message = Mage::helper('fianet')->__('Check %s : not found, ', $key_config, $scope);
					foreach ($scopes as $scope)
					{
						$config->_scope_field = $scope;
						$config
							->load($key_config)
							->setId($key_config)
							->setValue($default_value)
							->save();
						$message .= Mage::helper('fianet')->__('set default value = %s.', $default_value);
						Mage::getModel('fianet/log')->Log($message);
					}
				}
				else
				{
					$message = Mage::helper('fianet')->__('Check %s : found.', $key_config);
					Mage::getModel('fianet/log')->Log($message);
				}
			}
		}
	}
}