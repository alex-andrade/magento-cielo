	<?php

		
	$installer = $this;
	$installer->startSetup();

	$setup = Mage::getResourceModel('sales/setup', 'default_setup');

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

	