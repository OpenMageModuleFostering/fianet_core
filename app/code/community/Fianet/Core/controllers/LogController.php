<?php

class Fianet_Core_LogController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('fianet/log')
            ->_addBreadcrumb(Mage::helper('fianet')->__('Log'), Mage::helper('fianet')->__('Item Manager'));
        return $this;
    }    
    
    public function indexAction()
    {
        $this->_initAction()->renderLayout();
	}
	
	public function exportCsvAction()
	{
		$fileName   = 'fianet_log.csv';
		$content    = $this->getLayout()->createBlock('fianet/log_grid')
			->getCsv();
		$this->_sendUploadResponse($fileName, $content);
	}
	
	public function exportXmlAction()
	{
		$fileName   = 'fianet_log.xml';
		$content    = $this->getLayout()->createBlock('fianet/log_grid')
			->getXml();
		
		$this->_sendUploadResponse($fileName, $content);
	}
	
	protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
	{
		$response = $this->getResponse();
		$response->setHeader('HTTP/1.1 200 OK','');
		$response->setHeader('Pragma', 'public', true);
		$response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
		$response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
		$response->setHeader('Last-Modified', date('r'));
		$response->setHeader('Accept-Ranges', 'bytes');
		$response->setHeader('Content-Length', strlen($content));
		$response->setHeader('Content-type', $contentType);
		$response->setBody($content);
		$response->sendResponse();
		die;
	}
	
	public function massDeleteAction() {
		$fianetIds = $this->getRequest()->getParam('logform');
		//Zend_Debug::dump($fianetIds);
		if(!is_array($fianetIds))
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
		}
		else
		{
			try
			{
				foreach ($fianetIds as $fianetId)
				{
					Mage::getModel('fianet/log')->load($fianetId)
					->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(
						Mage::helper('adminhtml')->__(
							'Total of %d record(s) were successfully deleted', count($fianetIds)
							)
						);
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}
}