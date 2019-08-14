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
class Fianet_Core_Model_Fianet_Order_Info_Transport {

    public $type;
    public $nom;
    public $rapidite;

    public function __construct() {

    }

    public function get_xml() {
        $xml = '';
        if ($this->type == null) {
            Mage::throwException("Mage_Fianet_Model_Fianet_Order_Info_Transport::get_xml() - Transport type undefined");
        }
        if ($this->nom == null) {
            Mage::throwException("Mage_Fianet_Model_Fianet_Order_Info_Transport::get_xml() - Transport name undefined");
        }
        if ($this->rapidite == null) {
            Mage::throwException("Mage_Fianet_Model_Fianet_Order_Info_Transport::get_xml() - Transport time undefined");
        }
        $xml .= "\t\t" . '<transport>' . "\n";

        $xml .= "\t\t\t" . '<type>' . $this->type . '</type>' . "\n";
        $xml .= "\t\t\t" . '<nom>' . Mage::getModel('fianet/functions')->clean_invalid_char($this->nom) . '</nom>' . "\n";
        $xml .= "\t\t\t" . '<rapidite>' . $this->rapidite . '</rapidite>' . "\n";

        $xml .= "\t\t" . '</transport>' . "\n";
        return ($xml);
    }

}
