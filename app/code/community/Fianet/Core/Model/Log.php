<?php

class Fianet_Core_Model_Log extends Mage_Core_Model_Abstract 
{
	protected function _construct()
	{
		parent::_construct();
		$this->_init('fianet/log');
	}
	
	public function Log($message)
	{
		$this->setMessage(utf8_encode($message))
		->save();
	}
}