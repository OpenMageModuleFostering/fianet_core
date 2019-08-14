<?php

class Fianet_Core_Model_Fianet_Order_Rnp
{
	public $billing_user	= null;
	public $billing_adress	= null;
	public $info_commande	= null;
	public $wallet			= null;
	
	public $delivery_user	= null;
	public $delivery_adress = null;
	protected $version		= null;
	protected $encoding		= null;
	protected $siteId		= null;
			
	public function __construct()
	{
		$store = Mage::getModel('fianet/functions')->getStore();
		$this->billing_user		= Mage::getModel('fianet/fianet_order_user_billing');
		$this->billing_adress	= Mage::getModel('fianet/fianet_order_adress_billing');
		$this->info_commande	= Mage::getModel('fianet/fianet_order_info_rnp');
		$this->wallet			= Mage::getModel('fianet/fianet_order_wallet');
		$this->version			= (string)Mage::getConfig()->getModuleConfig('Fianet_Core')->version;
		$this->encoding			= Mage::getModel('fianet/configuration_global')
									->load('XML_ENCODING')
									->Value;
		$this->siteId			= Mage::getModel('fianet/configuration_value')
									->setScope($store)
									->load('RNP_SITEID')
									->Value;
		if ($this->siteId == null && $store > 0)
		{
			$this->siteId = Mage::getModel('fianet/configuration_value')
				->setScope(0)
				->load('RNP_SITEID')
				->Value;
		}
	}
	
	public function reset()
	{
		$this->billing_user		= Mage::getModel('fianet/fianet_order_user_billing');
		$this->billing_adress	= Mage::getModel('fianet/fianet_order_adress_billing');
		$this->info_commande	= Mage::getModel('fianet/fianet_order_info_rnp');
		$this->wallet			= Mage::getModel('fianet/fianet_order_wallet');
		$this->delivery_user	= null;
		$this->delivery_adress	= null;
	}
	
	public function get_xml()
	{
		$xml = '';
		$xml .= '<?xml version="1.0" encoding="'. $this->encoding . '" ?>' . "\n";
		$xml .= '<control fianetmodule="Magento_RNP" version="'. $this->version .'">' . "\n";
		$xml .= $this->billing_user->get_xml();
		$xml .= $this->billing_adress->get_xml();
		if ($this->delivery_user != null)
		{
			if (Mage::getModel('fianet/functions')->var_is_object_of_class($this->delivery_user, 'Fianet_Core_Model_Fianet_Order_User_Delivery'))
			{
				$xml .= $this->delivery_user->get_xml();
			}
			else
			{
				Mage::getModel('fianet/log')->Log("Mage_Fianet_Model_Fianet_Order_Rnp::get_xml() <br />\nDelivery user is not an object of type Fianet_Core_Model_Fianet_Order_User_Delivery");
			}
		}
		if ($this->delivery_adress != null)
		{
			if (Mage::getModel('fianet/functions')->var_is_object_of_class($this->delivery_adress, 'Fianet_Core_Model_Fianet_Order_Adress_Delivery'))
			{
				$xml .= $this->delivery_adress->get_xml();
			}
			else
			{
				Mage::getModel('fianet/log')->Log("Mage_Fianet_Model_Fianet_Order_Rnp::get_xml() <br />\nDelivery adress is not an object of type Fianet_Core_Model_Fianet_Order_Adress_Delivery");
			}
		}
		$xml .= $this->info_commande->get_xml();
		
		$this->wallet->generate_wallet_crypt_data($this->info_commande->refid, $this->billing_user->nom, $this->billing_user->email, $this->info_commande->montant);
		$xml .= $this->wallet->get_xml();
		
		$xml .= '</control>';
		
		//save_flux_xml($xml, $this->info_commande->refid);
		return ($xml);
	}
	
