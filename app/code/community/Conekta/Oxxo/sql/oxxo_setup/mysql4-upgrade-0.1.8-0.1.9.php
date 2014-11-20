<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();

$connection = $installer->getConnection();
$connection->addColumn($installer->getTable('sales/quote_payment'), 'oxxo_barcode', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Oxxo Reference'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'oxxo_barcode', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Oxxo Reference'
    ));
$installer->endSetup();
