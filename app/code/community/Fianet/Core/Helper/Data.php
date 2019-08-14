<?php

class Fianet_Core_Helper_Data extends Mage_Core_Helper_Abstract
{
	public static function Initials($string)
	{
		$string = trim($string);
		$init = strtoupper($string[0]);
        for ($i = 1; $i < strlen($string); $i++)
		{
			if ($string[$i - 1] == ' ' && $string[$i] != ' ')
			{
				$init .= strtoupper($string[$i]);
			}
		}
        return $init; 
	} 
}