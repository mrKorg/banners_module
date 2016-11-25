<?php

/** @var Mage_Core_Model_Resource_Setup $installer */

$installer = $this;
$tableBanners = $installer->getTable('ronisbt_banners/table_banners');

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($tableBanners)
    ->addColumn('banner_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary'  => true,
    ))
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
        'nullable' => false,
    ))
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
        'nullable' => false,
    ))
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default'  => 1
    ))
    ->addColumn('created', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => false,
    ));

$installer->getConnection()->createTable($table);

$installer->endSetup();




