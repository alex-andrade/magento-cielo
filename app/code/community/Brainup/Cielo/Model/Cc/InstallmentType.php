<?php

class Brainup_Cielo_Model_Cc_InstallmentType
{
	/**
	 * 
	 * Opcoes de tipo de parcelamento
	 * 
	 */
	
	public function toOptionArray()
	{
		$options = array();
        
        $options['2'] = Mage::helper('adminhtml')->__('Loja');
        $options['3'] = Mage::helper('adminhtml')->__('Administradora');
        
		return $options;
	}
}
