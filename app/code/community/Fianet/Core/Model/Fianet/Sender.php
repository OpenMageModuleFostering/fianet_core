<?php

/**
 * 2000-2012 FIA-NET
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is available
 * through the world-wide-web at this URL: http://www.opensource.org/licenses/OSL-3.0
 * If you are unable to obtain it through the world-wide-web, please contact us
 * via http://www.fia-net-group.com/formulaire.php so we can send you a copy immediately.
 *
 *  @author FIA-NET <support-boutique@fia-net.com>
 *  @copyright 2000-2012 FIA-NET
 *  @version Release: $Revision: 1.0.1 $
 *  @license http://www.opensource.org/licenses/OSL-3.0  Open Software License (OSL 3.0)
 */
class Fianet_Core_Model_Fianet_Sender {

    protected $orders = array();
    protected $encoding = null;
    protected $sites_conf = array();

    public function __construct() {
        $this->encoding = Mage::getStoreConfig('sac/sacconfg/charset', '0');
    }

    public function addOrder(Fianet_Sac_Model_Fianet_Order_Sac $order) {
        $this->orders[$order->info_commande->siteid][] = $order;
        if (!isset($this->sites_configuration[$order->info_commande->siteid])) {
            $this->sites_conf[$order->info_commande->siteid]['scope_field'] = $order->scope_field;
            $this->sites_conf[$order->info_commande->siteid]['scope_id'] = (int) $order->scope_id;
        }
    }

    public function send() {
        $stacks = $this->getStacks();
        $responses = array();
        foreach ($stacks as $siteid => $stacksof25) {
            $url = $this->buildUrl('stacking', $siteid);
            foreach ($stacksof25 as $stack) {
                $params = array('siteid' => $siteid, 'controlcallback' => $stack);
                $response = $this->getResponse($url, $params);
                $response = Mage::getModel('fianet/fianet_parser')->processResultStacking($response->getBody());
                if (is_array($response)) {
                    $responses = array_merge($responses, $response);
                }
            }
        }
        return ($responses);
    }

    public function getReevaluation() {
        $sites = array();
        $websiteCollection = Mage::getModel('core/website')->getResourceCollection();
        foreach ($websiteCollection as $website) {
            $groupCollection = $website->getGroupCollection();
            foreach ($groupCollection as $group) {
                $id = $group->getId();
                $index = count($sites);
                $sites[$index]['siteid'] = Mage::getStoreConfig('sac/sacconfg/siteid', $id);

                if ($sites[$index]['siteid'] == null && $id > 0)
                    $sites[$index]['siteid'] = Mage::getStoreConfig('sac/sacconfg/siteid', '0');

                $sites[$index]['password'] = Mage::getStoreConfig('sac/sacconfg/password', $id);

                if ($sites[$index]['password'] == null && $id > 0)
                    $sites[$index]['password'] = Mage::getStoreConfig('sac/sacconfg/password', '0');

                $sites[$index]['mode'] = Mage::getStoreConfig('sac/sacconfg/mode', $id);

                if ($sites[$index]['mode'] == null && $id > 0)
                    $sites[$index]['mode'] = Mage::getStoreConfig('sac/sacconfg/mode', '0');

                if ($sites[$index]['siteid'] === NULL || $sites[$index]['password'] === NULL) {
                    unset($sites[$index]);
                }
            }
        }
        $reevaluations = array();

        foreach ($sites as $infos) {
            $url = $this->buildUrl('alert', $infos['siteid'], $infos['mode']);
            $params = array(
                'SiteID' => $infos['siteid'],
                'Pwd' => $infos['password'],
                'Mode' => 'new',
                'Output' => 'new',
                'RepFT' => '0'
            );

            $response = $this->getResponse($url, $params);
            $xml_array = Fianet_Core_Model_Functions::xml2array($response->getBody());
            $reevals = Mage::getModel('fianet/fianet_parser')->processResultNostack($xml_array);
            $reevaluations = array_merge($reevaluations, $reevals);
        }

        return ($reevaluations);
    }

