<?php

	
$installer = $this;
$installer->startSetup();

$tablePrefix = (string) Mage::getConfig()->getTablePrefix();
if($tablePrefix)
{
	$tablePrefix = "_" . $tablePrefix;
}


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

$installer->endSetup();

	