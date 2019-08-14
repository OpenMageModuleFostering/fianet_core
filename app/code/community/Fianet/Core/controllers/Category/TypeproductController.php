<?php


class Fianet_Core_Category_TypeproductController extends Fianet_Core_Controller_Tree_TypeProduct
{
	
	public function indexAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('fianet/config/catproduct');
		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true)
            ->setContainerCssClass('catalog-categories');
		
		$selectedCategory = Mage::getSingleton('admin/session')->getLastEditedCategory(true);
		if ($selectedCategory)
		{
             $this->getRequest()->setParam('id', $selectedCategory);
        }
		$selectedCategory = (int) $this->getRequest()->getParam('id', 0);
		$this->_initCategory(true);
		if ($selectedCategory > 0)
		{
			$this->getLayout()->getBlock('tree')->setData('selectedCategory', $selectedCategory);
		}	
		$this->renderLayout();
	}
	
	public function editAction()
	{
		
		$params['_current'] = true;
        $redirect = false;

        $storeId = (int) $this->getRequest()->getParam('store');
        $parentId = (int) $this->getRequest()->getParam('parent');
        $_prevStoreId = Mage::getSingleton('admin/session')
            ->getLastViewedStore(true);

        if ($_prevStoreId != null && !$this->getRequest()->getQuery('isAjax')) {
            $params['store'] = $_prevStoreId;
            $redirect = true;
        }
			
		
		$_prevCategoryId = Mage::getSingleton('admin/session')
            ->getLastEditedCategory(true);
        if ($_prevCategoryId && !$this->getRequest()->getQuery('isAjax'))
		{
             $this->getRequest()->setParam('id', $_prevCategoryId);
        }
		if ($redirect)
		{
            $this->_redirect('*/*/edit', $params);
            return;
        }
		
		$categoryId = (int) $this->getRequest()->getParam('id');
		if ($storeId && !$categoryId && !$parentId)
		{
            $store = Mage::app()->getStore($storeId);
            $_prevCategoryId = (int) $store->getRootCategoryId();
            $this->getRequest()->setParam('id', $_prevCategoryId);
        }
		
		if (!($category = $this->_initCategory(true)))
		{
            return;
        }
		
		$data = Mage::getSingleton('adminhtml/session')->getCategoryData(true);
        if (isset($data['general'])) {
            $category->addData($data['general']);
        }
		
		if ($this->getRequest()->getQuery('isAjax'))
		{
            // prepare breadcrumbs of selected category, if any
            
			$breadcrumbsPath = $category->getPath();
            if (empty($breadcrumbsPath))
			{
                // but if no category, and it is deleted - prepare breadcrumbs from path, saved in session
                $breadcrumbsPath = Mage::getSingleton('admin/session')->getDeletedPath(true);
                if (!empty($breadcrumbsPath))
				{
                    $breadcrumbsPath = explode('/', $breadcrumbsPath);
                    // no need to get parent breadcrumbs if deleting category level 1
                    if (count($breadcrumbsPath) <= 1)
					{
                        $breadcrumbsPath = '';
                    }
                    else
					{
                        array_pop($breadcrumbsPath);
                        $breadcrumbsPath = implode('/', $breadcrumbsPath);
                    }
                }
            }
		
            Mage::getSingleton('admin/session')
                ->setLastViewedStore($this->getRequest()->getParam('store'));
            Mage::getSingleton('admin/session')
                ->setLastEditedCategory($category->getId());
            $this->_initLayoutMessages('adminhtml/session');
			//Zend_Debug::dump('Fianet_Core_CatproductController::editAction()');
			//Zend_Debug::dump($breadcrumbsPath);
            $this->getResponse()->setBody(
                $this->getLayout()->getMessagesBlock()->getGroupedHtml()
                . $this->getLayout()->createBlock('fianet/category_typeproduct_edit')->toHtml()
                . $this->getLayout()->getBlockSingleton('adminhtml/catalog_category_tree')
                    ->getBreadcrumbsJavascript($breadcrumbsPath, 'editingCategoryBreadcrumbs')
            );
            return;
        }
		$this->_redirect('*/*/index');
	}
	
	public function saveAction()
	{
		$post = $this->getRequest()->getPost();
		$storeid = $post['storeId'];
		//Zend_Debug::dump($post);
		try
		{
			if (empty($post))
			{
				Mage::throwException($this->__('Invalid form data.'));
			}
			if (isset($post["typeProduct"]))
			{
				if ($post["typeProduct"] != "" && $post["typeProduct"] != "0")
				{
					$productType = Mage::getModel('fianet/catproduct_association')
							->loadByCategorieId($post['id']);
					if ($productType->getId() > 0)
					{
						$productType->delete();
						$productType = Mage::getModel('fianet/catproduct_association');
					}
					$productType->setCatalog_category_entity_id($post['id'])
							->setFianet_product_type($post["typeProduct"])
							->save();
					$message = Mage::helper('fianet')->__('Data succesfully saved.');
					Mage::getSingleton('adminhtml/session')->addSuccess($message);
				}
				if (isset($post['applysubcat']))
				{
					$category = Mage::getModel('catalog/category')->load($post['id']);
					$subcat = $category->getAllChildren(true);
					foreach ($subcat as $categoryId)
					{	
						$productType = Mage::getModel('fianet/catproduct_association')
							->loadByCategorieId($categoryId);
						if ($productType->getId() > 0)
						{
							$productType->delete();
							$productType = Mage::getModel('fianet/catproduct_association');
						}
						$productType->setCatalog_category_entity_id($categoryId)
								->setFianet_product_type($post["typeProduct"])
								->save();
						}
				}
			}
		}
		catch (Exception $e)
		{
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index/', array('_current' => true, "id"=>$post['id'], "store"=>$storeid));
		//$url = $this->getUrl('*/*/edit', array('_current' => true, 'id' => $post['id']));
        //$this->getResponse()->setBody(
        //    '<script type="text/javascript">parent.updateContent("' . $url . '", {}, true);</script>'
        //);
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id', 0);
		$storeid = $this->getRequest()->getParam('store', 0);
		//Zend_Debug::dump($id);
		try
		{
			$productType = Mage::getModel('fianet/catproduct_association')
					->loadByCategorieId($id)
					->delete();
			$message = Mage::helper('fianet')->__('Data succesfully deleted.');
			Mage::getSingleton('adminhtml/session')->addSuccess($message);
		}
		catch (Exception $e)
		{
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index/', array("id"=>$id, "store"=>$storeid));
	}

	
	
}