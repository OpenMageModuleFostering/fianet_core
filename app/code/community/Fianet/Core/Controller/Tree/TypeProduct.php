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
class Fianet_Core_Controller_Tree_TypeProduct extends Fianet_Core_Controller_Tree_Abstract {

    protected function _construct() {
        parent::_construct();
        $this->_block = 'fianet/tree_typeProduct';
    }

    public function treeAction() {
        $categoryId = (int) $this->getRequest()->getParam('id');
        $storeId = $this->getRequest()->getParam('store', 0);
        if ($storeId) {
            if (!$categoryId) {
                $store = Mage::app()->getStore($storeId);
                $rootId = $store->getRootCategoryId();
                $this->getRequest()->setParam('id', $rootId);
            }
        }

        $category = $this->_initCategory(true);

        $block = $this->getLayout()->createBlock($this->_block);
        $root = $block->getRoot();
        $this->getResponse()->setBody(Zend_Json::encode(array(
                    'data' => $block->getTree(),
                    'parameters' => array(
                        'text' => $block->buildNodeName($root),
                        'draggable' => false,
                        'allowDrop' => false,
                        'id' => (int) $root->getId(),
                        'expanded' => (int) $block->getIsWasExpanded(),
                        'store_id' => (int) $block->getStore()->getId(),
                        'category_id' => (int) $category->getId(),
                        'root_visible' => (int) $root->getIsVisible()
                        ))));
    }

    public function categoriesJsonAction() {
        if ($this->getRequest()->getParam('expand_all')) {
            Mage::getSingleton('admin/session')->setIsTreeWasExpanded(true);
        } else {
            Mage::getSingleton('admin/session')->setIsTreeWasExpanded(false);
        }
        if ($categoryId = (int) $this->getRequest()->getPost('id')) {
            $this->getRequest()->setParam('id', $categoryId);

            if (!$category = $this->_initCategory()) {
                return;
            }
            $this->getResponse()->setBody(
                    $this->getLayout()->createBlock($this->_block)
                            ->getTreeJson($category)
            );
        }
    }

}