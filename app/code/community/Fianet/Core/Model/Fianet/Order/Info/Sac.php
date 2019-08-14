<?php
class Fianet_Core_Model_Fianet_Order_Info_Sac
{
	public $siteid = "";
	public $refid = "";
	public $montant = 0;
	public $devise = "EUR";
	public $ip;
	public $timestamp;
	public $transport;
	public $list;
	
	public function __construct()
	{
		$this->list			= Mage::getModel('fianet/fianet_order_info_productlist');
		$this->transport	= Mage::getModel('fianet/fianet_order_info_transport');
		$this->siteid		= Mage::getModel('fianet/configuration')->getValue('SAC_SITEID');
	}
	
	
	public function get_xml()
	{
		$xml = '';
		if (trim($this->siteid) == "" && trim($this->refid) == "" && ((integer)$this->montant) <= 0 && trim($this->devise) == "")
		{
			Mage::getModel('fianet/log')->Log("Mage_Fianet_Model_Fianet_Order_Info_Sac - get_xml() <br />Somes values are undefined\n");
		}
		if ($this->transport == null)
		{
			Mage::getModel('fianet/log')->Log("Mage_Fianet_Model_Fianet_Order_Info_Sac - get_xml() <br />Transport is undefined\n");
		}
		if ($this->list == null)
		{
			Mage::getModel('fianet/log')->Log("Mage_Fianet_Model_Fianet_Order_Info_Sac - get_xml() <br />List products is undefined\n");
		}
		$xml .= "\t" . '<infocommande>' . "\n";
		$xml .= "\t\t" . '<siteid>'.$this->siteid.'</siteid>' . "\n";
		$xml .= "\t\t" . '<refid>'.$this->refid.'</refid>' . "\n";
		$xml .= "\t\t" . '<montant devise="'.$this->devise.'">'.number_format($this->montant, 2, '.', '').'</montant>' . "\n";
		if ($this->ip != null && $this->timestamp != null)
		{
			$xml .= "\t\t" . '<ip timestamp="'.$this->timestamp.'">'.$this->ip.'</ip>' . "\n";
		}
		$xml .= $this->transport->get_xml();
		$xml .= $this->list->get_xml();
		$xml .= "\t" . '</infocommande>' . "\n";
		return ($xml);
	}
}