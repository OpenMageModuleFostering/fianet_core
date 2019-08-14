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
class Fianet_Core_Block_Tree_Abstract extends Mage_Adminhtml_Block_Catalog_Category_Tree {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('fianet/common/categoriestree.phtml');
        $this->setUseAjax(true);
        $this->_withProductCount = false;
    }

    protected function _prepareLayout() {
        return parent::_prepareLayout();
        $addUrl = $this->getUrl("*/*/add", array(
            '_current' => true,
            'id' => null,
            '_query' => false
                ));

        $this->setChild('store_switcher', $this->getLayout()->createBlock('adminhtml/store_switcher')
                        ->setSwitchUrl($this->getUrl('*/*/*', array('_current' => true, '_query' => false, 'store' => null)))
                        ->setTemplate('store/switcher/enhanced.phtml')
        );
    }

    public function getSwitchTreeUrl() {
        return $this->getUrl("*/*/tree", array('_current' => true, 'store' => null, '_query' => false, 'id' => null, 'parent' => null));
        //return $this->getUrl("adminhtml/catalog_category/tree", array('_current'=>true, 'store'=>null, '_query'=>false, 'id'=>null, 'parent'=>null));
    }

    public function getNodesUrl() {
        return $this->getUrl('adminhtml/catalog_category/jsonTree');
    }

    public function getEditUrl() {
        return $this->getUrl('*/*/edit', array('_current' => true, '_query' => false, 'id' => null, 'parent' => null));
    }

    public function buildNodeName($node) {
        $result = $this->htmlEscape($node->getName());
        if ($this->_withProductCount) {
            $result .= ' (' . $node->getProductCount() . ')';
        }
        return $result;
    }

    protected function _getNodeJson($node, $level = 0) {
        $item = array();
        $item['text'] = $this->buildNodeName($node);
        $rootForStores = in_array($node->getEntityId(), $this->getRootIds());

        $item['id'] = $node->getId();
        $item['cls'] = 'folder ' . ($node->getIsActive() ? 'active-category' : 'no-active-category');
        $item['store'] = (int) $this->getStore()->getId();
        $item['path'] = $node->getData('path');
        $item['allowDrop'] = false;
        $item['allowDrag'] = false;
        if ((int) $node->getChildrenCount() > 0) {
            $item['children'] = array();
        }
        $isParent = $this->_isParentSelectedCategory($node);
        if ($node->hasChildren()) {
            $item['children'] = array();
            if (!($this->getUseAjax() && $node->getLevel() > 1 && !$isParent)) {
                foreach ($node->getChildren() as $child) {
                    $item['children'][] = $this->_getNodeJson($child, $level + 1);
                }
            }
        }

        if ($isParent || $node->getLevel() < 2) {
            $item['expanded'] = true;
        }
        return $item;
    }

}