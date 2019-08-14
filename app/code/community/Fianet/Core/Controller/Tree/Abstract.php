<?php

interface IFianetTree
{
	public function treeAction();
	public function categoriesJsonAction();	
}


abstract class Fianet_Core_Controller_Tree_Abstract  extends Mage_Adminhtml_Controller_Action implements IFianetTree
{
	private $_block = 'fianet/tree_abstract';
	
	protected function _construct()
	{
		parent::_construct();
	}

	protected function _initCategory($getRootInstead = false)
    {
		//Zend_Debug::dump('Fianet_Core_Controller_Tree_Abstract::_initCategory()');
        $categoryId = (int) $this->getRequest()->getParam('id',false);
        $storeId    = (int) $this->getRequest()->getParam('store', 0);
        $category = Mage::getModel('catalog/category');
        $category->setStoreId($storeId);

        if ($categoryId)
		{
            $category->load($categoryId);
            if ($storeId)
			{
                $rootId = Mage::app()->getStore($storeId)->getRootCategoryId();
                if (!in_array($rootId, $category->getPathIds()))
				{
                    // load root category instead wrong one
                    if ($getRootInstead)
					{
                        $category->load($rootId);
                    }
                    else
					{
                        $this->_redirect('*/*/', array('_current'=>true, 'id'=>null));
                        return false;
                    }
                }
            }
        }

        Mage::register('category', $category);
        Mage::register('current_category', $category);
		//Zend_Debug::dump("Mage::register('current_category', ".$category->getId().")");
        return $category;
    }
	
}