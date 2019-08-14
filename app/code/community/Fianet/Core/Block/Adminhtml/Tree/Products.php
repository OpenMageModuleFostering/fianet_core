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
class Fianet_Core_Block_Adminhtml_Tree_Products extends Fianet_Core_Block_Adminhtml_Tree_Abstract {

    public function __construct() {
        parent::__construct();
        $this->_withProductCount = true;
    }

    protected function _getProductTypeLabel($productTypeId) {
        $types = Mage::getModel('fianet/source_productType')->toOptionArray();
        foreach ($types as $data) {
            if ($data['value'] == $productTypeId) {
                return ($data['label']);
            }
        }
        return '';
    }

    public function buildNodeName($node) {
        $result = $this->htmlEscape($node->getName());
        if ($this->_withProductCount) {
            $result .= ' (' . $node->getProductCount() . ')';
        }
        return $result;
    }

}