<?php

class Fianet_Core_Block_Configuration_Content extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$storeId = $this->getRequest()->getParam('store', 0);
		$form = new Varien_Data_Form(array(
					'id' => 'edit_form',
					'action' => $this->getUrl('*/*/save'),
					'method' => 'post',
					'enctype' => 'multipart/form-data'
					)
			);
		//$form->setUseContainer(true);
		$this->setForm($form);
		$fieldset = $form->addFieldset('fianet_form', array('legend'=>Mage::helper('fianet')->__('Normal')));
		$fieldset->addField('storeId', 'hidden', array(
					'class'     => 'required-entry',
					'required'  => true,
					'name'      => 'storeId',
					'value'		=> $storeId,
					));
		//$this->getSkinUrl('images/fianet/RnP.gif');
		foreach ($this->configuration as $conf)
		{
			$fieldset_name = $conf->advanced == '0' ? 'fieldset' : 'fieldset_adv';
			if ($conf->type == $this->type)
			{
				if ($fieldset_name == 'fieldset_adv' && !isset($fieldset_adv))
				{
					$fieldset_adv = $form->addFieldset('fianet_form_adv', array('legend'=>Mage::helper('fianet')->__('Advanced')));
				}
				if ($conf->is_global == '0')
				{
					$note = '['.$this->__(Mage::getModel('fianet/configuration_global')->load('CONFIGURATION_SCOPE')->Value).']';
					$value = Mage::getModel('fianet/configuration_value')
								->setScope($storeId)
								->load($conf->code)
								->getValue();
				}
				else
				{
					$note = '[GLOBAL]';
					$value = Mage::getModel('fianet/configuration_global')->load($conf->code)->Value;
				}
				if (is_null($value))
				{
					$value = $conf->default_value;
				}
				if (!$conf->values)
				{
					
					${$fieldset_name}->addField($conf->code, 'text', array(
							'label'     => $this->__($conf->text),
							'class'     => 'required-entry',
							'required'  => true,
							'note'		=> $note,
							'name'      => $conf->code,
							'value'		=> $value,
							));
				}
				else
				{
					eval('$values = '. $conf->values.';');
					${$fieldset_name}->addField($conf->code, 'select', array(
								'label'     => $this->__($conf->text),
								'class'     => 'required-entry',
								'required'  => true,
								'note'			=> $note,
								'name'      => $conf->code,
								'value'		=> $value,
								'values'	=> $values,
								));
				}
			}
		}
		
		return parent::_prepareForm();
	}
}