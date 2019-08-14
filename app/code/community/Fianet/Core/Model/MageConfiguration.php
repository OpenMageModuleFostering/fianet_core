<?php

class Fianet_Core_Model_MageConfiguration extends Mage_Adminhtml_Model_Config
{
	protected function GetList($type = 'carriers')
	{
		$list = array();
		parent::getSections();
		foreach ($this->_sections->$type->groups->children() as $Id => $children)
		{
			$list[(string)$Id] = (string)$children->label;
		}
		return ($list);
	}
		
	public function getShippingMethods()
	{
		return ($this->GetList('carriers'));
	}
	
	public function getPaymentMethods()
	{
		return ($this->GetList('payment'));
	}
}