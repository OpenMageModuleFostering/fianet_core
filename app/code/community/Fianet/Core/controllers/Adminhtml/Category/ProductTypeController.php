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
class Fianet_Core_Adminhtml_Category_ProductTypeController extends Fianet_Core_Controller_Tree_ProductType {

    public function indexAction() {
        $this->loadLayout();
        $this->_setActiveMenu('adminfianet');
        $this->getLayout()->getBlock('head')
                ->setCanLoadExtJs(true)
                ->setContainerCssClass('catalog-categories');

        $selectedCategory = Mage::getSingleton('admin/session')->getLastEditedCategory(true);
        if ($selectedCategory) {
            $this->getRequest()->setParam('id', $selectedCategory);
        }

        $selectedCategory = (int) $this->getRequest()->getParam('id', 0);
        $this->_initCategory(true);

        if ($selectedCategory > 0) {
            $this->getLayout()->getBlock('tree')->setData('selectedCategory', $selectedCategory);
        }
        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('fianet')->__('Product type'));
        $this->renderLayout();
    }

    public function editAction() {
        $params = array('_current' => true);
        $redirect = false;

        $storeId = (int) $this->getRequest()->getParam('store');
        $parentId = (int) $this->getRequest()->getParam('parent');
        $prevStoreId = Mage::getSingleton('admin/session')->getLastViewedStore(true);

        if ($prevStoreId != null && !$this->getRequest()->getQuery('isAjax')) {
            $params['store'] = $prevStoreId;
            $redirect = true;
        }

        $prevCategoryId = Mage::getSingleton('admin/session')->getLastEditedCategory(true);
        if ($prevCategoryId && !$this->getRequest()->getQuery('isAjax')) {
            $this->getRequest()->setParam('id', $prevCategoryId);
        }
        if ($redirect) {
            $this->_redirect('*/*/edit', $params);
            return;
        }

        $categoryId = (int) $this->getRequest()->getParam('id');
        if ($storeId && !$categoryId && !$parentId) {
            $store = Mage::app()->getStore($storeId);
            $prevCategoryId = (int) $store->getRootCategoryId();
            $this->getRequest()->setParam('id', $prevCategoryId);
        }

        if (!($category = $this->_initCategory(true))) {
            return;
        }

        $data = Mage::getSingleton('adminhtml/session')->getCategoryData(true);
        if (isset($data['general'])) {
            $category->addData($data['general']);
        }

        if ($this->getRequest()->getQuery('isAjax')) {
            // prepare breadcrumbs of selected category, if any

            $breadcrumbsPath = $category->getPath();
            if (empty($breadcrumbsPath)) {
                // but if no category, and it is deleted - prepare breadcrumbs from path, saved in session
                $breadcrumbsPath = Mage::getSingleton('admin/session')->getDeletedPath(true);
                if (!empty($breadcrumbsPath)) {
                    $breadcrumbsPath = explode('/', $breadcrumbsPath);
                    // no need to get parent breadcrumbs if deleting category level 1
                    if (count($breadcrumbsPath) <= 1) {
                        $breadcrumbsPath = '';
                    } else {
                        array_pop($breadcrumbsPath);
                        $breadcrumbsPath = implode('/', $breadcrumbsPath);
                    }
                }
            }

            Mage::getSingleton('admin/session')->setLastViewedStore($this->getRequest()->getParam('store'));
            Mage::getSingleton('admin/session')->setLastEditedCategory($category->getId());
            $this->_initLayoutMessages('adminhtml/session');
            $this->getResponse()->setBody(
                    $this->getLayout()->getMessagesBlock()->getGroupedHtml()
                    . $this->getLayout()->createBlock('fianet/adminhtml_category_productType_edit')->toHtml()
                    . $this->getLayout()->getBlockSingleton('adminhtml/catalog_category_tree')
                           ->getBreadcrumbsJavascript($breadcrumbsPath, 'editingCategoryBreadcrumbs')
            );
            return;
        }
        $this->_redirect('*/*/index');
    }

    public function saveAction() {
        $post = $this->getRequest()->getPost();
        $storeId = $post['storeId'];

        try {
            if (empty($post)) {
                Mage::throwException($this->__('Invalid form data.'));
            }
            if (isset($post["productType"])) {
                if ($post["productType"] != "" && $post["productType"] != "0") {
                    $productType = Mage::getModel('fianet/catproduct_association')
                            ->loadByCategoryId($post['id']);
                    if ($productType->getId() > 0) {
                        $productType->delete();
                        $productType = Mage::getModel('fianet/catproduct_association');
                    }
                    $productType->setCatalogCategoryEntityId($post['id'])
                            ->setFianetProductType($post["productType"])
                            ->save();
                    $message = Mage::helper('fianet')->__('Data succesfully saved.');
                    Mage::getSingleton('adminhtml/session')->addSuccess($message);
                }
                if (isset($post['applysubcat'])) {
                    $category = Mage::getModel('catalog/category')->load($post['id']);
                    $subcat = $category->getAllChildren(true);
                    foreach ($subcat as $categoryId) {
                        $productType = Mage::getModel('fianet/catproduct_association')
                                ->loadByCategoryId($categoryId);
                        if ($productType->getId() > 0) {
                            $productType->delete();
                            $productType = Mage::getModel('fianet/catproduct_association');
                        }
                        $productType->setCatalogCategoryEntityId($categoryId)
                                ->setFianetProductType($post["productType"])
                                ->save();
                    }
                }
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/index/', array('_current' => true, "id" => $post['id'], "store" => $storeId));
    }

    public function deleteAction() {
        $id = $this->getRequest()->getParam('id', 0);
        $storeId = $this->getRequest()->getParam('store', 0);
        try {
            Mage::getModel('fianet/catproduct_association')
                    ->loadByCategoryId($id)
                    ->delete();
            $message = Mage::helper('fianet')->__('Data succesfully deleted.');
            Mage::getSingleton('adminhtml/session')->addSuccess($message);
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/index/', array("id" => $id, "store" => $storeId));
    }

}
