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
class Fianet_Core_Model_Fianet_Order_Wallet {

    protected $_version = '1.0';
    protected $_dateCom = null;
    protected $_crypt = null;
    protected $_cryptVersion = '2.0';
    protected $_orderDateTimeSeconds = null;
    public $dateLivr = null;

    public function __construct() {
        $this->_dateCom = Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s');
        $this->_orderDateTimeSeconds = substr($this->_dateCom, -2, 2);
    }

    public function generateWalletCryptData($orderId, $billingLastname, $customerEmail, $total) {
        $total = number_format($total, 2, '.', '');
        $this->_generateCryptKey($orderId, $billingLastname, $customerEmail, $total);
    }

    public function getXml() {
        $xml = '';
        $xml .= "\t" . '<wallet version="' . $this->_version . '">' . "\n";
        $xml .= "\t\t" . '<datelivr><![CDATA[' . $this->dateLivr . ']]></datelivr>' . "\n";
        $xml .= "\t\t" . '<datecom><![CDATA[' . $this->_dateCom . ']]></datecom>' . "\n";
        $xml .= "\t\t" . '<crypt version="' . $this->_cryptVersion . '"><![CDATA[' . $this->_crypt . ']]></crypt>' . "\n";
        $xml .= "\t" . '</wallet>' . "\n";
        return ($xml);
    }

    protected function _generateCryptKey($orderId, $billingLastname, $customerEmail, $total) {
        if (Mage::getStoreConfig('kwixo/kwixoconfg/charset') == 'UTF-8') {
            $billingLastname = utf8_decode($billingLastname);
        }
        $store = Mage::getModel('fianet/functions')->getStore();
        $cryptkey = Mage::getStoreConfig('kwixo/kwixoconfg/cryptkey', $store);
        $encodingKey = Mage::getModel('fianet/fianet_encodingKey');
        $this->_crypt = $encodingKey->giveHashCode2($cryptkey, $this->_orderDateTimeSeconds, $customerEmail, $orderId, $total, $billingLastname);
    }

}
