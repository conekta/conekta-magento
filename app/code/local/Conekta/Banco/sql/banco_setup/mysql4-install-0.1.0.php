<?php
$installer = $this;

$installer->startSetup();
$installer->run("
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `numero_servicio` VARCHAR( 25 ) NOT NULL;
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `nombre_servicio` VARCHAR( 25 ) NOT NULL;
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `referencia` VARCHAR( 25 ) NOT NULL;
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `banco` VARCHAR( 25 ) NOT NULL;

ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `numero_servicio` VARCHAR( 25 ) NOT NULL ;
ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `nombre_servicio` VARCHAR( 25 ) NOT NULL ;
ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `referencia` VARCHAR( 25 ) NOT NULL ;
ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `banco` VARCHAR( 25 ) NOT NULL ;

");
$installer->endSetup();
