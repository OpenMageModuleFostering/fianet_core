<?php
//Mage::getModel('receiveandpay/payment_transaction')->GetPertinentTagForOrder(100000040);

class Fianet_Core_Block_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
	/*
	protected function _prepareCollection()
	{
		parent::_prepareCollection();
		$collection = $this->getCollection()->clear();
		//$collection->joinAttribute('fianet_rnp_tag', 'order/fianet_rnp_tag', 'entity_id', null, 'left');
		//$collection->addAttributeToSelect('fianet_rnp_tag', 'outer');
		/*$this->getCollection()->load();
		$this->setCollection($collection);
	}
	*/
	
	protected function _prepareColumns()
	{
		parent::_prepareColumns();
		$this->addColumn('fianet', array(
					'header'=> 'FIA-NET',
					'sortable'  => false,
					'type' => 'fianet',
					'align' => 'center',
					'width'=>'20',
					'renderer'=>'fianet/widget_grid_column_renderer_fianet',
					'filter'=>'fianet/widget_grid_column_filter_fianet'
					));
	}
	
	protected function _prepareMassaction()
    {
		parent::_prepareMassaction();
		
		if (Fianet_Core_Model_Configuration::CheckModuleIsInstalled('Fianet_Sac'))
		{
			$nb = 0;
			try
			{
				$nb = Mage::getModel('sac/action')->getEvaluation();
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
			if ($nb > 0)
			{
				Mage::getSingleton('adminhtml/session')->addSuccess($this->__('%s evaluation(s) successfully receipted', $nb));
			}
			
			Mage::getModel('sac/action')->GetReevaluation();
			
			$this->getMassactionBlock()->addItem('sent_sac', array(
					'label'=> Mage::helper('fianet')->__('Sent to SAC FIA-NET'),
					'url'  => $this->getUrl('sac/order/sentSac'),
					));
		}
		return $this;
	}
	
}