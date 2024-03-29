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
class Fianet_Core_Model_Source_ShippingType {

    public function toOptionArray() {
        return array(
            array('value' => 1, 'label' => Mage::helper('fianet')->__('Retrait de la marchandise chez le marchand')),
            array('value' => 2, 'label' => Mage::helper('fianet')->__('Utilisation d\'un réseau de points-retrait tiers')),
            array('value' => 3, 'label' => Mage::helper('fianet')->__('Retrait dans un aéroport, une gare ou une agence de voyage')),
            array('value' => 4, 'label' => Mage::helper('fianet')->__('Transporteur')),
            array('value' => 5, 'label' => Mage::helper('fianet')->__('Emission d\'un billet électronique, téléchargements'))
        );
    }

}