<?php

class Fianet_Core_Model_Mysql4_Catproduct_association_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract 
{
	protected function _construct()
	{
		parent::_construct();
		$this->_init('fianet/catproduct_association');
	}
	
	public function getConfiguredCategoriesCollection()
	{
		$collection = $this->load();
		$list = array();
		foreach ($collection as $catproduct)
		{
			$list[$catproduct->getCatalog_category_entity_id()] = $catproduct->getFianet_product_type();
		}
		return ($list);
	}
}