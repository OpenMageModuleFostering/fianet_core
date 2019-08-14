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
class Fianet_Core_Model_Fianet_Order_Wallet {

    protected $version = '1.0';
    public $datelivr = null;
    protected $datecom = null;
    protected $crypt = null;
    protected $crypt_version = '2.0';
    protected $order_date_time_seconds = null;

    public function __construct() {
        //$this->datecom = "2008-05-28 14:25:31";
        $this->datecom = date("Y-m-d H:i:s");
        $this->order_date_time_seconds = substr($this->datecom, -2, 2);
    }

    public function generate_wallet_crypt_data($order_id, $billing_lastname, $customer_email, $total) {
        $total = number_format($total, 2, '.', '');
        $this->generate_crypt_key($order_id, $billing_lastname, $customer_email, $total);
    }

    public function get_xml() {
        $xml = '';

        $xml .= "\t" . '<wallet version="' . $this->version . '">' . "\n";
        $xml .= "\t\t" . '<datelivr>' . $this->datelivr . '</datelivr>' . "\n";
        $xml .= "\t\t" . '<datecom>' . $this->datecom . '</datecom>' . "\n";
        $xml .= "\t\t" . '<crypt version="' . $this->crypt_version . '">' . $this->crypt . '</crypt>' . "\n";
        $xml .= "\t" . '</wallet>' . "\n";

        return ($xml);
    }

    protected function generate_crypt_key($order_id, $billing_lastname, $customer_email, $total) {
        if (Mage::getModel('fianet/configuration_global')->load('XML_ENCODING')->Value == 'UTF-8') {
            $billing_lastname = utf8_decode($billing_lastname);
        } else {
            $billing_lastname = $billing_lastname;
        }
        $store = Mage::getModel('fianet/functions')->getStore();
        $cryptkey = Mage::getModel('fianet/configuration')->GetStoreValue('RNP_CRYPT_KEY', $store);
        if ($cryptkey == '' && $store > 0) {
            $cryptkey = Mage::getModel('fianet/configuration')->GetStoreValue('RNP_CRYPT_KEY', 0);
        }


        $encodingKey = Mage::getModel('fianet/fianet_EncodingKey');
        $this->crypt = $encodingKey->giveHashCode2($cryptkey, $this->order_date_time_seconds, $customer_email, $order_id, $total, $billing_lastname);
    }

}
