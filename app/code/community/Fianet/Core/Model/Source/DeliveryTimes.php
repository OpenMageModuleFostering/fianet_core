<?php

class Fianet_Core_Model_Source_DeliveryTimes
{
	public function toOptionArray()
	{
		return array(
				array('value' => 1, 'label' => Mage::helper('fianet')->__('Express (moins de 24h)')),
				array('value' => 2, 'label' => Mage::helper('fianet')->__('Standard'))
					);
	}
}