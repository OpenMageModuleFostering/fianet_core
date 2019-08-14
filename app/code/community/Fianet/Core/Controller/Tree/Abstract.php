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
interface IFianetTree {

    public function treeAction();

    public function categoriesJsonAction();
}

abstract class Fianet_Core_Controller_Tree_Abstract extends Mage_Adminhtml_Controller_Action implements IFianetTree {

    private $_block = 'fianet/adminhtml_tree_abstract';

    protected function _initCategory($getRootInstead = false) {
        $categoryId = (int) $this->getRequest()->getParam('id', false);
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        $category = Mage::getModel('catalog/category');
        $category->setStoreId($storeId);

        if ($categoryId) {
            $category->load($categoryId);
            if ($storeId) {
                $rootId = Mage::app()->getStore($storeId)->getRootCategoryId();
                if (!in_array($rootId, $category->getPathIds())) {
                    // load root category instead wrong one
                    if ($getRootInstead) {
                        $category->load($rootId);
                    } else {
                        $this->_redirect('*/*/', array('_current' => true, 'id' => null));
                        return false;
                    }
                }
            }
        }

        Mage::register('category', $category);
        Mage::register('current_category', $category);
        return $category;
    }

}