	/*
	Cette fonction génère le formulaire de redirection vers ReceiveAndPay
	Elle prends des paramètres optionnels
	url_call : url de retour sur le site marchand
	url_sys : url de réponse des tags asynchrone pour le serveur ReceiveAndPay
	ParamCallBack : tableau associatif des données que vous souhaitez voir retourner par le serveur ReceiveAndPay sur url_sys et url_call
	typeIHM : 1 pour carte bancaire seulement, 2 pour à crédit uniquement, 3 pour les deux en même temps
	enProd : mettre à true pour rediriger vers ReceiveAndPay de production
	auto_send : si true : génère un javascript qui soumettra immédiatement le formulaire
	*/
	public function get_formular($url_call = null, $url_sys = null, $ParamCallBack = array(), $typeIHM = 3, $auto_send = true, $mode)
	{
		$flux	= $this->get_xml();
		$flux	= Mage::getModel('fianet/functions')->clean_xml($flux);
		$flux	= str_replace('"', "'", $flux);
		$store	= Mage::getModel('fianet/functions')->getStore();
		
		$cryptage = Mage::getModel('fianet/configuration')->GetGlobalValue('RNP_CRYPTAGE');
		$my_hashmd5 = Mage::getModel('fianet/fianet_'.$cryptage);
		
		//$toto = html_entity_decode($flux);
		//debug($toto);
		$checksum = $my_hashmd5->hash(html_entity_decode($flux));
		
		if (is_array($ParamCallBack) && count($ParamCallBack) > 0)
		{
			$XMLParam = Mage::getModel('fianet/fianet_paramcallback_builder');
			foreach ($ParamCallBack as $index => $value)
			{
				$XMLParam->add_param(Mage::getModel('fianet/fianet_paramcallback_element')->setValues($index, urlencode(htmlentities($value))));
			}
		}

	
		$url = Mage::getModel('fianet/configuration')->getGlobalValue('RNP_BASEURL_'.$mode);
		$url .= Mage::getModel('fianet/configuration')->getGlobalValue('RNP_URL_FRONTLINE');
		
		$form = '';
		$form .= '<form name="rnp_form" id="rnp_form" action="'.$url.'" method="post">';
		$form .= '<input type="hidden" name="MerchID" value="'.$this->siteId .'">' . "\n";
		$form .= '<input type="hidden" name="XMLInfo" value="'. $flux .'">' . "\n";
		if ($url_call != null && $url_call != '')
		{
			$form .= '<input type="hidden" name="URLCall" value="'. $url_call .'">' . "\n";
		}
		if ($url_sys != null && $url_sys != '')
		{
			$form .= '<input type="hidden" name="URLSys" value="'. $url_sys .'">' . "\n";
		}
		if (isset($XMLParam))
		{
			$form .= '<input type="hidden" name="XMLParam" value="'.Mage::getModel('fianet/functions')->clean_xml(str_replace('"', "'", $XMLParam->get_xml())).'">' . "\n";
		}
		$form .= '<input type="hidden" name="CheckSum" value="'. $checksum .'">' . "\n";
		$form .= '<input type="hidden" name="TypeIHM" value="'. $typeIHM .'">' . "\n";
		$form .= '</form>';
		if ($auto_send)
		{
			$form .= '<script>document.rnp_form.submit();</script>';
		}

		return ($form);
	}
	
