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
 *  @author Quadra Informatique <ecommerce@quadra-informatique.fr>
 *  @copyright 2000-2012 FIA-NET
 *  @version Release: $Revision: 0.9.0 $
 *  @license http://www.opensource.org/licenses/OSL-3.0  Open Software License (OSL 3.0)
 */
class Fianet_Core_Block_Widget_Grid_Column_Renderer_Fianet extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $html = '';
        //Zend_Debug::dump($row, '$row');
        if ($row->status == 'pending_rnp') {
            $html = '<img src="' . $this->getSkinUrl('images/fianet/RnP.gif') . '">';
        }
        if ($row->Fianet_rnp_tag != null) {
            $html = $this->_renderRnP($row);
        }
        if ($row->Fianet_sac_sent != 0) {
            $html = $this->_renderSac($row);
        }

        return ($html);
    }

    protected function _renderSac(Varien_Object $row) {
        $evaluation = $row->Fianet_sac_evaluation;
        $reevaluation = $row->Fianet_sac_reevaluation;
        $mode = $row->Fianet_sac_mode;
        $html = '';
        $icon = '';
        switch ($evaluation) {
            case('error'):
                $icon = 'attention.gif';
                $alt = Mage::helper('fianet')->__('Error on transaction');
                break;
            case('100'):
                $icon = 'rond_vert.gif';
                $alt = Mage::helper('fianet')->__('Click for details');
                break;
            case('-1'):
                $icon = 'rond_vertclair.gif';
                $alt = Mage::helper('fianet')->__('Click for details');
                break;
            case('0'):
                $icon = 'rond_rouge.gif';
                $alt = Mage::helper('fianet')->__('Click for details');
                break;
            default:
                $icon = 'fianet_SAC_icon.gif';
                $alt = Mage::helper('fianet')->__('Evaluation in progress...');
                break;
        }
        switch ($reevaluation) {
            case('100'):
                $icon = 'rond_vert.gif';
                $alt = Mage::helper('fianet')->__('Click for details');
                break;
            case('-1'):
                $icon = 'rond_vertclair.gif';
                $alt = Mage::helper('fianet')->__('Click for details');
                break;
            case('0'):
                $icon = 'rond_rouge.gif';
                $alt = Mage::helper('fianet')->__('Click for details');
                break;
        }
        $url = $url = Mage::getModel('fianet/configuration')->getGlobalValue('SAC_BASEURL_TEST');
        if ($mode == 'PRODUCTION') {
            $url = $url = Mage::getModel('fianet/configuration')->getGlobalValue('SAC_BASEURL_PRODUCTION');
        }
        $url .= Mage::getModel('fianet/configuration')->getGlobalValue('SAC_URL_BOMERCHANT');

        $config = $this->GetConfigurationData($row->IncrementId);

        $siteid = $config->load('SAC_SITEID')->Value;
        $login = $config->load('SAC_LOGIN')->Value;
        $password = $config->load('SAC_PASSWORD')->Value;

        if ($siteid == null) {
            $config->setScope(0);
            $siteid = $config->load('SAC_SITEID')->Value;
            $login = $config->load('SAC_LOGIN')->Value;
            $password = $config->load('SAC_PASSWORD')->Value;
        }

        $url .= '?sid=' . $siteid . '&log=' . $login . '&pwd=' . urlencode($password) . '&rid=' . $row->IncrementId;
        $js = 'onclick="javascript: window.open(\'' . $url . '\',\'fianet\',\'toolbar=no, location=no, directories=no, status=no,scrollbars=yes, resizable=yes, copyhistory=no, width=900, height=800, left=200, top=100\'); return false;"';
        $html .= '<a href="#" ' . $js . ' style="text-decoration: none;"><img src="' . $this->getSkinUrl('images/fianet/' . $icon) . '" alt="' . $alt . '"></a>';

        if ($mode == 'TEST') {
            $html .= '<br />TEST';
        }

        return ($html);
    }

    protected function GetConfigurationData($incrementId) {
        //Zend_Debug::dump($incrementId);
        $order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
        $scope_field = Mage::getModel('fianet/configuration')->getGlobalValue('CONFIGURATION_SCOPE');
        switch ($scope_field) {
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

        return ($configurationData);
    }

    protected function _renderRnP(Varien_Object $row) {
        $html = '';
        $icon = '';

        if ($row->Fianet_rnp_tag != null) {
            $Tag = $row->Fianet_rnp_tag;
        } else {
            return ($html);
        }

        $Mode = 'TEST';
        //Zend_Debug::dump($row->Fianet_rnp_mode);
        if ($row->Fianet_rnp_mode != null) {
            $Mode = $row->Fianet_rnp_mode;
        }
        $html = $Tag;
        //Zend_Debug::dump($Tag);
        //$index = $this->getColumn()->getIndex();
        $options = $this->getColumn()->getRenderoptions();
        if (!isset($options['mode'])) {
            $options['mode'] = 'mini';
        }
        switch ($Tag) {
            case '1':
                $icon = 'ok';
                break;
            case '10':
                $icon = 'ok';
                break;
            case '13':
                $icon = 'ok';
                break;
            case '14':
                $icon = 'ok';
                break;
            case '2':
                $icon = 'ko';
                break;
            case '11':
                $icon = 'ko';
                break;
            case '3':
                $icon = 'ss';
                break;
            case '12':
                $icon = 'ss';
                break;
            case "100":
                $icon = 'ok';
                break;
            case "101":
                $icon = 'ko';
                break;
            case "0":
                $icon = 'ko';
                break;
        }
        foreach (Mage::getModel('receiveandpay/source_tags')->toOptionArray() as $tag) {
            if ($tag['value'] == $Tag) {
                $title = $tag['label'];
            }
        }
        $js = "onclick=\"window.open('" . $this->getUrl('receiveandpay/redirect/', array('mode' => $Mode)) . "','test'); return false;\"";
        if ($icon != '') {
            if ($icon == 'ss') {

                $html = '<a href="#" ' . $js . ' style="text-decoration: none;" title="' . $title . '">' . $this->__('Und. ctrl.') . '</a>';
                if ($Mode == 'TEST' && $options['mode'] == 'mini') {
                    $html .= '<br />TEST';
                }
            } else {
                $html = '<a href="#" ' . $js . ' style="text-decoration: none;"><img src="' . $this->getSkinUrl('images/fianet/' . $icon . '.gif') . '" alt="' . $title . ' (' . $Tag . ')"></a>';
                if ($Mode == 'TEST' && $options['mode'] == 'mini') {
                    $html .= '<br />TEST';
                }
            }
        } elseif ($options['mode'] == 'full') {
            $html = '<a href="#" ' . $js . ' style="text-decoration: none;">' . $Tag . '</a>';
        }
        if ($options['mode'] == 'full') {
            $html .= ' - ' . $title;
        }
        return $html;
    }

}