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
class Fianet_Core_Model_Fianet_Parser {

    public function processResult($xmlArray) {
        $evaluations = array();
        if (isset($xmlArray['stack']['result'][0])) {
            foreach ($xmlArray['stack']['result'] as $result) {
                if (preg_match("#error#", $result['attr']['retour'])) {
                    Mage::getModel('fianet/log')->log("Parser.php - processResult() - Erreur : " . $result['attr']['message']);
                } else {
                    $index = count($evaluations);
                    $evaluations[$index] = $this->_processResultArray($result);
                }
            }
        } else {
            $res = $xmlArray['stack']['result'];
            if (preg_match("#error#", $res['attr']['retour'])) {
                Mage::getModel('fianet/log')->log("Parser.php - processResult() - Erreur : " . $res['attr']['message']);
            } else {
                $index = count($evaluations);
                $evaluations[$index] = $this->_processResultArray($res);
            }
        }
        return ($evaluations);
    }

    public function processResultStacking($xml_data) {
        $result = array();
        $xmlArray = Fianet_Core_Model_Functions::xml2array($xml_data);
        if (isset($xmlArray['validstack']['unluck'])) {
            Mage::getModel('fianet/log')->log("Parser - processResultStacking() - Error : " . $xmlArray['validstack']['unluck']['value']);
            return ($result);
        } elseif (isset($xmlArray['validstack']['result'])) {
            $xmlArray = $xmlArray['validstack']['result'];
            if (isset($xmlArray[0])) {
                foreach ($xmlArray as $transaction_result) {
                    $index = count($result);
                    $result[$index]['refid'] = $transaction_result['attr']['refid'];
                    $result[$index]['etat'] = $transaction_result['attr']['avancement'];
                    $result[$index]['details'] = $transaction_result['detail']['value'];
                }
            } else {
                $index = count($result);
                $result[$index]['refid'] = $xmlArray['attr']['refid'];
                $result[$index]['etat'] = $xmlArray['attr']['avancement'];
                $result[$index]['details'] = $xmlArray['detail']['value'];
            }
        }
        return ($result);
    }

    public function processResultNostack($xmlArray) {
        $evaluations = array();
        Mage::getModel('fianet/log')->log('Parser->processResultNostack(), return value : ' . $xmlArray['result']['attr']['retour']);
        if ($xmlArray['result']['attr']['retour'] == 'absente') {
            return ($evaluations);
        }
        if ($xmlArray['result']['attr']['retour'] == 'param_error') {
            Mage::getModel('fianet/log')->log('Parser->processResultNostack(), param error : ' . $xmlArray['result']['attr']['message']);
            return ($evaluations);
        }
        if (isset($xmlArray['result']['transaction'][0])) {
            foreach ($xmlArray['result']['transaction'] as $res) {
                $eval = $this->_processTransactionArray($res);
                if ($eval['refid'] != null) {
                    $evaluations[$eval['refid']] = $eval;
                }
            }
        } elseif (isset($xmlArray['result']['transaction'])) {
            $res = $xmlArray['result']['transaction'];
            $eval = $this->_processTransactionArray($res);
            if ($eval['refid'] != null) {
                $evaluations[$eval['refid']] = $eval;
            }
        }

        return ($evaluations);
    }

    private function _processResultArray($res) {		
        $eval = array('refid' => $res['attr']['refid']);
        if ($res['attr']['retour'] == 'absente') {
            $eval['info'] = 'absente';
        } else {
            if (isset($res['transaction'][0])) {
                $index = $this->_searchLastValidTransaction($res['transaction']);
                $transaction = $index >= 0 ? $res['transaction'][$index] : end($res['transaction']);
            } else {
                $transaction = $res['transaction'];
            }
            $etat = $this->_processTransactionArray($transaction);
            $eval['eval'] = $etat['eval'];
            $eval['info'] = $etat['info'];
            $eval['cid'] = $etat['cid'];
			$eval['classementid'] = $etat['classementid'];
        }
        return ($eval);
    }

    private function _searchLastValidTransaction($transactions) {
        $index = -1;
        foreach ($transactions as $i => $transaction) {
            if ($transaction['attr']['avancement'] == 'traitee') {
                $index = $i;
            }
        }
        return ($index);
    }

    private function _processTransactionArray($transaction) {

		$eval = array();

        if ($transaction['attr']['avancement'] == 'error') {
            $eval['refid'] = isset($transaction['attr']['refid']) == true ? $transaction['attr']['refid'] : null;
            $eval['eval'] = 'error';
            $eval['info'] = $transaction['detail']['value'];
            $eval['cid'] = $transaction['attr']['cid'];
			$eval['classementid'] = '';
        }else if ($transaction['attr']['avancement'] == 'encours') {
            $eval['refid'] = isset($transaction['attr']['refid']) == true ? $transaction['attr']['refid'] : null;
            $eval['eval'] = 'encours';
            $eval['info'] = $transaction['detail']['value'];
            $eval['cid'] = '';
			$eval['classementid'] = '';
        } elseif ($transaction['attr']['avancement'] == 'traitee') {
            $eval['refid'] = isset($transaction['attr']['refid']) == true ? $transaction['attr']['refid'] : null;
            $eval['eval'] = $transaction['analyse']['eval']['value'];
            $eval['info'] = $transaction['analyse']['eval']['attr']['info'];
            $eval['cid'] = $transaction['attr']['cid'];
			$eval['classementid'] = $transaction['analyse']['classement']['attr']['id'];
        }

        return ($eval);
    }

}