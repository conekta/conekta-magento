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

$connection->addColumn($installer->getTable('sales/quote_payment'), 'spei_expiry_date', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
    'comment' => 'Spei Expiry Date'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'spei_expiry_date', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
    'comment' => 'Spei Expiry Date'
    ));

$connection->addColumn($installer->getTable('sales/quote_payment'), 'spei_clabe', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Spei Barcode URL'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'spei_clabe', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Spei Barcode URL'
    ));
$installer->endSetup();
