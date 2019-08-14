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
class Fianet_Core_Model_Fianet_Order_Sac {

    public $billing_user = null;
    public $billing_adress = null;
    public $info_commande = null;
    public $payment = null;
    public $delivery_user = null;
    public $delivery_adress = null;
    public $scope_field = null;
    public $scope_id = null;
    protected $version = null;
    protected $encoding = null;

    public function __construct() {
        $this->billing_user = Mage::getModel('fianet/fianet_order_user_billing');
        $this->billing_adress = Mage::getModel('fianet/fianet_order_adress_billing');
        $this->info_commande = Mage::getModel('fianet/fianet_order_info_sac');
        $this->payment = Mage::getModel('fianet/fianet_order_payment');
        $this->version = (string) Mage::getConfig()->getModuleConfig('Fianet_Sac')->version;
        $this->encoding = (string) Mage::getModel('fianet/configuration')->getGlobalValue('XML_ENCODING');
    }

    public function reset() {
        $this->billing_user = Mage::getModel('fianet/fianet_order_user_billing');
        $this->billing_adress = Mage::getModel('fianet/fianet_order_adress_billing');
        $this->info_commande = Mage::getModel('fianet/fianet_order_info_sac');
        $this->payment = Mage::getModel('fianet/fianet_order_payment');
        $this->delivery_user = null;
        $this->delivery_adress = null;
    }

    public function get_xml($display_signature = false) {
        $xml = '';
        if ($display_signature) {
            $xml .= '<?xml version="1.0" encoding="' . $this->encoding . '" ?>' . "\n";
        }
        $xml .= '<control fianetmodule="Magento_SAC" version="' . $this->version . '">' . "\n";
        $xml .= $this->billing_user->get_xml();
        $xml .= $this->billing_adress->get_xml();
        if ($this->delivery_user != null) {
            if (Mage::getModel('fianet/functions')->var_is_object_of_class($this->delivery_user, 'Fianet_Core_Model_Fianet_Order_User_Delivery')) {
                $xml .= $this->delivery_user->get_xml();
            } else {
                Mage::getModel('fianet/log')->Log("Mage_Fianet_Model_Fianet_Order_Sac - get_xml() <br />\nDelivery user is not an object of type Mage_Fianet_Order_User_Delivery");
            }
        }
        if ($this->delivery_adress != null) {
            if (Mage::getModel('fianet/functions')->var_is_object_of_class($this->delivery_adress, 'Fianet_Core_Model_Fianet_Order_Adress_Delivery')) {
                $xml .= $this->delivery_adress->get_xml();
            } else {
                Mage::getModel('fianet/log')->Log("Mage_Fianet_Model_Fianet_Order_Sac - get_xml() <br />\nDelivery adress is not an object of type Mage_Fianet_Order_Adress_Delivery");
            }
        }
        $xml .= $this->info_commande->get_xml();
        $xml .= $this->payment->get_xml();
        $xml .= '</control>';
        //Zend_Debug::dump($xml);exit();
        return ($xml);
    }

