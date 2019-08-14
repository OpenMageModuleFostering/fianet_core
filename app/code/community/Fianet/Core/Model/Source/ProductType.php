<?php

class Fianet_Core_Model_Source_ProductType
{
	public function toOptionArray()
	{
		return array(
				array('value' => 1, 'label' => Mage::helper('fianet')->__('Alimentation & gastronomie')),
				array('value' => 2, 'label' => Mage::helper('fianet')->__('Auto & moto')),
				array('value' => 3, 'label' => Mage::helper('fianet')->__('Culture & divertissements')),
				array('value' => 4, 'label' => Mage::helper('fianet')->__('Maison & jardin')),
				array('value' => 5, 'label' => Mage::helper('fianet')->__('Electrom&eacute;nager')),
				array('value' => 6, 'label' => Mage::helper('fianet')->__('Ench&egrave;res et achats group&eacute;s')),
				array('value' => 7, 'label' => Mage::helper('fianet')->__('Fleurs & cadeaux')),
				array('value' => 8, 'label' => Mage::helper('fianet')->__('Informatique & logiciels')),
				array('value' => 9, 'label' => Mage::helper('fianet')->__('Sant&eacute; & beaut&eacute;')),
				array('value' => 10, 'label' => Mage::helper('fianet')->__('Services aux particuliers')),
				array('value' => 11, 'label' => Mage::helper('fianet')->__('Services aux professionnels')),
				array('value' => 12, 'label' => Mage::helper('fianet')->__('Sport')),
				array('value' => 13, 'label' => Mage::helper('fianet')->__('V&ecirc;tements & accessoires')),
				array('value' => 14, 'label' => Mage::helper('fianet')->__('Voyage & tourisme')),
				array('value' => 15, 'label' => Mage::helper('fianet')->__('Hifi, photo & vid&eacute;os')),
				array('value' => 16, 'label' => Mage::helper('fianet')->__('T&eacute;l&eacute;phonie & communication')),
				array('value' => 17, 'label' => Mage::helper('fianet')->__('Bijoux et m&eacute;taux pr&eacute;cieux')),
				array('value' => 18, 'label' => Mage::helper('fianet')->__('Articles et accessoires pour b&eacute;b&eacute;')),
				array('value' => 19, 'label' => Mage::helper('fianet')->__('Sonorisation & lumi&egrave;re'))
				);
	}
}