<?php

class Fianet_Core_Model_Fianet_Paramcallback_Element
{
	protected $name;
	protected $value;
	
	public function __construct()
	{
	}
	
	public function SetValues($name, $value)
	{
		$this->name = $name;
		$this->value = $value;
		return $this;
	}
	
	public function get_xml()
	{
		$xml = "\t". '<obj>' . "\n";
		$xml .= "\t\t". '<name>'.$this->name.'</name>' . "\n";
		$xml .= "\t\t". '<value>'.$this->value.'</value>' . "\n";
		$xml .= "\t". '</obj>' . "\n";
		return ($xml);
	}
}