<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();

$connection = $installer->getConnection();
$connection->addColumn($installer->getTable('sales/quote_payment'), 'bank_expiry_date', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
    'comment' => 'Bank Expiry Date'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'bank_expiry_date', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
    'comment' => 'Bank Expiry Date'
    ));
$installer->endSetup();