	public static function GenerateRnPOrder(Mage_Sales_Model_Order $order)
	{
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
		
		$RnPOrder = Mage::getModel('fianet/fianet_order_rnp');
		
		$RnPOrder->scope_field	= $scope_field;
		$RnPOrder->scope_id		= $id;
		
		$billing_address = $order->getBillingAddress();
		$shipping_address = $order->getShippingAddress();
		
		$RnPOrder->billing_user->nom = $billing_address->getLastname();
		$RnPOrder->billing_user->prenom = $billing_address->getFirstname();
		$RnPOrder->billing_user->telhome = eregi_replace("[^0-9]", "", $billing_address->getTelephone());
		$RnPOrder->billing_user->telfax = eregi_replace("[^0-9]", "", $billing_address->getFax());
		$RnPOrder->billing_user->email = $billing_address->getEmail() == '' ? $order->getCustomer_email() : $billing_address->getEmail();
		$RnPOrder->billing_user->societe = $billing_address->getCompany();
		
		if (trim($billing_address->getCompany()) != '')
		{
			$RnPOrder->billing_user->set_quality_professional();
		}
		
		$RnPOrder->billing_adress->rue1 = $billing_address->getStreet(1);
		$RnPOrder->billing_adress->rue2 = $billing_address->getStreet(2);
		$RnPOrder->billing_adress->cpostal = $billing_address->getPostcode();
		$RnPOrder->billing_adress->ville = $billing_address->getCity();
		$RnPOrder->billing_adress->pays = $billing_address->getCountry();
		
		if (!Fianet_Core_Model_Functions::compare_billing_and_shipping($billing_address, $shipping_address))
		{
			$RnPOrder->delivery_user = Mage::getModel('fianet/fianet_order_user_delivery');
			$RnPOrder->delivery_adress = Mage::getModel('fianet/fianet_order_adress_delivery');
			
			$RnPOrder->delivery_user->qualite = $RnPOrder->billing_user->qualite;
			
			$RnPOrder->delivery_user->nom = $shipping_address->getLastname();
			$RnPOrder->delivery_user->prenom = $shipping_address->getFirstname();
			$RnPOrder->delivery_user->telhome = eregi_replace("[^0-9]", "", $shipping_address->getTelephone());
			$RnPOrder->delivery_user->telfax = eregi_replace("[^0-9]", "", $shipping_address->getFax());
			$RnPOrder->delivery_user->email = $shipping_address->getEmail();
			$RnPOrder->delivery_user->societe = $shipping_address->getCompany();
			
			$RnPOrder->delivery_adress->rue1 = $shipping_address->getStreet(1);
			$RnPOrder->delivery_adress->rue2 = $shipping_address->getStreet(2);
			$RnPOrder->delivery_adress->cpostal = $shipping_address->getPostcode();
			$RnPOrder->delivery_adress->ville = $shipping_address->getCity();
			$RnPOrder->delivery_adress->pays = $shipping_address->getCountry();
		}
		
		$RnPOrder->info_commande->refid = $order->getRealOrderId();
		$RnPOrder->info_commande->devise = $order->getBaseCurrencyCode();
		$RnPOrder->info_commande->montant = $order->getBaseGrandTotal();
		$RnPOrder->info_commande->ip = $order->getRemoteIp();
		$RnPOrder->info_commande->timestamp = $order->getCreatedAt();
		
		$RnPOrder->info_commande->siteid = $configurationData->load('RNP_SITEID')->Value;
		if ($RnPOrder->info_commande->siteid == null)
		{
			$configurationData->setScope(0);
			$RnPOrder->info_commande->siteid = $configurationData->load('RNP_SITEID')->Value;
		}
		
		//Zend_Debug::dump(Mage::getModel('fianet/carrier_abstract')->getCode($order->getShippingCarrier()));
		$shipping_code = Mage::getModel('fianet/carrier_abstract')->getCode($order->getShippingCarrier());
		$shipping = Mage::getModel('fianet/shipping_association')->load($shipping_code);
		$RnPOrder->info_commande->transport->type = $shipping->fianet_shipping_type;
		$RnPOrder->info_commande->transport->nom = $shipping->conveyor_name;
		$RnPOrder->info_commande->transport->rapidite = $shipping->delivery_times;
		
		foreach($order->getItemsCollection() as $item)
		{
			$product = Mage::getModel('fianet/fianet_order_info_productList_product');
			
			$product->type = Mage::getModel('fianet/product')->load($item->getProduct_id())->getFianetProductType();
			$product->prixunit = $item->getPrice();
			$product->name = $item->getName();
			$product->nb = (int)$item->getQtyOrdered();
			$product->ref = $item->getProduct_id();
			$RnPOrder->info_commande->list->add_product($product);
		}
		
		$RnPOrder->wallet->datelivr = Mage::getModel('fianet/functions')->get_delivery_date(self::getMaxShippingTimes($order), $configurationData, $order->getRealOrderId());
		return ($RnPOrder);
	}
	
	protected static function getMaxShippingTimes(Mage_Sales_Model_Order $order)
	{
		$times = 0;
		$scope_field = Mage::getModel('fianet/configuration')->getGlobalValue('CONFIGURATION_SCOPE');
		switch ($scope_field)
		{
			case ('store_id'):
				$store_id = $order->getStore()->getId();
				break;
			case ('group_id'):
				$store_id = $order->getStore()->getGroup()->getId();
				break;
			case ('website_id'):
				$store_id = $order->getStore()->getWebsite()->getId();
				break;
			default:
				$store_id = $order->getStore()->getGroup()->getId();
				break;
		}
		//Récupération du délais de livraison de chaque produit
		foreach($order->getAllItems() as $item)
		{
			$productTime = Mage::getModel('receiveandpay/product')
				->setQty($item->getQty())
				->setProductId($item->getProduct_id())
				->setStoreId($store_id)
				->GetShippingTimes();
			if ($productTime > $times)
			{//on conserve le délai de livraison le plus long
				$times = $productTime;
			}
		}
		//Gestion du délais de livraison par transporteur
		$shipping_code = Mage::getModel('fianet/carrier_abstract')->getCode($order->getShippingCarrier());
		$transport_times_configured = Mage::getModel('receiveandpay/shipping_transport_times')
						->setScope($store_id)
						->load($shipping_code);
		$transport_times = $transport_times_configured->getShipping_times() == '' ? 0 : $transport_times_configured->getShipping_times();
		if ($transport_times == 0 && $store_id > 0)
		{
			$transport_times_configured = Mage::getModel('receiveandpay/shipping_transport_times')
						->setScope(0)
						->load($shipping_code);
			$transport_times = $transport_times_configured->getShipping_times() == '' ? 0 : $transport_times_configured->getShipping_times();
		}
		if ($transport_times > $times)
		{//si le délais de livraison transporteur est plus long que le délais de livraison produit, on utilise le délais de livraison transporteur
			$times = $transport_times;
		}
		
		return ($times);
	}
	
}