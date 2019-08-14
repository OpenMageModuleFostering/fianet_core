<?php

class Fianet_Core_Model_Fianet_Order_Info_Transport
{
	public $type;
	public $nom;
	public $rapidite;
	
	public function __construct()
	{
	}
	
	public function get_xml()
	{
		$xml = '';
		if ($this->type == null)
		{
			Mage::throwException("Mage_Fianet_Model_Fianet_Order_Info_Transport::get_xml() - Transport type undefined");
		}
		if ($this->nom == null)
		{
			Mage::throwException("Mage_Fianet_Model_Fianet_Order_Info_Transport::get_xml() - Transport name undefined");
		}
		if ($this->rapidite == null)
		{
			Mage::throwException("Mage_Fianet_Model_Fianet_Order_Info_Transport::get_xml() - Transport time undefined");
		}
		$xml .= "\t\t". '<transport>' . "\n";
		
		$xml .= "\t\t\t". '<type>'.$this->type.'</type>' . "\n";
		$xml .= "\t\t\t". '<nom>'.Mage::getModel('fianet/functions')->clean_invalid_char($this->nom).'</nom>' . "\n";
		$xml .= "\t\t\t". '<rapidite>'.$this->rapidite.'</rapidite>' . "\n";
		
		$xml .= "\t\t". '</transport>' . "\n";
		return ($xml);
	}
}
