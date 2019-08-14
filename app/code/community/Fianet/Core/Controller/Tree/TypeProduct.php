<?php

class Fianet_Core_Controller_Tree_TypeProduct extends Fianet_Core_Controller_Tree_Abstract
{
	protected function _construct()
	{
		parent::_construct();
		$this->_block = 'fianet/tree_typeProduct';
	}
	
	public function treeAction()
    {
        $categoryId = (int) $this->getRequest()->getParam('id');
		$storeId = $this->getRequest()->getParam('store', 0);
        if ($storeId)
		{
            if (!$categoryId) {
                $store = Mage::app()->getStore($storeId);
                $rootId = $store->getRootCategoryId();
                $this->getRequest()->setParam('id', $rootId);
            }
        }

        $category = $this->_initCategory(true);

        $block = $this->getLayout()->createBlock($this->_block);
        $root  = $block->getRoot();
        $this->getResponse()->setBody(Zend_Json::encode(array(
            'data' => $block->getTree(),
            'parameters' => array(
                'text'        => $block->buildNodeName($root),
                'draggable'   => false,
                'allowDrop'   => false,
                'id'          => (int) $root->getId(),
                'expanded'    => (int) $block->getIsWasExpanded(),
                'store_id'    => (int) $block->getStore()->getId(),
                'category_id' => (int) $category->getId(),
                'root_visible'=> (int) $root->getIsVisible()
        ))));
		
		
    }
	
	public function categoriesJsonAction()
    {
        if ($this->getRequest()->getParam('expand_all'))
		{
            Mage::getSingleton('admin/session')->setIsTreeWasExpanded(true);
        }
		else
		{
            Mage::getSingleton('admin/session')->setIsTreeWasExpanded(false);
        }
        if ($categoryId = (int) $this->getRequest()->getPost('id'))
		{
            $this->getRequest()->setParam('id', $categoryId);

            if (!$category = $this->_initCategory())
			{
                return;
            }
            $this->getResponse()->setBody(
                $this->getLayout()->createBlock($this->_block)
                    ->getTreeJson($category)
            );
        }
	}
}