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
class Fianet_Core_Adminhtml_ConfigurationController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->loadLayout();

        $this->_setActiveMenu('adminfianet');
        /*if (!Mage::app()->isSingleStoreMode()) {
            $this->_addLeft($this->getLayout()->createBlock('fianet/store_switcher'));
        }*/

        $this->renderLayout();
    }

    public function deleteAction() {
        $storeId = (integer) $this->getRequest()->getParam('store', 0);
        if ($storeId > 0) {
            $Configuration = Mage::getModel('fianet/configuration')
                    ->getCollection()
                    ->setOrder('sort', 'asc');
            foreach ($Configuration as $conf) {
                if ($conf->is_global == '0') {
                    Mage::getModel('fianet/configuration_value')
                            ->setScope($storeId)
                            ->load($conf->code)
                            ->delete();
                }
            }
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Data succesfully deleted.'));
        }
        $this->_redirect('*/*/index/store/' . $storeId);
    }

    public function saveAction() {
        $post = $this->getRequest()->getPost();
        //Zend_Debug::dump($post);
        $storeId = $post['storeId'];
        unset($post['storeId']);
        //Zend_Debug::dump($storeId);
        try {
            if (empty($post)) {
                Mage::throwException($this->__('Invalid form data.'));
            }

            if (isset($post['CONFIGURATION_SCOPE'])) {
                $scope = $post['CONFIGURATION_SCOPE'];
                unset($post['CONFIGURATION_SCOPE']);
            }
            foreach ($post as $key => $value) {
                if (Mage::getModel('fianet/configuration')->load($key)->is_global == '1') {
                    Mage::getModel('fianet/configuration_global')->load($key)
                            ->setId($key)
                            ->setValue($value)
                            ->save();
                } else {
                    Mage::getModel('fianet/configuration_value')
                            ->setScope($storeId)
                            ->load($key)
                            ->setId($key)
                            ->setValue($value)
                            ->save();
                }
            }
            if (isset($scope)) {
                Mage::getModel('fianet/configuration_global')->load('CONFIGURATION_SCOPE')
                        ->setId('CONFIGURATION_SCOPE')
                        ->setValue($scope)
                        ->save();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Data succesfully saved.'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/index/store/' . $storeId);
    }

}
