<?php

class Fianet_Core_Block_Fianetadmingrid extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'log';
		$this->_blockGroup = 'fianet';
		$this->_headerText = Mage::helper('fianet')->__('Log');
		$this->setTemplate('widget/grid/container.phtml');
		//$this->_addButtonLabel = Mage::helper('fianet')->__('Add Item');
	}
}
