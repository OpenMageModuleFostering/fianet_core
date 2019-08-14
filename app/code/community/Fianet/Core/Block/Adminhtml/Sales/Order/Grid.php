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
class Fianet_Core_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid {

    protected function _prepareColumns() {
        parent::_prepareColumns();
        // if Certissim is installed
        if (Mage::helper('fianet')->checkModuleIsInstalled('Fianet_Sac')) {
            $this->addColumn('fianet', array(
                'header' => 'FIA-NET',
                'sortable' => false,
                'type' => 'fianet',
                'align' => 'center',
                'width' => '120',
                'renderer' => 'fianet/adminhtml_widget_grid_column_renderer_fianet',
                'filter' => 'fianet/adminhtml_widget_grid_column_filter_fianet'
            ));
        }
    }

    protected function _prepareMassaction() {
        parent::_prepareMassaction();

        // if Kwixo is installed
        if (Mage::helper('fianet')->checkModuleIsInstalled('Fianet_Kwixo')) {
            $this->getMassactionBlock()->addItem('transaction_state', array(
                'label' => Mage::helper('kwixo')->__('KWIXO FIA-NET : get back transaction\'s state'),
                'url' => $this->getUrl('fianet/adminhtml_order/getTransactionState'),
            ));
        }

        // if Certissim is installed
        if (Mage::helper('fianet')->checkModuleIsInstalled('Fianet_Sac')) {
            $this->getMassactionBlock()->addItem('sent_sac', array(
                'label' => Mage::helper('sac')->__('Sent to CERTISSIM FIA-NET'),
                'url' => $this->getUrl('fianet/adminhtml_order/sentSac'),
            ));
            $this->getMassactionBlock()->addItem('get_eval', array(
                'label' => Mage::helper('sac')->__('CERTISSIM FIA-NET : Get evaluations'),
                'url' => $this->getUrl('fianet/adminhtml_order/getEvaluation'),
            ));
        }

        return $this;
    }

}
