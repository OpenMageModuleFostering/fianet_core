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
class Fianet_Core_Block_Category_Typeproduct_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form(
                array(
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                )
        );


        $categoryId = (int) $this->getRequest()->getParam('id');
        if ($categoryId <= 0) {
            return parent::_prepareForm();
        }
        $producttype = Mage::getModel('fianet/Catproduct_association')->loadByCategorieId($categoryId);
        $storeId = (int) $this->getRequest()->getParam('store');
        //Zend_Debug::dump($storeId,'$storeId');

        $fieldset = $form->addFieldset('fianet_form', array('legend' => Mage::helper('fianet')->__('Categories configuration')));
        $fieldset->addField('storeId', 'hidden', array(
            'required' => true,
            'name' => 'storeId',
            'value' => $storeId,
        ));
        $fieldset->addField('id', 'hidden', array(
            'required' => true,
            'name' => 'id',
            'value' => $categoryId,
        ));

        $fieldset->addField('typeProduct', 'select', array(
            'label' => Mage::Helper('fianet')->__('Product type'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'typeProduct',
            'value' => $producttype->getFianet_product_type(),
            'values' => Mage::getModel('fianet/source_TypeProduct')->toOptionArray()
        ));
        $fieldset->addField('applysubcat', 'checkbox', array(
            'label' => Mage::Helper('fianet')->__('Apply to sub-categories'),
            'name' => 'applysubcat'
        ));


        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}