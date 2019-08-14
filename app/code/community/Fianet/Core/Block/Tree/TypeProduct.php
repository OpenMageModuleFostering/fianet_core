<?php

class Fianet_Core_Block_Tree_TypeProduct extends Fianet_Core_Block_Tree_Abstract
{
	public function __construct()
	{
		parent::__construct();
	}
	
	protected function _getTypeProductLabel($typeProductId)
	{
		$types = Mage::getModel('fianet/source_TypeProduct')->toOptionArray();
		foreach ($types as $data)
		{
			if ($data['value'] == $typeProductId)
			{
				return ($data['label']);
			}
		}
		return '';
	}
	
	public function buildNodeName($node)
    {
        $result = $this->htmlEscape($node->getName());
        
		$productType = Mage::getModel('fianet/catproduct_association')
						->loadByCategorieId($node->getEntityId());			
		if ($productType->getId() > 0)
		{
			$result .= ' ('.Fianet_Core_Helper_Data::Initials($this->_getTypeProductLabel($productType->getFianet_product_type())).')';
		}
		
        return $result;
    }
		
}