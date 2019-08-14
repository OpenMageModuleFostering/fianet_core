<?php

class Fianet_Core_Model_Fianet_Order_Adress_Billing extends Fianet_Core_Model_Fianet_Order_Adress_Base 
{
	public function __construct($order = null)
	{
		$this->type = 'facturation';
	}
}
