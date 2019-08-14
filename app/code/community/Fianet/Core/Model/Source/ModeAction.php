<?php

class Fianet_Core_Model_Source_ModeAction
{
	public function toOptionArray()
	{
		return array(
				array('value' => 'TEST', 'label' => Mage::helper('receiveandpay')->__('Test')),
				array('value' => 'PRODUCTION', 'label' => Mage::helper('receiveandpay')->__('Production'))
				);
	}
}