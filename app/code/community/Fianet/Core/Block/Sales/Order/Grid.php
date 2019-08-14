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
 *  @author Quadra Informatique <ecommerce@quadra-informatique.fr>
 *  @copyright 2000-2012 FIA-NET
 *  @version Release: $Revision: 0.9.0 $
 *  @license http://www.opensource.org/licenses/OSL-3.0  Open Software License (OSL 3.0)
 */
class Fianet_Core_Block_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid {

    /*protected function _prepareCollection() {
        parent::_prepareCollection();
        $collection = $this->getCollection()->clear();
        //$collection->joinAttribute('fianet_rnp_tag', 'order/fianet_rnp_tag', 'entity_id', null, 'left');
        //$collection->addAttributeToSelect('fianet_rnp_tag', 'outer');
        $this->getCollection()->load();
        $this->setCollection($collection);
    }*/

    protected function _prepareColumns() {
        parent::_prepareColumns();
        $this->addColumn('fianet', array(
            'header' => 'FIA-NET',
            'sortable' => false,
            'type' => 'fianet',
            'align' => 'center',
            'width' => '20',
            'renderer' => 'fianet/widget_grid_column_renderer_fianet',
            'filter' => 'fianet/widget_grid_column_filter_fianet'
        ));
    }

    protected function _prepareMassaction() {
        parent::_prepareMassaction();

        if (Fianet_Core_Model_Configuration::CheckModuleIsInstalled('Fianet_Sac')) {
             //Retiré la reception des évaluations et des reevaluations dans la page commande pour éviter des lenteurs é l'ouverture de la page
             //le cron est utilisé en remplacement

            /*
            $nb = 0;
            try {
                $nb = Mage::getModel('sac/action')->getEvaluation();
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            if ($nb > 0) {
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('%s evaluation(s) successfully receipted', $nb));
            }

            Mage::getModel('sac/action')->GetReevaluation();
             */

            $this->getMassactionBlock()->addItem('sent_sac', array(
                'label' => Mage::helper('fianet')->__('Sent to SAC FIA-NET'),
                'url' => $this->getUrl('sac/order/sentSac'),
            ));
            $this->getMassactionBlock()->addItem('get_eval', array(
                'label' => Mage::helper('fianet')->__('Get evaluations'),
                'url' => $this->getUrl('sac/order/getEvaluation'),
            ));
        }
        return $this;
    }

}