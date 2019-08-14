<?php

class Fianet_Core_Model_Fianet_Sender
{
	protected $orders	= array();
	protected $encoding	= null;
	protected $sites_conf = array();
	
	public function __construct()
	{
		$this->encoding	= (string)Mage::getModel('fianet/configuration')->getGlobalValue('XML_ENCODING');
	}
	
	public function add_order(Fianet_Core_Model_Fianet_Order_Sac $order)
	{
		$this->orders[$order->info_commande->siteid][] = $order;
		if (!isset($this->sites_configuration[$order->info_commande->siteid]))
		{
			$this->sites_conf[$order->info_commande->siteid]['scope_field'] = $order->scope_field;
			$this->sites_conf[$order->info_commande->siteid]['scope_id'] = (int)$order->scope_id;
		}
	}
	
	public function send()
	{
		$stacks = $this->get_stacks();
		$responses = array();
		foreach ($stacks as $siteid => $stacksof25)
		{
			$url = $this->build_url('SAC_URL_STACKING', $siteid);
			foreach ($stacksof25 as $stack)
			{
				$params = array('siteid' => $siteid, 'controlcallback' => $stack);
				$response = $this->get_zend_http_response($url, $params);
				//Mage::getModel('fianet/log')->log($response->getBody());
				$response = Mage::getModel('fianet/fianet_parser')->process_result_stacking($response->getBody());
				if (is_array($response))
				{
					$responses = array_merge($responses, $response);
				}
			}
		}
		return ($responses);
	}
	
	public function get_reevaluation()
	{
		$reevaluations = array();
		$Valuescollection = Mage::getModel('fianet/configuration_value')->getCollection();
		$sites = array();
		foreach ($Valuescollection as $Value)
		{
			if ($Value->code == 'SAC_SITEID')
			{
				$data['siteid'] = $Value->Value;
				$data['website_id'] = $Value->Website_id;
				$data['group_id'] = $Value->Group_id;
				$data['store_id'] = $Value->Store_id;
				
				$infos = $this->get_siteid_infos($data);
				$sites = array_merge($sites, $infos);
			}
		}
		//
		foreach ($sites as $infos)
		{
			$url = $this->build_url('SAC_URL_GETALERT', $infos['siteid'], $infos['mode']);
			//$infos['siteid'] = 0;
			$params = array(
						'SiteID'	=>	$infos['siteid'],
						'Pwd'		=>	$infos['password'],
						'Mode'		=>	'new',
						'Output'	=>	'new',
						'RepFT'		=>	'0'			
				);
			//Zend_Debug::dump($params);
			$response = $this->get_zend_http_response($url, $params);
			$xml_array = Fianet_Core_Model_Functions::xml2array($response->getBody());
			$reevals = Mage::getModel('fianet/fianet_parser')->process_result_nostack($xml_array);
			$reevaluations = array_merge($reevaluations, $reevals);
		}
		return ($reevaluations);
	}
	
	protected function get_siteid_infos($data)
	{
		$infos = array();
		$scope = '';
		$id = 0;
		if ($data['website_id'] != null)
		{
			$scope = 'website_id';
			$id = $data['website_id'];
		}
		elseif ($data['group_id'] != null)
		{
			$scope = 'group_id';
			$id = $data['group_id'];
		}
		elseif ($data['store_id'] != null)
		{
			$scope = 'store_id';
			$id = $data['store_id'];
		}
		
		$configurationData = Mage::getModel('fianet/configuration_value');
		$configurationData->_scope_field = $scope;
		$configurationData->setScope($id);
		
		$statut = $configurationData->load('SAC_STATUS')->Value;
		if ($statut == '1' || $statut == '2')
		{
			$infos[$data['siteid']]['siteid'] = $data['siteid'];
			$infos[$data['siteid']]['login'] = $configurationData->load('SAC_LOGIN')->Value;
			$infos[$data['siteid']]['password'] = $configurationData->load('SAC_PASSWORD')->Value;
			$infos[$data['siteid']]['mode'] = $statut;
		}
		return ($infos);
	}
	
