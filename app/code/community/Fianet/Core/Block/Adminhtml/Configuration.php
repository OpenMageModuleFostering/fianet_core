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
class Fianet_Core_Block_Adminhtml_Configuration extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';

        $this->_blockGroup = 'fianet';
        $this->_controller = 'adminhtml_configuration';
        $this->_mode = 'edit';

        $this->_updateButton('save', 'label', $this->__('Save'));
        $storeId = (integer) $this->getRequest()->getParam('store', 0);
        if ($storeId > 0) {
            $deleteButtonData = array(
                'label' => $this->__('Delete'),
                'onclick' => 'deleteConfirm(\'' . Mage::helper('adminhtml')->__('Are you sure you want to do this?') . '\', \'' . $this->getDeleteUrl() . '\')',
                'class' => 'delete',
                'level' => '-1',
            );
            $this->_addButton('delete', $deleteButtonData);
        }
    }

    public function getHeaderText() {
        return Mage::helper('fianet')->__('Manage configuration');
    }

    public function getDeleteUrl() {
        $storeId = $this->getRequest()->getParam('store', 0);
        return $this->getUrl('*/*/delete', array('store' => $storeId));
    }

}