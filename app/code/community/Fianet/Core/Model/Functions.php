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
class Fianet_Core_Model_Functions {

    public function cleanXml($xml) {
        $xml = str_replace("\\'", "'", $xml);
        $xml = str_replace("\\\"", "\"", $xml);
        $xml = str_replace("\\\\", "\\", $xml);
        $xml = str_replace("\t", "", $xml);
        $xml = str_replace("\n", "", $xml);
        $xml = str_replace("\r", "", $xml);
        $xml = trim($xml);
        return ($xml);
    }

    public function cleanInvalidChar($var) {
        //supprime les balises html
        $var = strip_tags($var);
        //$var = str_replace("&", "&&amp;", $var);
        $var = str_replace('&', '', $var);
        $var = str_replace("<", "&lt;", $var);
        $var = str_replace(">", "&gt;", $var);
        $var = trim($var);
        return ($var);
    }

    //Calcule la date de livraison en jour ouvré à partir de la date courante
    public function getDeliveryDate($deliveryTimes, $id, $orderIncrementId) {
        define('H', date("H"));
        define('i', date("i"));
        define('s', date("s"));
        define('m', date("m"));
        define('d', date("d"));
        define('Y', date("Y"));
        define('SATURDAY', 6);
        define('SUNDAY', 7);

        $nb_days = 0;
        $j = 0;
        $log = 'Delais de livraison le plus long : ' . $deliveryTimes . " commande #" . $orderIncrementId . " \r\n";

        while ($nb_days < $deliveryTimes) {
            $j++;
            $date = mktime(H, i, s, m, d + $j, Y);
            $day = date("N", $date);
            if ($day != SUNDAY && $day != SATURDAY) {
                $nb_days++;
                $log .= 'J' . $j . '(' . date('Y-m-d', $date) . ') est un jour de semaine(' . date('l', $date) . ').' . "\r\n";
            } else {
                $log .= 'J' . $j . '(' . date('Y-m-d', $date) . ') est un week-end(' . date('l', $date) . ').' . "\r\n";
            }
        }

        $max = Mage::getStoreConfig('kwixo/kwixoconfg/maxdeliverytimes', $id);
        if ($max == null) {
            $max = Mage::getStoreConfig('kwixo/kwixoconfg/maxdeliverytimes', '0');
        }
        $max = intval($max);
        if ($j > $max) {//si on dépasse le délais de livraison max à causes des samedi et dimanche on remet le délais de livraison à son maximum
            $j = $max;
            $log .= $max . ' jours depasses. Date de livraison ramenee a ' . $max . ' jours';
        }

        Mage::getModel('fianet/log')->log($log);
        $date = mktime(H, i, s, m, d + $j, Y);
        return (date("Y-m-d", $date));
    }

    public function getStore() {
        $scope = 'store_id';
        switch ($scope) {
            case('website_id'):
                $store = (int) Mage::app()->getStore(true)->getWebsiteId();
                break;
            case ('group_id'):
                $store = (int) Mage::app()->getStore(true)->getGroupId();
                break;
            case('store_id'):
                $store = (int) Mage::app()->getStore(true)->getStoreId();
                break;
            default :
                $store = 0;
        }
        return ($store);
    }

    public static function xml2array($contents, $getAttributes = 1) {
        if (!$contents)
            return array();

        if (!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }
        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $xmlValues = null;
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $contents, $xmlValues);
        xml_parser_free($parser);

        if (!$xmlValues)
            return; //Hmm...

        //Initializations
        $xmlArray = array();
        $current = &$xmlArray;

        //Go through the tags.
        foreach ($xmlValues as $data) {
            unset($attributes, $value); //Remove existing values, or there will be trouble
            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data); //We could use the array by itself, but this cooler.

            $result = '';
            if ($getAttributes) {//The second argument of the function decides this.
                $result = array();
                if (isset($value))
                    $result['value'] = $value;
                //Set the attributes too.
                if (isset($attributes)) {
                    foreach ($attributes as $attr => $val) {
                        if ($getAttributes == 1)
                            $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                        /**  :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too */
                    }
                }
            } elseif (isset($value)) {
                $result = $value;
            }

            //See tag status and do the needed.
            if ($type == "open") {//The starting of the tag '<tag>'
                $parent[$level - 1] = &$current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    $current = &$current[$tag];
                } else { //There was another element with the same tag name
                    if (isset($current[$tag][0])) {
                        array_push($current[$tag], $result);
                    } else {
                        $current[$tag] = array($current[$tag], $result);
                    }
                    $last = count($current[$tag]) - 1;
                    $current = &$current[$tag][$last];
                }
            } elseif ($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if (!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    //array_push($current[$tag],$result);
                } else { //If taken, put all things inside a list(array)
                    if ((is_array($current[$tag]) and $getAttributes == 0)//If it is already an array...
                            or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $getAttributes == 1)) {
                        array_push($current[$tag], $result); // ...push the new element into that array.
                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag], $result); //...Make it an array using using the existing value and the new value
                    }
                }
            } elseif ($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level - 1];
            }
        }
        return($xmlArray);
    }

    public static function compareBillingAndShipping(Mage_Sales_Model_Order_Address $billing, Mage_Sales_Model_Order_Address $shipping) {
        $identical = true;
        if ($billing->getLastname() != $shipping->getLastname()) {
            $identical = false;
        }
        if ($billing->getFirstname() != $shipping->getFirstname()) {
            $identical = false;
        }
        if ($billing->getTelephone() != $shipping->getTelephone()) {
            $identical = false;
        }
        if ($billing->getFax() != $shipping->getFax()) {
            $identical = false;
        }
        if ($billing->getStreet(1) != $shipping->getStreet(1)) {
            $identical = false;
        }
        if ($billing->getStreet(2) != $shipping->getStreet(2)) {
            $identical = false;
        }
        if ($billing->getPostcode() != $shipping->getPostcode()) {
            $identical = false;
        }
        if ($billing->getCity() != $shipping->getCity()) {
            $identical = false;
        }
        if ($billing->getCountry() != $shipping->getCountry()) {
            $identical = false;
        }
        if ($billing->getCompany() != $shipping->getCompany()) {
            $identical = false;
        }

        return ($identical);
    }

}