	public function get_evaluations(array $order_list)
	{
		$evaluations = array();
		foreach ($order_list as $site_id => $infos)
		{
			$order_list_by_stack = array_chunk($infos['orders'], 50, true);
			$info['siteid'] = $site_id;
			$info['mode'] = $infos['mode'];
			$info['login'] = $infos['login'];
			$info['pwd'] = $infos['pwd'];
			foreach ($order_list_by_stack as $stack)
			{
				$evaluations = array_merge($this->get_evaluation_by_stack($stack, $info), $evaluations);
			}
		}
		return ($evaluations);
	}
	/*
	protected function sort_order_by_site($refid_list)
	{
		$order_list = array();
		
		foreach ($refid_list as $refid)
		{
			$infos = $this->get_sac_info($refid);
			$order_list[$infos['siteid']]['mode'] = $infos['mode'];
			$order_list[$infos['siteid']]['login'] = $infos['login'];
			$order_list[$infos['siteid']]['pwd'] = $infos['pwd'];
			$order_list[$infos['siteid']]['refid'][] = $refid;
		}
		
		return ($order_list);
	}*/
	/*
	protected function get_sac_info($refid)
	{
		$infos = array();
		$order = Mage::getModel('sales/order')->loadByIncrementId($refid);
		$scope_field = Mage::getModel('fianet/configuration')->getGlobalValue('CONFIGURATION_SCOPE');
		switch ($scope_field)
		{
			case ('store_id'):
				$id = $order->getStore()->getId();
				break;
			case ('group_id'):
				$id = $order->getStore()->getGroup()->getId();
				break;
			case ('website_id'):
				$id = $order->getStore()->getWebsite()->getId();
				break;
			default:
				$id = $order->getStore()->getGroup()->getId();
				break;
		}
		$configurationData = Mage::getModel('fianet/configuration_value');
		$configurationData->_scope_field = $scope_field;
		$configurationData->setScope($id);
		
		$infos['mode']		= $order->getData('fianet_sac_mode');
		$infos['siteid']	= $configurationData->load('SAC_SITEID')->Value;
		$infos['login']		= $configurationData->load('SAC_LOGIN')->Value;
		$infos['pwd']		= $configurationData->load('SAC_PASSWORD')->Value;
		if ($infos['siteid'] == null)
		{
			$configurationData->setScope(0);
			$infos['siteid']= $configurationData->load('SAC_SITEID')->Value;
			$infos['login'] = $configurationData->load('SAC_LOGIN')->Value;
			$infos['pwd']	= $configurationData->load('SAC_PASSWORD')->Value;
		}
		return ($infos);
	}*/
	
	protected function get_evaluation_by_stack($stack, $info)
	{
		
		$evaluations = array();
		if (count($stack) <= 0)
		{
			return ($evaluations);
		}
		$siteid	= $info['siteid'];
		$mode	= $info['mode'] == 'TEST' ? 1 : 2;
		$login	= $info['login'];
		$pwd	= $info['pwd'];
		
		$listId = '';
		foreach ($stack as $refid)
		{
			if ($listId == '')
			{
				$listId .= $refid;
			}
			else
			{
				$listId .= ',' . $refid;
			}
		}
		
		$url = $this->build_url('SAC_URL_VALIDSTACK', $siteid, $mode);
		$params['SiteID'] = $siteid;
		$params['Pwd'] = $pwd;
		$params['Mode'] = 'mini';
		$params['RepFT'] = '0';
		$params['ListID'] = $listId;
		$params['Separ'] = ',';
		
		$response = $this->get_zend_http_response($url, $params);
		$response = Fianet_Core_Model_Functions::xml2array($response->getBody());
		//Zend_Debug::dump($response);
		$evaluations = Mage::getModel('fianet/fianet_parser')->process_result($response);
		
		
		return ($evaluations);
	}
		
	protected function get_stacks()
	{
		$stacks = array();
		foreach ($this->orders as $siteid => $listorders)
		{
			$listorders = array_chunk($listorders, 25);
			foreach ($listorders as $orders25)
			{
				$stacks[$siteid][] = $this->build_stack_of_25($orders25);
			}
		}
		return ($stacks);
	}
	
	protected function build_stack_of_25($orders)
	{
		$xml = '';
		$xml .= '<?xml version="1.0" encoding="'. $this->encoding . '" ?>' . "\n";
		$xml .= '<stack>' . "\n";
		
		foreach ($orders as $o)
		{
			$xml .= $o->get_xml();
		}
		
		$xml .= '</stack>' . "\n";
		return ($xml);
	}
	
	protected function build_url($name, $siteid, $statut = null)
	{
		if ($statut == null)
		{
			$statut = $this->get_site_statut($siteid);
		}
		$url = null;
		if ($statut == '1')
		{//mode TEST
			$url = Mage::getModel('fianet/configuration')->getGlobalValue('SAC_BASEURL_TEST');
		}
		if ($statut == '2')
		{//mode PRODUCTION
			$url = Mage::getModel('fianet/configuration')->getGlobalValue('SAC_BASEURL_PRODUCTION');
		}
		if ($url != null)
		{
			$url .= Mage::getModel('fianet/configuration')->getGlobalValue($name);
		}
		return ($url);
	}
	
	protected function get_zend_http_response($url, $params = null)
	{
		$response = false;
		try
		{
			$config = array('maxredirects' => 0, 'timeout' => 30);
			
			$client = new Zend_Http_Client($url, $config);
			$client->setMethod(Zend_Http_Client::POST);
			
			if (is_array($params) && count($params) > 0)
			{
				$client->setParameterPost($params);
			}
			$response = $client->request();
		}
		catch (Exception $e)
		{
			Mage::getModel('fianet/log')->log($e->getMessage());
			throw $e;
		}
		return ($response);
	}
	
	protected function get_site_statut($siteid)
	{
		$statut = '0';
		
		$configurationData = Mage::getModel('fianet/configuration_value');
		$configurationData->_scope_field = $this->sites_conf[$siteid]['scope_field'];
		$configurationData->setScope($this->sites_conf[$siteid]['scope_id']);
		
		$statut = $configurationData->load('SAC_STATUS')->Value;
		if ($statut == "" && $this->sites_conf[$siteid]['scope_id'] > 0)
		{
			$configurationData->setScope(0);
			$statut = $configurationData->load('SAC_STATUS')->Value;
		}
		
		return ($statut);
	}
}