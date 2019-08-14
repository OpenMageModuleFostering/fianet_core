<?php
$installer = $this;
$installer->startSetup();
/*
$config = array(
		'XML_ENCODING'=>'UTF-8',
		'DEFAULT_TYPE_PRODUCT'=>'1',
		'CONFIGURATION_SCOPE'=>'group_id'
		);
Fianet_Core_Model_Configuration::SetDefaultConfig($config);
*/
$shipping_list	= Mage::getModel('fianet/MageConfiguration')
	->getShippingMethods();
foreach ($shipping_list as $Code => $label)
{
	Mage::getModel('fianet/shipping_association')
		->load($Code)
		->setShipping_code($Code)
		->setFianet_shipping_type('4')
		->setDelivery_times('2')
		->setConveyor_name('A definir')
		->save();
}
$installer->endSetup();

?>