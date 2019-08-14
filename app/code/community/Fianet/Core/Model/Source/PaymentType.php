<?php

class Fianet_Core_Model_Source_PaymentType
{
	public function toOptionArray()
	{
		return array(
				array('value' => 'carte', 'label' => Mage::helper('fianet')->__('Carte bancaire')),
				array('value' => 'cheque', 'label' => Mage::helper('fianet')->__('Ch&egrave;que')),
				array('value' => 'contre-remboursement', 'label' => Mage::helper('fianet')->__('Contre-remboursement')),
				array('value' => 'virement', 'label' => Mage::helper('fianet')->__('Virement')),
				array('value' => 'cb en n fois', 'label' => Mage::helper('fianet')->__('Carte bancaire en plusieurs fois')),
				array('value' => 'paypal', 'label' => Mage::helper('fianet')->__('Paypal'))
				);
	}
}