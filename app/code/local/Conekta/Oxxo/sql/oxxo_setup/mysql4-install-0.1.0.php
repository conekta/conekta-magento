<?php
$installer = $this;

$installer->startSetup();
$installer->run("
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `codigo` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `codigo_barras` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `referencia` VARCHAR( 255 ) NOT NULL;

ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `codigo` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `codigo_barras` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `referencia` VARCHAR( 255 ) NOT NULL ;

");
$installer->endSetup();
