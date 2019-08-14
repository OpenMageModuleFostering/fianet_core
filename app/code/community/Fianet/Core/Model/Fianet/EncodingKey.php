<?php

class Fianet_Core_Model_Fianet_EncodingKey
{ 
	private $clMD5;
	
	function __construct()
	{
		$cryptage = Mage::getModel('fianet/configuration_global')
					->load('RNP_CRYPTAGE')
					->Value;
		$this->clMD5 = Mage::getModel('fianet/fianet_'.$cryptage);
	}
	
	public function giveHashCode($pkey, $second, $email, $refid, $montant, $nom) {
		
		$modulo = $second % 4;
		
		switch($modulo) {
			case 0:
				$select = $montant;
				break;    
			case 1:
				$select = $email;
				break;    
			case 2:
				$select = $refid;
				break;    
			case 3:
				$select = $nom;
				break;    
			default:
				break;    
		}
		
		return $this->clMD5->hash($pkey.$refid.$select);
		
	}
	
	public function giveHashCode2($pkey, $second, $email, $refid, $montant, $nom) {
		$modulo = $second % 4;
		
		$montant = sprintf("%01.2f",$montant);
		
		switch($modulo) {
			case 0:
				$select = $montant;
				break;    
			case 1:
				$select = $email;
				break;    
			case 2:
				$select = $refid;
				break;    
			case 3:
				$select = $nom;
				break;    
			default:
				break;    
		}
		
		return $this->clMD5->hash($pkey.$refid.$montant.$email.$select);
		
	}
}
