<?php

class Fianet_Core_Model_Source_ShippingType
{
	public function toOptionArray()
	{
		return array(
				array('value' => 1, 'label' => Mage::helper('fianet')->__('Retrait de la marchandise chez le marchand')),
				array('value' => 2, 'label' => Mage::helper('fianet')->__('Utilisation d\'un r&eacute;seau de points-retrait tiers')),
				array('value' => 3, 'label' => Mage::helper('fianet')->__('Retrait dans un a&eacute;roport, une gare ou une agence de voyage')),
				array('value' => 4, 'label' => Mage::helper('fianet')->__('Transporteur')),
				array('value' => 5, 'label' => Mage::helper('fianet')->__('Emission d\'un billet &eacute;lectronique, t&eacute;l&eacute;chargements'))
				);
	}
}