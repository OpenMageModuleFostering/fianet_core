<?php

/**
 * 2000-2012 FIA-NET
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is available
 * through the world-wide-web at this URL: http://www.opensource.org/licenses/OSL-3.0
 * If you are unable to obtain it through the world-wide-web, please contact us
 * via http://www.fia-net-group.com/formulaire.php so we can send you a copy immediately.
 *
 *  @author FIA-NET <support-boutique@fia-net.com>
 *  @copyright 2000-2012 FIA-NET
 *  @version Release: $Revision: 1.0.1 $
 *  @license http://www.opensource.org/licenses/OSL-3.0  Open Software License (OSL 3.0)
 */
class Fianet_Core_Block_Adminhtml_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('fianetGrid');
        $this->setDefaultSort('date');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('fianet/log')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('date', array(
            'header' => Mage::helper('fianet')->__('Date'),
            'align' => 'left',
            'index' => 'date',
            'type' => 'datetime',
            'width' => '125px'
        ));

        $this->addColumn('message', array(
            'header' => Mage::helper('fianet')->__('Message'),
            'align' => 'left',
            'index' => 'message',
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('fianet')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('fianet')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('logform');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('fianet')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('fianet')->__('Are you sure ?')
        ));

        return $this;
    }

    public function getRowUrl($row) {
        return false;
    }

}
