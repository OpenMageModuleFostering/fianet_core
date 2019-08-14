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
class Fianet_Core_Model_MageConfiguration extends Mage_Adminhtml_Model_Config {

    protected function _getList($type = 'carriers') {
        $list = array();
        parent::getSections();
        foreach ($this->_sections->$type->groups->children() as $id => $children) {
			if(!$children->label)
				$list[(string) $id] = (string) $id;
			else
				$list[(string) $id] = (string) $children->label;
		}
        return ($list);
    }

    public function getShippingMethods() {
        return ($this->_getList('carriers'));
    }

    public function getPaymentMethods() {
        return ($this->_getList('payment'));
    }

}