<?php

class Fianet_Core_Model_Fianet_Order_Adress_Delivery extends Fianet_Core_Model_Fianet_Order_Adress_Base 
{
	public function __construct()
	{
		$this->type = 'livraison';
	}
}
