<?php

/**
 * 2000-2012 FIA-NET
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is available
 * through the world-wide-web at this URL: http://www.opensource.org/licenses/OSL-3.0
 * If you are unable to obtain it through the world-wide-web, please contact us
 * via http://www.fia-net-group.com/formulaire.php so we can send you a copy immediately.
 *
 *  @author FIA-NET <support-boutique@fia-net.com>
 *  @copyright 2000-2012 FIA-NET
 *  @version Release: $Revision: 1.0.1 $
 *  @license http://www.opensource.org/licenses/OSL-3.0  Open Software License (OSL 3.0)
 */
?>
<?php

$installer = $this;
$installer->startSetup();
/*
$config = array(
    'XML_ENCODING' => 'UTF-8',
    'DEFAULT_TYPE_PRODUCT' => '1',
    'CONFIGURATION_SCOPE' => 'group_id'
);
Fianet_Core_Model_Configuration::SetDefaultConfig($config);
*/
$shippingList = Mage::getModel('fianet/mageConfiguration')->getShippingMethods();
foreach (array_keys($shippingList) as $code) {
    $installer->run("
        INSERT IGNORE INTO `{$this->getTable('fianet_shipping_association')}` (`shipping_code`, `fianet_shipping_type`, `delivery_times`, `conveyor_name`) VALUES
        ('{$code}', '4', '2', 'A definir');
    ");
}
$installer->endSetup();
