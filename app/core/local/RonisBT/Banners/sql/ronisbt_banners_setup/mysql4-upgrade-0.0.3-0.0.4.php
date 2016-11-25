<?php

$installer = $this;

$tableBanners = $installer->getTable('ronisbt_banners/table_banners');

$installer->startSetup();

$installer->getConnection()
    ->addColumn($tableBanners, 'url', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true
    ));


$installer->endSetup();
