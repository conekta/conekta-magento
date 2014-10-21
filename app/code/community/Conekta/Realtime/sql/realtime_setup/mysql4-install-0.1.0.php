<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();

$connection = $installer->getConnection();

$connection->addColumn($installer->getTable('sales/quote_payment'), 'charge_id', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Charge Id'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'charge_id', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Card Id'
    ));

$connection->addColumn($installer->getTable('sales/quote_payment'), 'realtime_expiry_date', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
    'comment' => 'Realtime Expiry Date'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'realtime_expiry_date', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
    'comment' => 'Realtime Expiry Date'
    ));

$connection->addColumn($installer->getTable('sales/quote_payment'), 'realtime_barcode_url', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Realtime Barcode URL'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'realtime_barcode_url', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Realtime Barcode URL'
    ));
$connection->addColumn($installer->getTable('sales/quote_payment'), 'realtime_barcode', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Realtime Reference'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'realtime_barcode', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Realtime Reference'
    ));

$connection->addColumn($installer->getTable('sales/quote_payment'), 'realtime_store_name', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Realtime Store Name'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'realtime_store_name', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Realtime Store Name'
    ));
$installer->endSetup();
