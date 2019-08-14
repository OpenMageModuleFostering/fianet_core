<?php

class Fianet_Core_Block_Configuration_Tab extends Mage_Adminhtml_Block_Widget_Tabs
{
	
	public function __construct()
	{
		parent::__construct();
		$this->setId('fianet_tab');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('fianet')->__('Configuration'));
	}
	
	protected function _beforeToHtml()
	{
		return parent::_beforeToHtml();
	}
	
	public function addConfigurationTab($name, $label, $title, $type)
	{
		$label = Mage::helper('fianet')->__($label);
		$title = Mage::helper('fianet')->__($title);
		if ($type == 'R')
		{
			$label = '<img src="'.$this->getSkinUrl('images/fianet/RnP.gif').'"> ' . $label;
		}
		if ($type == 'S')
		{
			$label = '<img src="'.$this->getSkinUrl('images/fianet/fianet_SAC_icon.gif').'"> ' . $label;
		}
		if ($type != 'R' && $type != 'G' && $type != 'S')
		{
			Mage::throwException($this->__('Invalid tab type, must be R, S or G.'));
		}
	
		$Configuration = Mage::getModel('fianet/configuration')
			->getCollection()
			->setOrder('sort', 'asc');
		$genericBlock = $this->getLayout()->createBlock('fianet/configuration_content')
			->setData('configuration', $Configuration)
			->setData('advanced', '0');
		
		$this->addTab($name, array(
					'label'     => $label,
					'title'     => $title,
					'content'   => $genericBlock->setData('type',$type)->toHtml()
					));
					
	}
	
}