    public static function GenerateSacOrder(Mage_Sales_Model_Order $order) {
        $scope_field = Mage::getModel('fianet/configuration')->getGlobalValue('CONFIGURATION_SCOPE');
        switch ($scope_field) {
            case ('store_id'):
                $id = $order->getStore()->getId();
                break;
            case ('group_id'):
                $id = $order->getStore()->getGroup()->getId();
                break;
            case ('website_id'):
                $id = $order->getStore()->getWebsite()->getId();
                break;
            default:
                $id = $order->getStore()->getGroup()->getId();
                break;
        }
        $configurationData = Mage::getModel('fianet/configuration_value');
        $configurationData->_scope_field = $scope_field;
        $configurationData->setScope($id);

        $SacOrder = Mage::getModel('fianet/fianet_order_sac');

        $SacOrder->scope_field = $scope_field;
        $SacOrder->scope_id = $id;

        $billing_address = $order->getBillingAddress();
        $shipping_address = $order->getShippingAddress();
        //Zend_Debug::dump($billing_address,'$billing_address');
        $SacOrder->billing_user->nom = $billing_address->getLastname();
        $SacOrder->billing_user->prenom = $billing_address->getFirstname();
        $SacOrder->billing_user->telhome = preg_replace("/[^0-9]/", "", $billing_address->getTelephone());
        $SacOrder->billing_user->telfax = preg_replace("/[^0-9]/", "", $billing_address->getFax());
        $SacOrder->billing_user->email = $billing_address->getEmail() == '' ? $order->getCustomer_email() : $billing_address->getEmail();
        $SacOrder->billing_user->societe = $billing_address->getCompany();

        if (trim($billing_address->getCompany()) != '') {
            $SacOrder->billing_user->set_quality_professional();
        }

        $SacOrder->billing_adress->rue1 = $billing_address->getStreet(1);
        $SacOrder->billing_adress->rue2 = $billing_address->getStreet(2);
        $SacOrder->billing_adress->cpostal = $billing_address->getPostcode();
        $SacOrder->billing_adress->ville = $billing_address->getCity();
        $SacOrder->billing_adress->pays = $billing_address->getCountry();

        //Zend_Debug::dump(Mage::getModel('fianet/carrier_abstract')->getCode($order->getShippingCarrier()));
        $shipping_code = $order->getShippingCarrier()->getCarrierCode();
        $shipping = Mage::getModel('fianet/shipping_association')->load($shipping_code);

        if ($shipping->fianet_shipping_type != '1' && $shipping->fianet_shipping_type != '2') {
            if (!Fianet_Core_Model_Functions::compare_billing_and_shipping($billing_address, $shipping_address)) {
                $SacOrder->delivery_user = Mage::getModel('fianet/fianet_order_user_delivery');
                $SacOrder->delivery_adress = Mage::getModel('fianet/fianet_order_adress_delivery');

                $SacOrder->delivery_user->qualite = $SacOrder->billing_user->qualite;

                $SacOrder->delivery_user->nom = $shipping_address->getLastname();
                $SacOrder->delivery_user->prenom = $shipping_address->getFirstname();
                $SacOrder->delivery_user->telhome = preg_replace("/[^0-9]/", "", $shipping_address->getTelephone());
                $SacOrder->delivery_user->telfax = preg_replace("/[^0-9]/", "", $shipping_address->getFax());
                $SacOrder->delivery_user->email = $shipping_address->getEmail();
                $SacOrder->delivery_user->societe = $shipping_address->getCompany();

                $SacOrder->delivery_adress->rue1 = $shipping_address->getStreet(1);
                $SacOrder->delivery_adress->rue2 = $shipping_address->getStreet(2);
                $SacOrder->delivery_adress->cpostal = $shipping_address->getPostcode();
                $SacOrder->delivery_adress->ville = $shipping_address->getCity();
                $SacOrder->delivery_adress->pays = $shipping_address->getCountry();
            }
        }

        $SacOrder->info_commande->refid = $order->getRealOrderId();
        $SacOrder->info_commande->devise = $order->getBaseCurrencyCode();
        $SacOrder->info_commande->montant = $order->getBaseGrandTotal();
        $SacOrder->info_commande->ip = $order->getRemoteIp();
        $SacOrder->info_commande->timestamp = $order->getCreatedAt();

        $SacOrder->info_commande->siteid = $configurationData->load('SAC_SITEID')->Value;
        if ($SacOrder->info_commande->siteid == null) {
            $configurationData->setScope(0);
            $SacOrder->info_commande->siteid = $configurationData->load('SAC_SITEID')->Value;
        }


        $SacOrder->info_commande->transport->type = $shipping->fianet_shipping_type;
        $SacOrder->info_commande->transport->nom = $shipping->conveyor_name;
        $SacOrder->info_commande->transport->rapidite = $shipping->delivery_times;

        foreach ($order->getAllVisibleItems() as $item) {
            $pAmount = $item->getBaseRowTotal() - $item->getBaseDiscountAmount() + $item->getBaseTaxAmount() + $item->getBaseWeeeTaxAppliedRowAmount();

            $pName = $item->getName();
            $pSku = $item->getSku();
            if ($item->getProduct_type() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
                $children_product = $item->getChildrenItems();
                if (count($children_product) == 1) {
                    $pName = $children_product[0]->getName();
                    $pSku = $children_product[0]->getSku();
                }
            }
            $product = Mage::getModel('fianet/fianet_order_info_productList_product');
            $product->type = Mage::getModel('fianet/product')->load($item->getProduct_id())->getFianetProductType();
            $product->prixunit = $pAmount;
            $product->name = $pName;
            $product->nb = (int) $item->getQtyOrdered();
            $product->ref = $pSku;
            $SacOrder->info_commande->list->add_product($product);
        }
        $SacOrder->payment->type = Mage::getModel('sac/payment_association')->load($order->getPayment()->getMethod())->getValue();

        return ($SacOrder);
    }

}
