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
?>
<?php

$installer = $this;
/* @var $installer Mage_Protx_Model_Mysql4_Setup */

$installer->startSetup();
$stringthis = '$this';
$installer->run("
    CREATE TABLE IF NOT EXISTS  `{$this->getTable('fianet_catproduct_association')}` (
    `id` int(11) unsigned NOT NULL auto_increment,
    `catalog_category_entity_id` int(11) unsigned NOT NULL,
    `fianet_product_type` int(5) unsigned NOT NULL,
    PRIMARY KEY  (`id`),
    UNIQUE KEY `catalog_category_entity_id` (`catalog_category_entity_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE IF NOT EXISTS `{$this->getTable('fianet_configuration')}` (
    `code` varchar(255) NOT NULL,
    `text` varchar(255) NOT NULL,
    `default_value` varchar(255) default NULL,
    `type` enum('G','S','R') NOT NULL,
    `advanced` enum('0','1') NOT NULL default '1',
    `sort` smallint(5) NOT NULL,
    `values` varchar(255) default NULL,
    `is_global` enum('0','1') NOT NULL default '0',
    PRIMARY KEY  (`code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE IF NOT EXISTS `{$this->getTable('fianet_configuration_values')}` (
    `code` varchar(255) NOT NULL,
    `website_id` smallint(5) unsigned default NULL,
    `group_id` smallint(5) unsigned default NULL,
    `store_id` smallint(5) unsigned default NULL,
    `value` varchar(255) NOT NULL,
    KEY `code` (`code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE IF NOT EXISTS `{$this->getTable('fianet_shipping_association')}` (
    `shipping_code` varchar(255) NOT NULL,
    `fianet_shipping_type` enum('1','2','3','4','5') NOT NULL default '4',
    `delivery_times` enum('1','2') NOT NULL default '2',
    `conveyor_name` varchar(255) NOT NULL,
    PRIMARY KEY  (`shipping_code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE IF NOT EXISTS `{$this->getTable('fianet_log')}` (
    `id` int(11) NOT NULL auto_increment,
    `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
    `message` text NOT NULL,
    PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    INSERT IGNORE INTO `{$this->getTable('fianet_configuration')}` (`code`, `text`, `default_value`, `type`, `advanced`, `sort`, `values`, `is_global`) VALUES
    ('CONFIGURATION_SCOPE', 'Configuration scope', 'group_id', 'G', '1', 2, 'array(\"website_id\"=>Mage::Helper(\"fianet\")->__(\"Website\"), \"group_id\"=>Mage::Helper(\"fianet\")->__(\"Store\"), \"store_id\"=>Mage::Helper(\"fianet\")->__(\"Store view\"))', '1'),
    ('DEFAULT_TYPE_PRODUCT', 'Default type product', '1', 'G', '0', 1, 'Mage::getModel(\"fianet/source_ProductType\")->toOptionArray()', '1'),
    ('XML_ENCODING', 'XML encoding', 'ISO-8859-1', 'G', '0', 0, 'array (\"ISO-8859-1\" => \"ISO-8859-1\", \"UTF-8\" => \"UTF-8\")', '1');

    ALTER TABLE `{$this->getTable('fianet_catproduct_association')}`
    ADD CONSTRAINT `{$this->getTable('fianet_catproduct_association')}_ibfk_1` FOREIGN KEY (`catalog_category_entity_id`) REFERENCES `catalog_category_entity` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE;
");
$installer->endSetup();