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
class Fianet_Core_Block_Adminhtml_Widget_Grid_Column_Renderer_Fianet extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $html = '';
        if ($row->status == 'pending_rnp') {
            $html = '<img src="' . $this->getSkinUrl('images/fianet/RnP.gif') . '">';
        }
        // if Kwixo is installed
        if (Mage::helper('fianet')->checkModuleIsInstalled('Fianet_Kwixo') && $row->getFianetRnpTag() != null) {
            $html = $this->_renderKwx($row);
        }
        // if ReceiveAndPay is installed
        if (Mage::helper('fianet')->checkModuleIsInstalled('Fianet_ReceiveAndPay') && $row->getFianetRnpTag() != null) {
            $html = $this->_renderRnP($row);
        }
        // if Cestissim is installed
        if (Mage::helper('fianet')->checkModuleIsInstalled('Fianet_Sac') && $row->getFianetSacSent() != 0) {
            $html = $this->_renderSac($row);
        }
        return ($html);
    }

    protected function _renderSac(Varien_Object $row) {
        // utilisable avec le module certissim
        $evaluation = $row->getFianetSacEvaluation();
        $reevaluation = $row->getFianetSacReevaluation();
		$classementID = $row->getFianetSacClassementId();
        $mode = $row->getFianetSacMode();
        $html = '';
        $icon = '';
		$textCoEx = '';
		$iconCoEx = '';
		
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
		switch ($classementID) {
			case('0'):
				$textCoEx = "&agrave; traiter";
				$iconCoEx = "CE_picto_rouge.gif";
				break;
			case('1'):
				$textCoEx = "Contr&ocirc;le visuel &agrave; r&eacute;aliser";
				$iconCoEx = "CE_picto_rouge.gif";
				break;
			case('2'):
				$textCoEx = "Contr&ocirc;le t&eacute;l&eacute;phonique &agrave; r&eacute;aliser";
				$iconCoEx = "CE_picto_rouge.gif";
				break;
			case('3'):
				$textCoEx = "Demande de justificatifs &agrave r&eacute;aliser";
				$iconCoEx = "CE_picto_rouge.gif";
				break;
			case('4'):
				$textCoEx = "Traitement lourd &agrave r&eacute;aliser";
				$iconCoEx = "CE_picto_rouge.gif";
				break;
			case('5'):
				$textCoEx = "Contr&ocirc;le visuel en cours";
				$iconCoEx = "CE_picto_orange.gif";
				break;
			case('6'):
				$textCoEx = "Contr&ocirc;le t&eacute;l&eacute;phonique en cours";
				$iconCoEx = "CE_picto_orange.gif";
				break;
			case('7'):
				$textCoEx = "Demande de justificatifs en cours";
				$iconCoEx = "CE_picto_orange.gif";
				break;
			case('8'):
				$textCoEx = "Traitement lourd en cours";
				$iconCoEx = "CE_picto_orange.gif";
				break;
			case('9'):
				$textCoEx = "FIA-NET : En attente de traitement interne";
				$iconCoEx = "CE_picto_orange.gif";
				break;
			case('10'):
				$textCoEx = "FIA-NET : En attente de retour internaute";
				$iconCoEx = "CE_picto_orange.gif";
				break;
			case('11'):
				$textCoEx = "FIA-NET : En cours de contr&ocirc;le";
				$iconCoEx = "CE_picto_orange.gif";
				break;
			case('12'):
				$textCoEx = "FIA-NET : R&eacute;sultat N&eacute;gatif";
				$iconCoEx = "CE_picto_vert.gif";
				break;
			case('13'):
				$textCoEx = "FIA-NET : R&eacute;sultat Positif";
				$iconCoEx = "CE_picto_vert.gif";
				break;
			case('14'):
				$textCoEx = "FIA-NET : R&eacute;sultat Positif, assurance OK";
				$iconCoEx = "CE_picto_vert.gif";
				break;
			case('15'):
				$textCoEx = "Transaction valid&eacute;e automatiquement";
				$iconCoEx = "CE_picto_vert.gif";
				break;
			case('16'):
				$textCoEx = "Transaction valid&eacute;e manuellement";
				$iconCoEx = "CE_picto_vert.gif";
				break;
			case('17'):
				$textCoEx = "Transaction rejet&eacute;e automatiquement";
				$iconCoEx = "CE_picto_vert.gif";
				break;
			case('18'):
				$textCoEx = "Transaction rejet&eacute;e manuellement";
				$iconCoEx = "CE_picto_vert.gif";
				break;
			case('22'):
			case('23'):
			case('24'):
				$textCoEx = "FIA-NET : CoEx en cours";
				$iconCoEx = "CE_picto_orange.gif";
				break;
			case('25'):
				$textCoEx = "FIA-NET : Transaction valid&eacute;e par CoEx";
				$iconCoEx = "CE_picto_vert.gif";
			case('26'):
				$textCoEx = "FIA-NET : Transaction rejet&eacute;e par CoEx";
				$iconCoEx = "CE_picto_vert.gif";
				break;
		}

        $url = Mage::getStoreConfig('sac/saclink/testurl', '0');
        if ($mode == 'PRODUCTION') {
            $url = Mage::getStoreConfig('sac/saclink/produrl', '0');
        }

        $url .= Mage::getStoreConfig('sac/saclink/interface', '0');
        $order = Mage::getModel('sales/order')->loadByIncrementId($row->IncrementId);
        $id = $order->getStore()->getId();

        $siteId = Mage::getStoreConfig('sac/sacconfg/siteid', $id);
        if ($siteId == null && $id > 0)
            $siteId = Mage::getStoreConfig('sac/sacconfg/siteid', '0');

        $login = Mage::getStoreConfig('sac/sacconfg/compte', $id);
        if ($login == null && $id > 0)
            $login = Mage::getStoreConfig('sac/sacconfg/compte', '0');

        $password = Mage::getStoreConfig('sac/sacconfg/password', $id);
        if ($password == null && $id > 0)
            $password = Mage::getStoreConfig('sac/sacconfg/password', '0');

        $url .= '?sid=' . $siteId . '&log=' . $login . '&pwd=' . urlencode($password) . '&rid=' . $row->getIncrementId();
        $js = 'onclick="javascript: window.open(\'' . $url . '\',\'fianet\',\'toolbar=no, location=no, directories=no, status=no,scrollbars=yes, resizable=yes, copyhistory=no, width=900, height=800, left=200, top=100\'); return false;"';
        $html .= '<p><a href="#" ' . $js . ' style="text-decoration: none;"><img src="' . $this->getSkinUrl('images/fianet/' . $icon) . '" alt="' . $alt . '"></a></p>';
		if($textCoEx != '') {
			$html .= '<p><a href="#" ' . $js . ' style="text-decoration: none;"><img src="' . $this->getSkinUrl('images/fianet/' . $iconCoEx) . '" alt="' . $alt . '"></a><br />';
			$html .= '<font size="1">'.$textCoEx.'</font></p>';
		}
        if ($mode == 'TEST') {
            $html .= 'TEST';
        }
        
		return ($html);
    }

    protected function _getConfigurationData($incrementId) {
        $order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
        $scopeField = Mage::getModel('fianet/configuration')->getGlobalValue('CONFIGURATION_SCOPE');
        switch ($scopeField) {
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
        $configurationData->_scope_field = $scopeField;
        $configurationData->setScope($id);

        return ($configurationData);
    }

    protected function _renderKwx(Varien_Object $row) {
        // utilisable avec le module kwixo
        $html = '';
        $icon = '';

        if ($row->getFianetRnpTag() != null) {
            $rnpTag = $row->getFianetRnpTag();
        } else {
            return ($html);
        }

        $mode = 'TEST';
        if ($row->getFianetRnpMode() != null) {
            $mode = $row->getFianetRnpMode();
        }

        $html = $rnpTag;
        $options = $this->getColumn()->getRenderoptions();

        if (!isset($options['mode'])) {
            $options['mode'] = 'mini';
        }
        switch ($rnpTag) {
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

        //recupere le label du tag
        foreach (Mage::getModel('kwixo/source_tags')->toOptionArray() as $tag) {
            if ($tag['value'] == $rnpTag) {
                $title = $tag['label'];
            }
        }
        $js = "";

        if ($icon != '') {
            if ($icon == 'ss') {
                $html = '<a href="#" ' . $js . ' style="text-decoration: none;" title="' . $title . '">' . $this->__('Und. ctrl.') . '</a>';
                if ($mode == 'TEST' && $options['mode'] == 'mini') {
                    $html .= '<br />TEST';
                }
            } else {
                $html = '<a href="#" ' . $js . ' style="text-decoration: none;"><img src="' . $this->getSkinUrl('images/fianet/' . $icon . '.gif') . '" alt="' . $title . ' (' . $rnpTag . ')"></a>';
                if ($mode == 'TEST' && $options['mode'] == 'mini') {

                    $html .= '<br />TEST';
                }
            }
        } elseif ($options['mode'] == 'full') {
            $html = '<a href="#" ' . $js . ' style="text-decoration: none;">' . $rnpTag . '</a>';
        }
        if ($options['mode'] == 'full') {
            $html .= ' - ' . $title;
        }

        return $html;
    }

    protected function _renderRnP(Varien_Object $row) {
        $html = '';
        $icon = '';

        if ($row->getFianetRnpTag() != null) {
            $tagValue = $row->getFianetRnpTag();
        } else {
            return ($html);
        }

        $mode = 'TEST';
        //Zend_Debug::dump($row->getFianetRnpMode());
        if ($row->getFianetRnpMode() != null) {
            $mode = $row->getFianetRnpMode();
        }
        $html = $tagValue;
        //Zend_Debug::dump($tagValue);
        //$index = $this->getColumn()->getIndex();
        $options = $this->getColumn()->getRenderoptions();
        if (!isset($options['mode'])) {
            $options['mode'] = 'mini';
        }
        switch ($tagValue) {
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
            if ($tag['value'] == $tagValue) {
                $title = $tag['label'];
            }
        }
        $js = "onclick=\"window.open('" . $this->getUrl('receiveandpay/redirect/', array('mode' => $mode)) . "','test'); return false;\"";
        if ($icon != '') {
            if ($icon == 'ss') {

                $html = '<a href="#" ' . $js . ' style="text-decoration: none;" title="' . $title . '">' . $this->__('Und. ctrl.') . '</a>';
                if ($mode == 'TEST' && $options['mode'] == 'mini') {
                    $html .= '<br />TEST';
                }
            } else {
                $html = '<a href="#" ' . $js . ' style="text-decoration: none;"><img src="' . $this->getSkinUrl('images/fianet/' . $icon . '.gif') . '" alt="' . $title . ' (' . $tagValue . ')"></a>';
                if ($mode == 'TEST' && $options['mode'] == 'mini') {
                    $html .= '<br />TEST';
                }
            }
        } elseif ($options['mode'] == 'full') {
            $html = '<a href="#" ' . $js . ' style="text-decoration: none;">' . $tagValue . '</a>';
        }
        if ($options['mode'] == 'full') {
            $html .= ' - ' . $title;
        }
        return $html;
    }

}