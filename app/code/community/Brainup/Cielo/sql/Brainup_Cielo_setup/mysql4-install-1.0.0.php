<?php

	
$installer = Mage::getResourceModel('sales/setup', 'default_setup');

$installer->startSetup();

$setup = Mage::getResourceModel('sales/setup', 'default_setup');

$tablePrefix = (string) Mage::getConfig()->getTablePrefix();
if($tablePrefix)
{
	$tablePrefix = "_" . $tablePrefix;
}


$installer->addAttribute('order', 'base_interest', array
(
	'label' => 'Base Interest',
	'type'  => 'decimal',
));

$installer->addAttribute('quote', 'interest', array
(
	'label' => 'Interest',
	'type'  => 'decimal',
));

$installer->addAttribute('quote', 'base_interest', array
(
	'label' => 'Base Interest',
	'type'  => 'decimal',
));

$installer->addAttribute('order', 'interest', array
(
	'label' => 'Interest',
	'type'  => 'decimal',
));

$installer->addAttribute('invoice', 'base_interest', array
(
	'label' => 'Base Interest',
	'type'  => 'decimal',
));

$installer->addAttribute('invoice', 'interest', array
(
	'label' => 'Interest',
	'type'  => 'decimal',
));

$installer->addAttribute('creditmemo', 'base_interest', array
(
	'label' => 'Base Interest',
	'type'  => 'decimal',
));

$installer->addAttribute('creditmemo', 'interest', array
(
	'label' => 'Interest',
	'type'  => 'decimal',
));

$installer->run
("
CREATE TABLE IF NOT EXISTS `" . $tablePrefix . "brainup_cielo_customer_token`
(
	`token_id` int(11) NOT NULL AUTO_INCREMENT,
	`customer_id` int(10) unsigned DEFAULT NULL,
	`token` varchar(255) NOT NULL,
	`cc_type` varchar(255) NOT NULL,
	`last_digits` varchar(255) NOT NULL,
	PRIMARY KEY (`token_id`)
)
");


$setup->addAttribute('quote_payment', 'cc_owner_doc', array
(
	'label' => 'Owner Document',
	'type'  => 'varchar'
));

$setup->addAttribute('order_payment', 'cc_owner_doc', array
(
	'label' => 'Owner Document',
	'type'  => 'varchar'
));

$installer->endSetup();

