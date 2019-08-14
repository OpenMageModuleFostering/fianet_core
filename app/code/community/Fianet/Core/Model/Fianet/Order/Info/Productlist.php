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
class Fianet_Core_Model_Fianet_Order_Info_Productlist {

    protected $productsList = array();


    public function addProduct($product) {
        if ($product instanceof Fianet_Core_Model_Fianet_Order_Info_Productlist_Product) {
            $this->productsList[] = $product;
        } else {
            Mage::throwException("Fianet_Core_Model_Fianet_Order_Info_Productlist::addProduct() - Data are not a valid Fianet_Core_Model_Fianet_Order_Info_Productlist_Product type");
        }
    }

    public function getXml() {
        $xml = '';
        if (count($this->productsList) > 0) {

            $xml .= "\t\t" . '<list nbproduit="' . $this->_countNbProducts() . '">' . "\n";
            foreach ($this->productsList as $product) {
                $xml .= $product->getXml();
            }
            $xml .= "\t\t" . '</list>' . "\n";
        }
        return ($xml);
    }

    protected function _countNbProducts() {
        $n = 0;
        foreach ($this->productsList as $product) {
            $n += $product->nb;
        }
        return ($n);
    }

}
