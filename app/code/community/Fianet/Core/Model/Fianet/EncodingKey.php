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
class Fianet_Core_Model_Fianet_EncodingKey {

    private $_clMD5;

    function __construct() {
        $cryptage = Mage::getStoreConfig('kwixo/kwixoconfg/encryptmode');
        $this->_clMD5 = Mage::getModel(strtolower('fianet/fianet_' . $cryptage));
    }

    public function giveHashCode($pkey, $second, $email, $refId, $montant, $nom) {
        $modulo = $second % 4;

        switch ($modulo) {
            case 0:
                $select = $montant;
                break;
            case 1:
                $select = $email;
                break;
            case 2:
                $select = $refId;
                break;
            case 3:
                $select = $nom;
                break;
            default:
                break;
        }

        return $this->_clMD5->hash($pkey . $refId . $select);
    }

    public function giveHashCode2($pkey, $second, $email, $refId, $montant, $nom) {
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
                $select = $refId;
                break;
            case 3:
                $select = $nom;
                break;
            default:
                break;
        }

        return $this->_clMD5->hash($pkey . $refId . $montant . $email . $select);
    }

    public function giveHashRemoteControl($pkey, $actionCode, $transactionId, $cmplt) {
        return $this->_clMD5->hash($pkey . $actionCode . $transactionId . $cmplt);
    }

    public function giveHashTagline($pkey, $refId, $transactionId) {
        return $this->_clMD5->hash($pkey . $refId . $transactionId);
    }

}
