<?php

class Fianet_Core_Model_Carrier_Abstract extends Mage_Shipping_Model_Carrier_Abstract
{
	public function getCode(Mage_Shipping_Model_Carrier_Abstract $shipping)
	{
		$string = serialize($shipping);
		$className = 'Fianet_Core_Model_Carrier_Abstract';
		$len = strlen($className);
		
		eregi('^O:([0-9]+):"([a-zA-Z0-9_]+)":', $string, $data);
		$string = str_replace('O:'.$data[1], 'O:'.$len, $string);
		$string = str_replace($data[2], $className, $string);
		
		$object = unserialize($string);
		return ($object->_code);
	}
	
	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{
		return null;
	}
}