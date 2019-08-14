<?php

class Fianet_Core_Block_Configuration extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
		
        $this->_blockGroup = 'fianet';
        $this->_controller = 'configuration';
        $this->_mode = 'edit';
        
        $this->_updateButton('save', 'label', $this->__('Save'));
		$storeId = (integer)$this->getRequest()->getParam('store', 0);
		if ($storeId > 0)
		{
			$deleteButtonData = array(
								'label'		=> $this->__('Delete'),
								'onclick'	=> 'deleteConfirm(\''. Mage::helper('adminhtml')->__('Are you sure you want to do this?').'\', \'' . $this->getDeleteUrl() . '\')',
								'class'		=> 'delete',
								'level'		=> '-1',
							);
			$this->_addButton('delete', $deleteButtonData);
		}
    }
    
	public function getHeaderText()
	{
		return Mage::helper('fianet')->__('Manage configuration');
	}
	
	public function getDeleteUrl()
	{
		$storeId = $this->getRequest()->getParam('store', 0);
		return $this->getUrl('*/*/delete', array('store' => $storeId));
	}
	
	
}