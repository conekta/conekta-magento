<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();

$connection = $installer->getConnection();
$connection->addColumn($installer->getTable('sales/order_payment'), 'spei_bank', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Spei Bank'
    ));

$connection->dropColumn($installer->getTable('sales/order_payment'), 'oxxo_bank');
$connection->dropColumn($installer->getTable('sales/quote_payment'), 'spei_expiry_date');
$connection->dropColumn($installer->getTable('sales/order_payment'), 'spei_expiry_date');


$installer->endSetup();
