<?php


class Fianet_Core_ShippingController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('fianet/config/shipping');
		$Shippings	= Mage::getModel('fianet/MageConfiguration')
					->getShippingMethods();
		$this->getLayout()->getBlock('shipping')->setData('shipping', $Shippings);
		$this->renderLayout();
	}
	
	public function postAction()
	{
		$post = $this->getRequest()->getPost();
		//Zend_Debug::dump($post);
		try
		{
			if (empty($post))
			{
				Mage::throwException($this->__('Invalid form data.'));
			}
			$data_saved = '';
			$data_notsaved = '';
			
			
			foreach ($post as $Code => $data)
			{
				if (trim($data['conveyorName']) != '')
				{
					$shippingType = $data['shippingType'];
					$deliveryTimes = $data['deliveryTimes'];
					$conveyorName = $data['conveyorName'];
					Mage::getModel('fianet/shipping_association')
						->load($Code)
						->setShipping_code($Code)
						->setFianet_shipping_type($shippingType)
						->setDelivery_times($deliveryTimes)
						->setConveyor_name($conveyorName)
						->save();
				}
			}
			Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Data succesfully saved.'));
		}
		catch (Exception $e)
		{
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*');
	}
}