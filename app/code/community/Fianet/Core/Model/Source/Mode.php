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
class Fianet_Core_Model_Source_Mode {

    const MODE_TEST = "test";
    const MODE_PRODUCTION = "production";

    public function toOptionArray() {
        return array(
            array('value' => self::MODE_TEST, 'label' => Mage::helper('adminhtml')->__('Test')),
            array('value' => self::MODE_PRODUCTION, 'label' => Mage::helper('adminhtml')->__('Production')),
        );
    }

}