<?php
class Fianet_Core_Model_Fianet_Order_Info_Rnp
{
	public $siteid;
	public $refid;
	public $montant = 0;
	public $devise = "EUR";
	public $transport;
	public $list;
	
	public function __construct()
	{
		$store = Mage::getModel('fianet/functions')->getStore();
		$this->list			= Mage::getModel('fianet/fianet_order_info_productlist');
		$this->transport	= Mage::getModel('fianet/fianet_order_info_transport');
		$this->siteid		= Mage::getModel('fianet/configuration')->getStoreValue('RNP_SITEID', $store);
		if ($this->siteid == null && $store > 0)
		{
			$this->siteid = Mage::getModel('fianet/configuration')->getStoreValue('RNP_SITEID', 0);
		}
	}
	
	public function get_xml()
	{
		$xml = '';
		
		if (trim($this->siteid) == '' || trim($this->refid) == '' || $this->montant <= 0 || trim($this->devise) == '')
		{
			Mage::getModel('fianet/log')->Log("Mage_Fianet_Model_Fianet_Order_Info_Rnp - get_xml() <br />Somes values are undefined\n");
		}
		$xml .= "\t" . '<infocommande>' . "\n";
		$xml .= "\t\t" . '<siteid>'.$this->siteid.'</siteid>' . "\n";
		$xml .= "\t\t" . '<refid>'.$this->refid.'</refid>' . "\n";
		$xml .= "\t\t" . '<montant devise="'.$this->devise.'">'.number_format($this->montant, 2, '.', '').'</montant>' . "\n";
		$xml .= $this->transport->get_xml();
		$xml .= $this->list->get_xml();
		$xml .= "\t" . '</infocommande>' . "\n";
		return ($xml);
	}
}