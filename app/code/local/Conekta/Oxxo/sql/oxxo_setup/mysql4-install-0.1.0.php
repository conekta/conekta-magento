<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'charge_id', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Charge Id'
));
$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'charge_id', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Card Id'
));

$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'oxxo_expiry_date', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
    'comment' => 'Oxxo Expiry Date'
));
$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'oxxo_expiry_date', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
    'comment' => 'Oxxo Expiry Date'
));

$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'oxxo_barcode_url', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Oxxo Barcode URL'
));
$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'oxxo_barcode_url', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Oxxo Barcode URL'
));
$installer->endSetup();
