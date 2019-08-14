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
class Fianet_Core_Model_Scope_Abstract extends Mage_Core_Model_Abstract {

    public $_scopeField;

    protected function _construct() {
        parent::_construct();
        $this->_scopeField = Mage::getModel('fianet/configuration_global')->load('CONFIGURATION_SCOPE')->Value;
    }

    public function setScope($value) {
        //Zend_Debug::dump('setScope ' . $value);
        if ($this->_scopeField == 'store_id') {
            $this->Store_id = (integer) $value;
        } elseif ($this->_scopeField == 'group_id') {
            $this->Group_id = (integer) $value;
        } elseif ($this->_scopeField == 'website_id') {
            $this->Website_id = (integer) $value;
        }
        return ($this);
    }

    public function getScope() {
        //Zend_Debug::dump('getScope');
        if ($this->_scopeField == 'store_id') {
            return $this->Store_id;
        } elseif ($this->_scopeField == 'group_id') {
            return $this->Group_id;
        } elseif ($this->_scopeField == 'website_id') {
            return $this->Website_id;
        }
    }

    public function setScopeField($value) {
        $this->_scopeField = $value;
        return $this;
    }

}