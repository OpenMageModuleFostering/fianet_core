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
class Fianet_Core_Model_Fianet_Paramcallback_Builder {

    protected $param_list = array();

    public function __construct() {

    }

    public function add_param($param) {
        if (Mage::getModel('fianet/functions')->var_is_object_of_class($param, 'Fianet_Core_Model_Fianet_Paramcallback_Element')) {
            $this->param_list[] = $param;
        } else {
            Mage::getModel('fianet/log')->Log("Erreur : le parametre n'est pas un objet Fianet_Core_Model_Fianet_Paramcallback_Element mais un objet : " . get_class($param));
        }
    }

    public function get_xml() {
        $xml = '';

        if (count($this->param_list) > 0) {
            $xml .= '<?xml version="1.0" encoding="' . Mage::getModel('fianet/configuration')->getGlobalValue('XML_ENCODING') . '" ?>
					<ParamCBack>' . "\n";

            foreach ($this->param_list as $param) {
                $xml .= $param->get_xml();
            }

            $xml .= '</ParamCBack>' . "\n";
        }

        return($xml);
    }

}
