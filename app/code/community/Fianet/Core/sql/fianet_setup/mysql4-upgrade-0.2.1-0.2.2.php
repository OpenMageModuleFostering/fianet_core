<?php
$installer = $this;
$installer->startSetup();
$installer->run("
			alter table `{$this->getTable('fianet_catproduct_association')}` drop foreign key  `{$this->getTable('fianet_catproduct_association')}_ibfk_1`;
");
$installer->endSetup();

?>