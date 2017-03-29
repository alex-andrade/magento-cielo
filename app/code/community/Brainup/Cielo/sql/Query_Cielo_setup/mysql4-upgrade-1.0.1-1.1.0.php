<?php

	
$installer = Mage::getResourceModel('sales/setup', 'default_setup');

$installer->startSetup();


// criacao dos campos de juros para as entidades envolvidas na compra

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

$installer->endSetup();

