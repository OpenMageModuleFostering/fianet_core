<?php

class Fianet_Core_Model_Fianet_Order_Adress_Base
{
	protected $type;
	protected $format = 1;
	public $rue1;
	public $rue2;
	public $cpostal;
	public $ville;
	public $pays;
	
	protected function __construct()
	{
	}
	
	public function get_xml()
	{
		$xml = '';
		if ($this->type != null)
		{
			$xml .= "\t" . '<adresse type="'.$this->type.'" format="'.$this->format.'">' . "\n";
			if ($this->rue1 != '')
			{
				$xml .= "\t\t" . '<rue1>'.Mage::getModel('fianet/functions')->clean_invalid_char($this->rue1).'</rue1>' . "\n";
			}
			else
			{
				Mage::throwException("Mage_Fianet_Model_Fianet_Order_Adress_Base::get_xml() - rue1 is undefined");
			}
			if ($this->rue2 != "")
			{
				$xml .= "\t\t" . '<rue2>'.Mage::getModel('fianet/functions')->clean_invalid_char($this->rue2).'</rue2>' . "\n";
			}
			if ($this->cpostal != "")
			{
				$xml .= "\t\t" . '<cpostal>'.Mage::getModel('fianet/functions')->clean_invalid_char($this->cpostal).'</cpostal>' . "\n";
			}
			else
			{
				Mage::throwException("Mage_Fianet_Model_Fianet_Order_Adress_Base::get_xml() - cpostal is undefined");
			}
			if ($this->ville != "")
			{
				$xml .= "\t\t" . '<ville>'.Mage::getModel('fianet/functions')->clean_invalid_char($this->ville).'</ville>' . "\n";
			}
			else
			{
				Mage::throwException("Mage_Fianet_Model_Fianet_Order_Adress_Base::get_xml() - ville is undefined");
			}
			if ($this->pays != "")
			{
				$xml .= "\t\t" . '<pays>'.Mage::getModel('fianet/functions')->clean_invalid_char($this->pays).'</pays>' . "\n";
			}
			else
			{
				Mage::throwException("Mage_Fianet_Model_Fianet_Order_Adress_Base::get_xml() - pays is undefined");
			}
			$xml .= "\t" . '</adresse>' . "\n";
		}
		return ($xml);
	}
}