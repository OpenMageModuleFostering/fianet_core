<?php
class Fianet_Core_Block_FianetAdmin extends Mage_Adminhtml_Block_Template
{
	public function _prepareLayout()
	{
		return parent::_prepareLayout();
	}
	
	public function getFianet()     
	{ 
		if (!$this->hasData('fianet'))
		{
			$this->setData('fianet', Mage::registry('fianet'));
		}
		return $this->getData('fianet');
	}
}