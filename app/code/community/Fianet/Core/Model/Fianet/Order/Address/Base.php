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
class Fianet_Core_Model_Fianet_Order_Address_Base {

    protected $_type;
    protected $_format = 1;
    public $rue1;
    public $rue2;
    public $cpostal;
    public $ville;
    public $pays;

    public function getXml() {
        $xml = '';
        if ($this->_type != null) {
            $xml .= "\t" . '<adresse type="' . $this->_type . '" format="' . $this->_format . '">' . "\n";
            if ($this->rue1 != '') {
                $xml .= "\t\t" . '<rue1><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->rue1) . ']]></rue1>' . "\n";
            } else {
                Mage::throwException("Fianet_Core_Model_Fianet_Order_Adress_Base::getXml() - rue1 is undefined");
            }
            if ($this->rue2 != "") {
                $xml .= "\t\t" . '<rue2><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->rue2) . ']]></rue2>' . "\n";
            }
            if ($this->cpostal != "") {
                $xml .= "\t\t" . '<cpostal><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->cpostal) . ']]></cpostal>' . "\n";
            } else {
                Mage::throwException("Fianet_Core_Model_Fianet_Order_Adress_Base::getXml() - cpostal is undefined");
            }
            if ($this->ville != "") {
                $xml .= "\t\t" . '<ville><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->ville) . ']]></ville>' . "\n";
            } else {
                Mage::throwException("Fianet_Core_Model_Fianet_Order_Adress_Base::getXml() - ville is undefined");
            }
            if ($this->pays != "") {
                $xml .= "\t\t" . '<pays><![CDATA[' . Mage::getModel('fianet/functions')->cleanInvalidChar($this->pays) . ']]></pays>' . "\n";
            } else {
                Mage::throwException("Fianet_Core_Model_Fianet_Order_Adress_Base::getXml() - pays is undefined");
            }
            $xml .= "\t" . '</adresse>' . "\n";
        }
        return ($xml);
    }

}