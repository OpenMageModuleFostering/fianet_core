<?php

class Fianet_Core_Model_Fianet_Order_User_Delivery extends Fianet_Core_Model_Fianet_Order_User_Base
{	
	public function __construct($order = null)
	{
		$this->type = 'livraison';
	}
}

?>