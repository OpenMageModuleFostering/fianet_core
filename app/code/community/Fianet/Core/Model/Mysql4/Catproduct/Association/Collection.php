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
class Fianet_Core_Model_Mysql4_Catproduct_association_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    protected function _construct() {
        parent::_construct();
        $this->_init('fianet/catproduct_association');
    }

    public function getConfiguredCategoriesCollection() {
        $collection = $this->load();
        $list = array();
        foreach ($collection as $catproduct) {
            $list[$catproduct->getCatalogCategoryEntityId()] = $catproduct->getFianetProductType();
        }
        return ($list);
    }

}