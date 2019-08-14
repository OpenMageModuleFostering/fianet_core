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
class Fianet_Core_Model_Fianet_EncodingKey {

    private $clMD5;

    function __construct() {
        $cryptage = Mage::getModel('fianet/configuration_global')
                        ->load('RNP_CRYPTAGE')
                ->Value;
        $this->clMD5 = Mage::getModel('fianet/fianet_' . $cryptage);
    }

    public function giveHashCode($pkey, $second, $email, $refid, $montant, $nom) {

        $modulo = $second % 4;

        switch ($modulo) {
            case 0:
                $select = $montant;
                break;
            case 1:
                $select = $email;
                break;
            case 2:
                $select = $refid;
                break;
            case 3:
                $select = $nom;
                break;
            default:
                break;
        }

        return $this->clMD5->hash($pkey . $refid . $select);
    }

    public function giveHashCode2($pkey, $second, $email, $refid, $montant, $nom) {
        $modulo = $second % 4;

        $montant = sprintf("%01.2f", $montant);

        switch ($modulo) {
            case 0:
                $select = $montant;
                break;
            case 1:
                $select = $email;
                break;
            case 2:
                $select = $refid;
                break;
            case 3:
                $select = $nom;
                break;
            default:
                break;
        }

        return $this->clMD5->hash($pkey . $refid . $montant . $email . $select);
    }

}
