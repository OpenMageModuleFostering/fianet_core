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
class Fianet_Core_Block_Adminhtml_Tree_ProductType extends Fianet_Core_Block_Adminhtml_Tree_Abstract {

    protected function _getProductTypeLabel($productTypeId) {
        $res = '';
        $types = Mage::getModel('fianet/source_productType')->toOptionArray();
        foreach ($types as $data) {
            if ($data['value'] == $productTypeId) {
                $res = $data['label'];
                break;
            }
        }
        return $res;
    }

    public function buildNodeName($node) {
        $result = $this->htmlEscape($node->getName());

        $productType = Mage::getModel('fianet/catproduct_association')->loadByCategoryId($node->getEntityId());
        if ($productType->getId() > 0) {
            $result .= ' (' . Fianet_Core_Helper_Data::initials($this->_getProductTypeLabel($productType->getFianetProductType())) . ')';
        }

        return $result;
    }

}