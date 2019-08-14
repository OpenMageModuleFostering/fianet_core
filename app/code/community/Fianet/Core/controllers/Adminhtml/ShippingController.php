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
class Fianet_Core_Adminhtml_ShippingController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->_setActiveMenu('adminfianet');
        $shippings = Mage::getModel('fianet/mageConfiguration')->getShippingMethods();
        $this->getLayout()->getBlock('shipping')->setData('shipping', $shippings);
        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('fianet')->__('Shipping type'));
        $this->renderLayout();
    }

    public function postAction() {
        $post = $this->getRequest()->getPost();
        try {
            if (empty($post)) {
                Mage::throwException($this->__('Invalid form data.'));
            }

            foreach ($post as $Code => $data) {
                if (trim($data['conveyorName']) != '') {
                    $shippingType = $data['shippingType'];
                    $deliveryTimes = $data['deliveryTimes'];
                    $conveyorName = $data['conveyorName'];
                    Mage::getModel('fianet/shipping_association')
                            ->load($Code)
                            ->setShippingCode($Code)
                            ->setFianetShippingType($shippingType)
                            ->setDeliveryTimes($deliveryTimes)
                            ->setConveyorName($conveyorName)
                            ->save();
                }
            }
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Data succesfully saved.'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*');
    }

}
