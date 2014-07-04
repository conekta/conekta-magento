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

$connection->addColumn($installer->getTable('sales/quote_payment'), 'service_name', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'Bank Service Name'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'service_name', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'Bank Service Name'
    ));

$connection->addColumn($installer->getTable('sales/quote_payment'), 'service_number', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Bank Service Number'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'service_number', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Bank Service Number'
    ));

$connection->addColumn($installer->getTable('sales/quote_payment'), 'bank_name', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Bank Name'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'bank_name', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Bank Name'
    ));

$connection->addColumn($installer->getTable('sales/quote_payment'), 'bank_reference', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Bank Reference'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'bank_reference', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Bank Reference'
    ));
$installer->endSetup();
