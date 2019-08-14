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
class Fianet_Core_Block_Category_Typeproduct_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_controller = 'category_typeproduct';
        $this->_mode = 'edit';
        $this->_blockGroup = 'fianet';

        $this->_updateButton('save', 'label', $this->__('Save'));
        $this->_updateButton('save', 'url', $this->getSaveUrl());
        $categoryId = (int) $this->getRequest()->getParam('id');
        if ($categoryId > 0) {
            $producttype = Mage::getModel('fianet/Catproduct_association')->loadByCategorieId($categoryId);
            if ($producttype->getId() == 0) {
                $this->_removeButton('delete');
            }
        } else {
            $this->_removeButton('save');
        }
        $this->_removeButton('reset');
    }

    public function getHeaderText() {
        $categoryId = (int) $this->getRequest()->getParam('id');

        if ($categoryId <= 0) {
            return Mage::helper('fianet')->__('Categories configuration');
        }
        $category = Mage::getModel('catalog/category')->load($categoryId);
        return Mage::helper('fianet')->__("Categorie's %s configuration", $category->getName());
    }

    public function getDeleteUrl() {
        $storeId = $this->getRequest()->getParam('store', 0);
        //return $this->getUrl('*/*/delete', array($this->_objectId => $this->getRequest()->getParam($this->_objectId), 'store' => $storeId));
    }

    public function getSaveUrl() {
        $storeId = $this->getRequest()->getParam('store', 0);
        //return $this->getUrl('*/*/delete', array($this->_objectId => $this->getRequest()->getParam($this->_objectId), 'store' => $storeId));
    }

}