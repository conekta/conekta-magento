<?php
$installer = $this;

$installer->startSetup();
//$installer->run("
//ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `cc_auth_code` VARCHAR( 10 ) NOT NULL;

//ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `cc_auth_code` VARCHAR( 10 ) NOT NULL ;

//");
$installer->endSetup();
