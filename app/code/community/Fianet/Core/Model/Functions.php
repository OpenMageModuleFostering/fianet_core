<?php

class Fianet_Core_Model_Functions
{
	public function clean_xml($xml)
	{
		$xml = str_replace("\\'", "'", $xml);
		$xml = str_replace("\\\"", "\"", $xml);
		$xml = str_replace("\\\\", "\\", $xml);
		$xml = str_replace("\t", "", $xml);
		$xml = str_replace("\n", "", $xml);
		$xml = str_replace("\r", "", $xml);
		$xml = trim($xml);
		return ($xml);
	}
	
	public function clean_invalid_char($var)
	{
		//supprimes les balises html
		$var = strip_tags($var);
		//$var = str_replace("&", "&&amp;", $var);
		$var = str_replace('&', '', $var);
		$var = str_replace("<", "&lt;", $var);
		$var = str_replace(">", "&gt;", $var);
		$var = trim($var);
		return ($var);
	}
	
	public function var_is_object_of_class($var, $class_name)
	{
		$res = false;
		if (is_object($var))
		{
			$name = get_class($var);
			if ($name == $class_name)
			{
				$res = true;
			}
		}
		return ($res);
	}
	
	//Calcule la date de livraison en jour ouvré à partir de la date courante
	public function get_delivery_date($delivery_times)
	{
		define('H', date("H"));
		define('i', date("i"));
		define('s', date("s"));
		define('m', date("m"));
		define('d', date("d"));
		define('Y', date("Y"));
		define('SUNDAY', 0);
		define('SATURDAY', 6);
		
		$nb_days = 0;
		$j = 0;
		while ($nb_days < $delivery_times)
		{
			$j++;
			$date = mktime(H, i, s, m, d + $j, Y);
			$day = date("w", $date);
			if ($day != SUNDAY && $day != SATURDAY)
			{
				$nb_days++;
			}
		}
		if ($j > 23)
		{//si on dépasse le délais de livraison max à causes des samedi et dimanche on remet le délais de livraison à son maximum
			$j = 23;
		}
		//$j = 200;
		$date = mktime(H, i, s, m, d + $j, Y);
		return (date("Y-m-d", $date));
	}
	
	public function getStore()
	{
		$scope = Mage::getModel('fianet/configuration_global')
					->load('CONFIGURATION_SCOPE')
					->Value;
		$store = 0;
		switch ($scope)
		{
			case('website_id'):
				$store = (integer)Mage::app()->getStore(true)->getWebsiteId();
				break;
			case ('group_id'):
				$store = (integer)Mage::app()->getStore(true)->getGroupId();
				break;
			case('store_id'):
				$store = (integer)Mage::app()->getStore(true)->getStoreId();
				break;
		}
		if ($store == 0)
		{
			Mage::getModel('fianet/log')->log('Unable to retrieve ' . $scope);
		}
		return ($store);
	}
	
	public static function xml2array($contents, $get_attributes=1)
	{ 
		if(!$contents) return array(); 

		if(!function_exists('xml_parser_create'))
		{ 
			//print "'xml_parser_create()' function not found!"; 
			return array(); 
		} 
		//Get the XML parser of PHP - PHP must have this module for the parser to work 
		$parser = xml_parser_create(); 
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 ); 
		xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 ); 
		xml_parse_into_struct( $parser, $contents, $xml_values ); 
		xml_parser_free( $parser ); 

		if(!$xml_values) return;//Hmm... 

		//Initializations 
		$xml_array = array(); 
		$parents = array(); 
		$opened_tags = array(); 
		$arr = array(); 

		$current = &$xml_array; 

		//Go through the tags. 
		foreach($xml_values as $data)
		{ 
			unset($attributes,$value);//Remove existing values, or there will be trouble 
			//This command will extract these variables into the foreach scope 
			// tag(string), type(string), level(int), attributes(array). 
			extract($data);//We could use the array by itself, but this cooler. 

			$result = ''; 
			if($get_attributes)
			{//The second argument of the function decides this. 
				$result = array(); 
				if(isset($value)) $result['value'] = $value; 
				//Set the attributes too. 
				if(isset($attributes))
				{ 
					foreach($attributes as $attr => $val)
					{ 
						if($get_attributes == 1) $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr' 
						/**  :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too */ 
					} 
				} 
			}
			elseif(isset($value))
			{ 
				$result = $value; 
			} 

			//See tag status and do the needed. 
			if($type == "open")
			{//The starting of the tag '<tag>' 
				$parent[$level-1] = &$current; 
				if(!is_array($current) or (!in_array($tag, array_keys($current))))
				{ //Insert New tag 
					$current[$tag] = $result; 
					$current = &$current[$tag]; 

				}
				else
				{ //There was another element with the same tag name 
					if(isset($current[$tag][0]))
					{				
						array_push($current[$tag], $result); 
					}
					else
					{ 
						$current[$tag] = array($current[$tag],$result); 
					} 
					$last = count($current[$tag]) - 1; 
					$current = &$current[$tag][$last]; 
				} 

			}
			elseif($type == "complete")
			{ //Tags that ends in 1 line '<tag />' 
				//See if the key is already taken. 
				if(!isset($current[$tag]))
				{ //New Key 
					$current[$tag] = $result;
					//array_push($current[$tag],$result);
				}
				else
				{ //If taken, put all things inside a list(array) 
					if((is_array($current[$tag]) and $get_attributes == 0)//If it is already an array... 
						or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1))
					{ 
						array_push($current[$tag],$result); // ...push the new element into that array. 
					}
					else
					{ //If it is not an array... 
						$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value 
					} 
				} 

			}
			elseif ($type == 'close')
			{ //End of tag '</tag>' 
				$current = &$parent[$level-1]; 
			} 
		} 
		return($xml_array); 
	}
	
	public static function compare_billing_and_shipping(Mage_Sales_Model_Order_Address $billing, Mage_Sales_Model_Order_Address $shipping)
	{
		$identical = true;
		if ($billing->getLastname() != $shipping->getLastname())
		{
			$identical = false;
		}
		if ($billing->getFirstname() != $shipping->getFirstname())
		{
			$identical = false;
		}
		if ($billing->getTelephone() != $shipping->getTelephone())
		{
			$identical = false;
		}
		if ($billing->getFax() != $shipping->getFax())
		{
			$identical = false;
		}
		if ($billing->getStreet(1) != $shipping->getStreet(1))
		{
			$identical = false;
		}
		if ($billing->getStreet(2) != $shipping->getStreet(2))
		{
			$identical = false;
		}
		if ($billing->getPostcode() != $shipping->getPostcode())
		{
			$identical = false;
		}
		if ($billing->getCity() != $shipping->getCity())
		{
			$identical = false;
		}
		if ($billing->getCountry() != $shipping->getCountry())
		{
			$identical = false;
		}
		if ($billing->getCompany() != $shipping->getCompany())
		{
			$identical = false;
		}
		
		return ($identical);
	}
}