<?php

class Fianet_Core_Model_Fianet_Order_User_Siteconso
{
	public $nb = 0;
	public $ca = 0;
	public $datepremcmd = null;
	public $datederncmd = null;
	
	public function __construct()
	{
	}
	
	public function get_xml()
	{
		$xml = '';
		if ($this->nb > 0)
		{
			$xml .= "\t\t" . '<siteconso>' . "\n";
			$xml .= "\t\t\t" . '<nb>'.$this->nb.'</nb>' . "\n";
			$xml .= "\t\t\t" . '<ca>'.number_format($this->ca, 2, '.', '').'</ca>' . "\n";
			$xml .= "\t\t\t" . '<datepremcmd>'.$this->datepremcmd.'</datepremcmd>' . "\n";
			$xml .= "\t\t\t" . '<datederncmd>'.$this->datederncmd.'</datederncmd>' . "\n";
			$xml .= "\t\t" . '</siteconso>' . "\n";
		}
		return ($xml);
	}
}