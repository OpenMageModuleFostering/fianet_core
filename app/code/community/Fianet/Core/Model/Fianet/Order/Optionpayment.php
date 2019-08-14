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
class Fianet_Core_Model_Fianet_Order_Optionpayment {

    public $typpayment = null;
    public $comptantrnp = null;
    public $comptantrnpoffert = null;

    public function get_xml() {
        $xml = "";
        if ($this->typpayment == "comptant" && ($this->comptantrnp == '1' || $this->comptantrnp == '0')) {
            //comptant
            $xml .= "\t" . '<options-paiement type="' . $this->typpayment . '" comptant-rnp="' . $this->comptantrnp . '">' . "\n";
            $xml .= "\t" . '</options-paiement>' . "\n";
        } elseif($this->typpayment == "credit") {
            //credit
            $xml .= "\t" . '<options-paiement type="credit">' . "\n";
            $xml .= "\t" . '</options-paiement>' . "\n";
        }
        return ($xml);
    }

}