    protected function getSiteIdInfos($data) {
        $infos = array();
        $scope = '';
        $id = 0;
        if ($data['website_id'] != null) {
            $scope = 'website_id';
            $id = $data['website_id'];
        } elseif ($data['group_id'] != null) {
            $scope = 'group_id';
            $id = $data['group_id'];
        } elseif ($data['store_id'] != null) {
            $scope = 'store_id';
            $id = $data['store_id'];
        }

        $mode = Mage::getStoreConfig('sac/sacconfg/mode', $id);
        if ($mode == null && $id > 0)
            $mode = Mage::getStoreConfig('sac/sacconfg/mode', '0');

        if ($mode == Fianet_Core_Model_Source_Mode::MODE_TEST || $mode == Fianet_Core_Model_Source_Mode::MODE_PRODUCTION) {
            $infos[$data['siteid']]['siteid'] = $data['siteid'];

            $infos[$data['siteid']]['login'] = Mage::getStoreConfig('sac/sacconfg/compte', $id);
            if ($infos[$data['siteid']]['login'] == null && $id > 0)
                $infos[$data['siteid']]['login'] = Mage::getStoreConfig('sac/sacconfg/compte', '0');

            $infos[$data['siteid']]['password'] = Mage::getStoreConfig('sac/sacconfg/password', $id);
            if ($infos[$data['siteid']]['password'] == null && $id > 0)
                $infos[$data['siteid']]['password'] = Mage::getStoreConfig('sac/sacconfg/password', '0');

            $infos[$data['siteid']]['mode'] = $mode;
        }
        return ($infos);
    }

    public function getEvaluations(array $order_list) {
		
        $evaluations = array();
        foreach ($order_list as $site_id => $infos) {
            $order_list_by_stack = array_chunk($infos['orders'], 50, true);
            $info['siteid'] = $site_id;
            $info['mode'] = $infos['mode']; //TEST or PRODUCTION, voir BD
            $info['login'] = $infos['login'];
            $info['pwd'] = $infos['pwd'];
            foreach ($order_list_by_stack as $stack) {
                $evaluations = array_merge($this->getEvaluationByStack($stack, $info), $evaluations);
            }
        }
		
        return ($evaluations);
    }

    protected function getEvaluationByStack($stack, $info) {
        $evaluations = array();
        if (count($stack) <= 0) {
            return ($evaluations);
        }
        $siteid = $info['siteid'];
        $mode = $info['mode'] == 'TEST' ? 'test' : 'production'; //TEST or PRODUCTION, voir BD
        $login = $info['login'];
        $pwd = $info['pwd'];

        $listId = '';
        foreach ($stack as $refid) {
            if ($listId == '') {
                $listId .= $refid;
            } else {
                $listId .= ',' . $refid;
            }
        }

        $url = $this->buildUrl('validstack', $siteid, $mode);
        $params['SiteID'] = $siteid;
        $params['Pwd'] = $pwd;
        $params['Mode'] = 'mini';
        $params['RepFT'] = '0';
        $params['ListID'] = $listId;
        $params['Separ'] = ',';

        $response = $this->getResponse($url, $params);
        $response = Fianet_Core_Model_Functions::xml2array($response->getBody());
        $evaluations = Mage::getModel('fianet/fianet_parser')->processResult($response);

        return ($evaluations);
    }

    protected function getStacks() {
        $stacks = array();
        foreach ($this->orders as $siteid => $listorders) {
            $listorders = array_chunk($listorders, 25);
            foreach ($listorders as $orders25) {
                $stacks[$siteid][] = $this->buildStackOf25($orders25);
            }
        }
        return ($stacks);
    }

    protected function buildStackOf25($orders) {
        $xml = '';
        $xml .= '<?xml version="1.0" encoding="' . $this->encoding . '" ?>' . "\n";
        $xml .= '<stack>' . "\n";

        foreach ($orders as $o) {
            $xml .= $o->getXml();
        }

        $xml .= '</stack>' . "\n";
        return ($xml);
    }

    protected function buildUrl($name, $siteid, $mode = null) {
        if ($mode == null) {
            $mode = $this->getSiteMode($siteid);
        }

        $url = null;
        if ($mode == Fianet_Core_Model_Source_Mode::MODE_TEST) {
            $url = Mage::getStoreConfig('sac/saclink/testurl', '0');
        }
        if ($mode == Fianet_Core_Model_Source_Mode::MODE_PRODUCTION) {
            $url = Mage::getStoreConfig('sac/saclink/produrl', '0');
        }
        if ($url != null) {
            $url .= Mage::getStoreConfig('sac/saclink/' . $name, '0');
        }

        return ($url);
    }

    protected function getResponse($url, $params = null) {
        $response = false;
        try {
            $config = array('maxredirects' => 0, 'timeout' => 30);

            $client = new Zend_Http_Client($url, $config);
            $client->setMethod(Zend_Http_Client::POST);

            if (is_array($params) && count($params) > 0) {
                $client->setParameterPost($params);
            }
            $response = $client->request();
        } catch (Exception $e) {
            Mage::getModel('fianet/log')->log($e->getMessage());
            throw $e;
        }
        return ($response);
    }

    protected function getSiteMode($siteid) {
        $groupid = $this->sites_conf[$siteid]['scope_id'];

        $mode = Mage::getStoreConfig('sac/sacconfg/mode', $groupid);
        if ($mode == null && $groupid > 0)
            $mode = Mage::getStoreConfig('sac/sacconfg/mode', '0');

        return ($mode);
    }

}