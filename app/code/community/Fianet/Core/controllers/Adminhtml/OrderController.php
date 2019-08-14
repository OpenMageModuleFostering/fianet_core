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
class Fianet_Core_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->_redirect('adminhtml/sales_order');
    }

    public function getTransactionStateAction() {
        if (Mage::helper('fianet')->checkModuleIsInstalled('Fianet_Kwixo')) {
            try {
                Mage::getModel('kwixo/tagline')->getTransactionState($this->getRequest()->getPost('order_ids', array()));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('adminhtml/sales_order');
    }

    public function getEvaluationAction() {
        if (Mage::helper('fianet')->checkModuleIsInstalled('Fianet_Sac')) {
            try {
                Fianet_Sac_Model_Action::getEvaluation($this->getRequest()->getPost('order_ids', array()));
                Fianet_Sac_Model_Action::getReevaluation($this->getRequest()->getPost('order_ids', array()));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('adminhtml/sales_order');
    }

    public function sentSacAction() {
        if (Mage::helper('fianet')->checkModuleIsInstalled('Fianet_Sac')) {
            $orderIds = $this->getRequest()->getPost('order_ids', array());
            $countHoldOrder = 0;

            $sender = Mage::getModel('fianet/fianet_sender');
            foreach ($orderIds as $orderId) {
                $order = Mage::getModel('sales/order')->load($orderId);

                if (!Mage::helper('sac/order')->canSendOrder($order, $orderId)) {
                    continue;
                }

                try {
                    $SacOrder = Fianet_Sac_Model_Fianet_Order_Sac::generateSacOrder($order);
                    $sender->addOrder($SacOrder);
                    $response = $sender->send();
                    $countHoldOrder = Mage::helper('sac/order')->processResponse($response, array($orderId));
                    if ($countHoldOrder > 0) {
                        $this->_getSession()->addSuccess(Mage::helper('fianet')->__('%s order(s) successfully sent to FIA-NET', $countHoldOrder));
                    }
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('adminhtml/sales_order');
    }

}
