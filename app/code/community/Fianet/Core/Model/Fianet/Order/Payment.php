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
class Fianet_Core_Model_Fianet_Order_Payment {

    public $type;
    public $numcb;
    public $dateval;
    public $bin;
    public $bin4;
    public $bin42;

    public function __construct($order = null) {

    }

    public function set_cb_number($cb, $dateval) {
        if (!preg_match("/^[0-9]{16}$/", $cb)) {
            Mage::throwException("Mage_Fianet_Model_Fianet_Order_Payment::set_cb_number() - Cb number (" . $cb . ") format is invalid, must be 00001111222233334444");
        } else if (!preg_match("/^[0-9]{2}/[0-9]{4}$/", $dateval)) {
            Mage::throwException("Mage_Fianet_Model_Fianet_Order_Payment::set_cb_number() - Validity date (" . $dateval . ") format is invalid, must be MM/YYYY");
        } else {
            $cryptage = Mage::getModel('fianet/configuration')->getGlobalValue('RNP_CRYPTAGE');
            $crypt = Mage::getModel('fianet/fianet_' . $cryptage);
            $this->numcb = $crypt->hash($cb);
            $this->dateval = $crypt->hash($dateval);
        }
    }

    public function get_xml() {
        $xml = '';
        if ($this->type != null) {
            $xml .= "\t" . '<paiement>' . "\n";

            $xml .= "\t\t" . '<type>' . $this->type . '</type>' . "\n";
            if ($this->type == 'carte' || $this->type == 'paypal') {
                if ($this->numcb != null) {
                    $xml .= "\t\t" . '<numcb>' . $this->numcb . '</numcb>' . "\n";
                }
                if ($this->dateval != null) {
                    $xml .= "\t\t" . '<dateval>' . $this->dateval . '</dateval>' . "\n";
                }
                if ($this->bin != null) {
                    $xml .= "\t\t" . '<bin>' . $this->bin . '</bin>' . "\n";
                }
                if ($this->bin4 != null) {
                    $xml .= "\t\t" . '<bin4>' . $this->bin4 . '</bin4>' . "\n";
                }
                if ($this->bin42 != null) {
                    $xml .= "\t\t" . '<bin42>' . $this->bin42 . '</bin42>' . "\n";
                }
            }
            $xml .= "\t" . '</paiement>' . "\n";
        } else {
            Mage::throwException("Mage_Fianet_Model_Fianet_Order_Payment::get_xml() - Type is undefined");
        }
        return ($xml);
    }

}
