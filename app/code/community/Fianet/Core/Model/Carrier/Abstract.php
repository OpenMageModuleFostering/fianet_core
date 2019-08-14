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
 *  @author Quadra Informatique <ecommerce@quadra-informatique.fr>
 *  @copyright 2000-2012 FIA-NET
 *  @version Release: $Revision: 0.9.0 $
 *  @license http://www.opensource.org/licenses/OSL-3.0  Open Software License (OSL 3.0)
 */
class Fianet_Core_Model_Carrier_Abstract extends Mage_Shipping_Model_Carrier_Abstract {

    public function getCode(Mage_Shipping_Model_Carrier_Abstract $shipping) {
        $string = serialize($shipping);
        $className = 'Fianet_Core_Model_Carrier_Abstract';
        $len = strlen($className);

        eregi('^O:([0-9]+):"([a-zA-Z0-9_]+)":', $string, $data);
        $string = str_replace('O:' . $data[1], 'O:' . $len, $string);
        $string = str_replace($data[2], $className, $string);

        $object = unserialize($string);
        return ($object->_code);
    }

    public function collectRates(Mage_Shipping_Model_Rate_Request $request) {
        return null;
    }

}