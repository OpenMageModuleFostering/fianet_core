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
class Fianet_Core_Model_Mysql4_Abstract extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        parent::_construct();
    }

    public function save(Mage_Core_Model_Abstract $object) {
        //Zend_Debug::dump('Save');
        if ($object->isDeleted()) {
            return $this->delete($object);
        }

        $this->_beforeSave($object);
        $this->_checkUnique($object);

        if (!is_null($object->getId()) || $object->getId() === 0) {
            $condition = $this->_getWriteAdapter()->quoteInto($this->getIdFieldName() . '=?', $object->getId());
            if ($this->_exist($object)) {
                $this->_getWriteAdapter()->update($this->getMainTable(), $this->_prepareDataForSave($object), $condition);
            } else {
                $this->_getWriteAdapter()->insert($this->getMainTable(), $this->_prepareDataForSave($object));
            }
        } else {
            $this->_getWriteAdapter()->insert($this->getMainTable(), $this->_prepareDataForSave($object));
            $object->setId($this->_getWriteAdapter()->lastInsertId($this->getMainTable()));
        }

        $this->_afterSave($object);

        return $this;
    }

    protected function _exist(Mage_Core_Model_Abstract $object) {
        if (is_null($object->getId())) {
            return (false);
        }
        $select = $this->_getWriteAdapter()->select()
                ->from($this->getMainTable())
                ->reset(Zend_Db_Select::WHERE)
                ->where($this->getIdFieldName() . '=?', $object->getId());
        if ($this->_getWriteAdapter()->fetchRow($select)) {
            return (true);
        }
        return (false);
    }

}