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
class Fianet_Core_Helper_Data extends Mage_Core_Helper_Abstract {

	/**
     * Return Magento version
     *
     * @return int
     */
    public function getMagentoVersion() {
        $version = Mage::getVersion();
        $version = substr($version, 0, 5);
        $version = str_replace('.', '', $version);
        while (strlen($version) < 3) {
            $version .= "0";
        }
        return (int) $version;
    }

    public static function initials($string) {
        $string = trim($string);
        $init = strtoupper($string[0]);
        for ($i = 1; $i < strlen($string); $i++) {
            if ($string[$i - 1] == ' ' && $string[$i] != ' ') {
                $init .= strtoupper($string[$i]);
            }
        }
        return $init;
    }

    public static function checkModuleIsInstalled($name) {
        $modules = array_keys((array) Mage::getConfig()->getNode('modules')->children());
        sort($modules);
        foreach ($modules as $moduleName) {
            if ($moduleName == $name) {
                return (true);
            }
        }
        return (false);
    }

}
