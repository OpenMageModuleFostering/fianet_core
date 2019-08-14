<?php

 
class Fianet_Core_Block_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('fianetGrid');
        $this->setDefaultSort('date');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('fianet/log')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('date', array(
            'header'    => Mage::helper('fianet')->__('Date'),
            'align'     =>'left',
            'index'     => 'date',
            'type'		=> 'datetime',
        ));
 
        $this->addColumn('message', array(
            'header'    => Mage::helper('fianet')->__('Message'),
            'align'     =>'left',
            'index'     => 'message',
        ));
		$this->addExportType('*/*/exportCsv', Mage::helper('fianet')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('fianet')->__('XML'));
        return parent::_prepareColumns();
    }
    
	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('logform');
		$this->getMassactionBlock()->addItem('delete', array(
					'label'    => Mage::helper('fianet')->__('Delete'),
					'url'      => $this->getUrl('*/*/massDelete'),
					'confirm'  => html_entity_decode(Mage::helper('fianet')->__('Are you sure ?'), ENT_COMPAT, 'UTF-8')
					));
		
		return $this;
	}
 /*
    public function getRowUrl($row)
    {
        return $this->getUrl('', array('id' => $row->getId()));
    }*/
 
 
}