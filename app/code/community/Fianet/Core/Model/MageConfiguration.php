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
class Fianet_Core_Model_MageConfiguration extends Mage_Adminhtml_Model_Config {

    protected function GetList($type = 'carriers') {
        $list = array();
        parent::getSections();
        foreach ($this->_sections->$type->groups->children() as $Id => $children) {
            $list[(string) $Id] = (string) $children->label;
        }
        return ($list);
    }

    public function getShippingMethods() {
        return ($this->GetList('carriers'));
    }

    public function getPaymentMethods() {
        return ($this->GetList('payment'));
    }

}