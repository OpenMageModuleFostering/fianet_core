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
class Fianet_Core_Model_Fianet_Order_Info_Productlist {

    protected $products_list = array();

    public function __construct() {

    }

    public function add_product($product) {
        if (Mage::getModel('fianet/functions')->var_is_object_of_class($product, 'Fianet_Core_Model_Fianet_Order_Info_ProductList_Product')) {
            $this->products_list[] = $product;
        } else {
            Mage::throwException("Mage_Fianet_Model_Fianet_Order_Info_Productlist::add_product() - Data are not a valid Mage_Fianet_Model_Fianet_Order_Info_Productlist_Product type");
        }
    }

    public function get_xml() {
        $xml = '';
        if (count($this->products_list) > 0) {

            $xml .= "\t\t" . '<list nbproduit="' . $this->count_nbproduct() . '">' . "\n";
            foreach ($this->products_list as $product) {
                $xml .= $product->get_xml();
            }
            $xml .= "\t\t" . '</list>' . "\n";
        }
        return ($xml);
    }

    protected function count_nbproduct() {
        $n = 0;
        foreach ($this->products_list as $product) {
            $n += $product->nb;
        }
        return ($n);
    }

}
