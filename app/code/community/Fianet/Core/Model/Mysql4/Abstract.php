<?php

class Fianet_Core_Model_Mysql4_Abstract extends Mage_Core_Model_Mysql4_Abstract
{
	protected function _construct()
	{
		parent::_construct();
	}
	
	public function save(Mage_Core_Model_Abstract $object)
	{
		//Zend_Debug::dump('Save');
		if ($object->isDeleted()) {
			return $this->delete($object);
		}
		
		$this->_beforeSave($object);
		$this->_checkUnique($object);

		if (!is_null($object->getId()) || $object->getId() === 0)
		{
			$condition = $this->_getWriteAdapter()->quoteInto($this->getIdFieldName().'=?', $object->getId());
			if ($this->_exist($object))
			{
				$this->_getWriteAdapter()->update($this->getMainTable(), $this->_prepareDataForSave($object), $condition);
			}
			else
			{
				$this->_getWriteAdapter()->insert($this->getMainTable(), $this->_prepareDataForSave($object));
			}
		}
		else
		{
			$this->_getWriteAdapter()->insert($this->getMainTable(), $this->_prepareDataForSave($object));
			$object->setId($this->_getWriteAdapter()->lastInsertId($this->getMainTable()));
		}
		
		$this->_afterSave($object);
		
		return $this;
	}
	
	protected function _exist(Mage_Core_Model_Abstract $object)
	{
		if (is_null($object->getId()))
		{
			return (false);
		}
		$select = $this->_getWriteAdapter()->select()
			->from($this->getMainTable())
			->reset(Zend_Db_Select::WHERE)
			->where($this->getIdFieldName().'=?', $object->getId());
		if ($this->_getWriteAdapter()->fetchRow($select) )
		{
			return (true);
		}
		return (false);
	}
}