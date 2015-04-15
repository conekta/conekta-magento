<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer->startSetup();
$connection = $installer->getConnection();
$connection->addColumn($installer->getTable('sales/quote_payment'), 'card_monthly_installments', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Monthly Installments'
    ));
$connection->addColumn($installer->getTable('sales/order_payment'), 'card_monthly_installments', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => '255',
    'comment' => 'Monthly Installments'
    ));
$installer->endSetup();