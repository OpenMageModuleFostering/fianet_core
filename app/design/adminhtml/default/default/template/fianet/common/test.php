<?php
try
{
	$form_key = Mage::getSingleton('core/session')->getFormKey();
}
catch(Exception $e)
{
}
$storeid = $this->getRequest()->getParam('store', 0);
?>
<div class="content-header">
    <table cellspacing="0" class="grid-header">
        <tr>
            <td><h3><?php echo $this->__('Product type configuration'); ?></h3></td>
            <td class="a-right">
				<?php if ($this->producttype->getId() > 0) : ?>
				<button  id="id_<?php echo $this->selectedcategorieid ?>" type="button" class="scalable delete" onclick="confirmSetLocation('<?php echo $this->__('Are you sure ?') ?>', '<?php echo $this->getUrl('*/*/delete/', array('id'=>$this->selectedcategorieid, 'store'=>$storeid)); ?>')" style=""><span><?php echo $this->__('Delete') ?></span></button>
                <?php endif; ?>
                <button onclick="editForm.submit()" class="scalable save" type="button"><span><?php echo $this->__('Save'); ?></span></button>
            </td>
        </tr>
    </table>
</div>
<h4 class="icon-head head-edit-form fieldset-legend"><?php echo $this->__('Configuration'); ?></h4>
<div class="entry-edit">
    <form id="edit_form" name="edit_form" method="post" action="<?php echo $this->getUrl('*/*/post'); ?>">
<?php
if (isset($form_key))
{
	echo '<input type="hidden" name="form_key" value="'.$form_key.'" />';
}

?>
        <fieldset id="my-fieldset">
			<input type="hidden" name="store" value="<?php echo $storeid; ?>">
			<input type="hidden" name="id" value="<?php echo $this->selectedcategorieid; ?>">
			<input type="hidden" name="name" value="<?php echo $this->selectedcategoriename; ?>">
			<select name="typeProduct">
					<?php
						$types = Mage::getModel('fianet/source_TypeProduct')->toOptionArray();
						if ($this->producttype->getId() <= 0)
						{
							echo '<optgroup label="'.$this->__('Choose a value').'">';
							echo '<option value="" selected>'.$this->__('Nothing configured') .'</option>';
						}
						foreach ($types as $value)
						{
							$sel = '';
							if ($this->producttype->getId() > 0 && $this->producttype->getFianet_product_type() == $value['value'])
							{
								$sel = ' selected';
							}
							echo '<option value="'.$value['value'].'"'.$sel.'>'.$value['label'].'</option>';
						}
						if ($this->producttype->getId() <= 0)
						{
							echo '</optgroup>';
						}
					?></select>
			<input type="checkbox" name="applysubcat" id="idbox"><label for="idbox"><?php echo $this->__('Apply to sub-categories'); ?></label>
        </fieldset>
    </form>
</div>
<script type="text/javascript">
    var editForm = new varienForm('edit_form');
    //var advForm = new varienForm('advanced_form');
</script>