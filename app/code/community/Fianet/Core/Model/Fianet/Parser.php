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
class Fianet_Core_Model_Fianet_Parser {

    public function process_result($xml_array) {
        $evaluations = array();
        //Zend_Debug::dump($xml_array, 'xml_array');
        if (isset($xml_array['stack']['result'][0])) {
            foreach ($xml_array['stack']['result'] as $result) {
                if (preg_match("#error#", $result['attr']['retour'])) {
                    Mage::getModel('fianet/log')->Log("Parser.php - process_result() - Erreur : " . $result['attr']['message']);
                } else {
                    $index = count($evaluations);
                    $evaluations[$index] = $this->process_result_array($result);
                }
            }
        } else {
            $res = $xml_array['stack']['result'];
            if (preg_match("#error#", $res['attr']['retour'])) {
                Mage::getModel('fianet/log')->Log("Parser.php - process_result() - Erreur : " . $res['attr']['message']);
            } else {
                $index = count($evaluations);
                $evaluations[$index] = $this->process_result_array($res);
            }
        }
        //Zend_Debug::dump($evaluations);
        return ($evaluations);
    }

    public function process_result_stacking($xml_data) {
        $result = array();
        $xml_array = Fianet_Core_Model_Functions::xml2array($xml_data);
        //debug($xml_array);
        if (isset($xml_array['validstack']['unluck'])) {
            Mage::getModel('fianet/log')->Log("Parser - process_result_stacking() - Error : " . $xml_array['validstack']['unluck']['value']);
            return ($result);
        } elseif (isset($xml_array['validstack']['result'])) {
            $xml_array = $xml_array['validstack']['result'];
            //debug($xml_array);
            if (isset($xml_array[0])) {
                foreach ($xml_array as $transaction_result) {
                    $index = count($result);
                    $result[$index]['refid'] = $transaction_result['attr']['refid'];
                    $result[$index]['etat'] = $transaction_result['attr']['avancement'];
                    $result[$index]['details'] = $transaction_result['detail']['value'];
                }
            } else {
                $index = count($result);
                $result[$index]['refid'] = $xml_array['attr']['refid'];
                $result[$index]['etat'] = $xml_array['attr']['avancement'];
                $result[$index]['details'] = $xml_array['detail']['value'];
            }
        }
        return ($result);
    }

    public function process_result_nostack($xml_array) {
        $evaluations = array();
        //Zend_Debug::dump($xml_array, 'xml_array');
        Mage::getModel('fianet/log')->log('Parser->process_result_nostack(), return value : ' . $xml_array['result']['attr']['retour']);
        if ($xml_array['result']['attr']['retour'] == 'absente') {
            return ($evaluations);
        }
        if ($xml_array['result']['attr']['retour'] == 'param_error') {
            Mage::getModel('fianet/log')->log('Parser->process_result_nostack(), param error : ' . $xml_array['result']['attr']['message']);
            return ($evaluations);
        }
        if (isset($xml_array['result']['transaction'][0])) {
            foreach ($xml_array['result']['transaction'] as $res) {
                $eval = $this->process_transaction_array($res);
                if ($eval['refid'] != null) {
                    $evaluations[$eval['refid']] = $eval;
                }
            }
        } elseif (isset($xml_array['result']['transaction'])) {
            $res = $xml_array['result']['transaction'];
            $eval = $this->process_transaction_array($res);
            if ($eval['refid'] != null) {
                $evaluations[$eval['refid']] = $eval;
            }
        }

        return ($evaluations);
    }

    private function process_result_array($res) {
        //Zend_Debug::dump($res, 'Result');
        $eval['refid'] = $res['attr']['refid'];
        if ($res['attr']['retour'] == 'absente') {
            $eval['info'] = 'absente';
        } else {
            if (isset($res['transaction'][0])) {
                $index = $this->search_last_valid_transaction($res['transaction']);
                $transaction = $index >= 0 ? $res['transaction'][$index] : end($res['transaction']);
            } else {
                $transaction = $res['transaction'];
            }
            $etat = $this->process_transaction_array($transaction);
            $eval['eval'] = $etat['eval'];
            $eval['info'] = $etat['info'];
            $eval['cid'] = $etat['cid'];
        }
        //Zend_Debug::dump($eval);
        return ($eval);
    }

    private function search_last_valid_transaction($transactions) {
        $index = -1;
        foreach ($transactions as $i => $transaction) {
            if ($transaction['attr']['avancement'] == 'traitee') {
                $index = $i;
            }
        }
        return ($index);
    }

    private function process_transaction_array($transaction) {
        $eval = array();
        //Zend_Debug::dump($transaction);

        if ($transaction['attr']['avancement'] == 'error') {
            $eval['refid'] = isset($transaction['attr']['refid']) == true ? $transaction['attr']['refid'] : null;
            $eval['eval'] = 'error';
            $eval['info'] = $transaction['detail']['value'];
            $eval['cid'] = $transaction['attr']['cid'];
        }
        if ($transaction['attr']['avancement'] == 'encours') {
            $eval['refid'] = isset($transaction['attr']['refid']) == true ? $transaction['attr']['refid'] : null;
            $eval['eval'] = 'encours';
            $eval['info'] = $transaction['detail']['value'];
            $eval['cid'] = '';
        } elseif ($transaction['attr']['avancement'] == 'traitee') {
            $eval['refid'] = isset($transaction['attr']['refid']) == true ? $transaction['attr']['refid'] : null;
            $eval['eval'] = $transaction['analyse']['eval']['value'];
            $eval['info'] = $transaction['analyse']['eval']['attr']['info'];
            $eval['cid'] = $transaction['attr']['cid'];
        }

        return ($eval);
    }

}