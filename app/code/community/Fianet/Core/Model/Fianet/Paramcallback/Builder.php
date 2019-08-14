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
class Fianet_Core_Model_Fianet_Paramcallback_Builder {

    protected $_paramList = array();

    public function __construct() {

    }

    public function addParam($param) {
        if ($param instanceof Fianet_Core_Model_Fianet_Paramcallback_Element) {
            $this->_paramList[] = $param;
        } else {
            Mage::getModel('fianet/log')->Log("Erreur : le parametre n'est pas un objet Fianet_Core_Model_Fianet_Paramcallback_Element mais un objet : " . get_class($param));
        }
    }

    public function getXml() {
        $xml = '';
        if (count($this->_paramList) > 0) {
            $xml .= '<?xml version="1.0" encoding="' . Mage::getStoreConfig('kwixo/kwixoconfg/charset', 0) . '" ?><ParamCBack>' . "\n";

            foreach ($this->_paramList as $param) {
                $xml .= $param->getXml();
            }
            $xml .= '</ParamCBack>' . "\n";
        }
        return($xml);
    }

}
