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
class Fianet_Core_Model_Fianet_Order_Info_ProductList_Product {

    public $type;
    public $ref;
    public $nb = 0;
    public $prixunit;
    public $name;

    public function __construct() {

    }

    public function get_xml() {
        $xml = '';
        if ($this->name != null) {
            $nb = '';
            $prixunit = '';
            if ($this->type != null) {
                $type = ' type="' . $this->type . '"';
            } else {
                Mage::getModel('fianet/log')->Log("Mage_Fianet_Model_Fianet_Order_Info_ProductList_Product::get_xml() <br />\nproduct type is missing");
            }
            if ($this->ref != null) {
                $ref = ' ref="' . Mage::getModel('fianet/functions')->clean_invalid_char($this->ref) . '"';
            } else {
                Mage::getModel('fianet/log')->Log("Mage_Fianet_Model_Fianet_Order_Info_ProductList_Product::get_xml() <br />\nproduct ref is missing");
            }
            if ($this->nb != null) {
                $nb = ' nb="' . $this->nb . '"';
            }
            if ($this->prixunit != null) {
                $prixunit = ' prixunit="' . number_format($this->prixunit, 2, '.', '') . '"';
            }
            $xml .= "\t\t\t<produit$type$ref$nb$prixunit>" . Mage::getModel('fianet/functions')->clean_invalid_char($this->name) . "</produit>\n";
        } else {
            Mage::getModel('fianet/log')->Log("Mage_Fianet_Model_Fianet_Order_Info_ProductList_Product::get_xml() <br />\nproduct name is missing");
        }
        return ($xml);
    }

}
