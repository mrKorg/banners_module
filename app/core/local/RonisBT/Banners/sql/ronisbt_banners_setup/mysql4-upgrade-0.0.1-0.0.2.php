<?php

$installer = $this;

$tableBanners = $installer->getTable('ronisbt_banners/table_banners');

$installer->startSetup();

$installer->getConnection()
    ->changeColumn($tableBanners, 'created', 'created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(), 'Creation Time');
$installer->getConnection()
    ->addColumn($tableBanners, 'updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), 'Update Time');

$installer->run("RENAME TABLE {$tableBanners} TO ronisbt_banners;");

$installer->endSetup();
