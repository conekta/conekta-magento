<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer->startSetup();
$connection = $installer->getConnection();
$connection->addColumn($installer->getTable('sales/quote_payment'), 'card_bin', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Card Bin Number'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'card_bin', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Card Bin Number'
    ));
$installer->endSetup();