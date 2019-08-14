<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Store switcher block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Fianet_Core_Block_Store_Switcher extends Mage_Adminhtml_Block_Template
{
	protected $_storeIds;
	
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('fianet/common/storeswitcher.phtml');
		$this->setUseConfirm(true);
		$this->setDefaultStoreName($this->Helper('fianet')->__('All Store'));
	}
	
	public function getWebsiteCollection()
	{
		$collection = Mage::getModel('core/website')->getResourceCollection();
		
		$websiteIds = $this->getWebsiteIds();
		if (!is_null($websiteIds)) {
			$collection->addIdFilter($this->getWebsiteIds());
		}
		
		return $collection->load();
	}
	
	public function getGroupCollection($website)
	{
		if (!$website instanceof Mage_Core_Model_Website) {
			$website = Mage::getModel('core/website')->load($website);
		}
		return $website->getGroupCollection();
	}
	
	public function getStoreCollection($group)
	{
		if (!$group instanceof Mage_Core_Model_Store_Group) {
			$group = Mage::getModel('core/store_group')->load($group);
		}
		$stores = $group->getStoreCollection();
		if (!empty($this->_storeIds)) {
			$stores->addIdFilter($this->_storeIds);
		}
		return $stores;
	}
	
	public function getSwitchUrl()
	{
		if ($url = $this->getData('switch_url')) {
			return $url;
		}
		return $this->getUrl('*/*/*', array('_current'=>true, 'store'=>null));
	}
	
	public function getStoreId()
	{
		return $this->getRequest()->getParam('store');
	}
	
	public function setStoreIds($storeIds)
	{
		$this->_storeIds = $storeIds;
		return $this;
	}
	
	public function getDefaultStoreName()
	{
		$scope = Mage::getModel('fianet/configuration_global')->load('CONFIGURATION_SCOPE')->Value;
		$name = 'All';
		switch ($scope)
		{
			case('website_id'):
				$name = 'All website';
				break;
			case('group_id'):
				$name = 'All store';
				break;
			case('store_id'):
				$name = 'All store view';
				break;
		}
		return ($this->__($name));
	}
	
	public function isShow()
	{
		return !Mage::app()->isSingleStoreMode();
	}
	
	protected function _toHtml()
	{
		if (!Mage::app()->isSingleStoreMode()) {
			return parent::_toHtml();
		}
		return '';
	}
}