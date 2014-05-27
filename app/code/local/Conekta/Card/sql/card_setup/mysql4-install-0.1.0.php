<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'charge_id', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255'
));
$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'charge_id', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255'
));

$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'charge_authorization', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255'
));
$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'charge_authorization', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255'
));

$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'card_token', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255'
));
$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'card_token', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255'
));
$installer->endSetup();
