<?php

class Fianet_Core_Model_Fianet_Paramcallback_Builder
{
	protected $param_list = array();
	
	public function __construct()
	{
	}
	
	public function add_param($param)
	{
		if (Mage::getModel('fianet/functions')->var_is_object_of_class($param, 'Fianet_Core_Model_Fianet_Paramcallback_Element'))
		{
			$this->param_list[] = $param;
		}
		else
		{
			Mage::getModel('fianet/log')->Log("Erreur : le parametre n'est pas un objet Fianet_Core_Model_Fianet_Paramcallback_Element mais un objet : ".get_class($param));
		}
	}
	
	public function get_xml()
	{
		$xml = '';
		
		if (count($this->param_list) > 0)
		{
			$xml .= '<?xml version="1.0" encoding="'.Mage::getModel('fianet/configuration')->getGlobalValue('XML_ENCODING').'" ?>
					<ParamCBack>' . "\n";
			
			foreach ($this->param_list as $param)
			{
				$xml .= $param->get_xml();
			}
			
			$xml .= '</ParamCBack>' . "\n";
		}
		
		return($xml);
	}
}
