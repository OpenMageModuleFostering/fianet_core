<?php

class Fianet_Core_Model_Catproduct_Association extends Mage_Core_Model_Abstract 
{
	protected function _construct()
	{
		parent::_construct();
		$this->_init('fianet/catproduct_association');
	}
	
	public function loadByCategorieId($id)
	{
		$this->_getResource()->load($this, $id, 'catalog_category_entity_id');
		return ($this);
	}
}