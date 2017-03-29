<?php

class Brainup_Cielo_Model_Dc_Types
{

    /**
     * Formato vetor de vetores
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array
	(
			array
			(
				'value' 	=> 'visa-electron',
				'label' 	=> Mage::helper('adminhtml')->__('Visa Electron'),
				'image' 	=> 'Visa-Electron.png'
			),   
	               array
	                (
                	        'value'     => 'mastercard-maestro',
		                'label'     => Mage::helper('adminhtml')->__('Mastercard Maestro'),
		                'image'     => 'Master-maestro.png'
	                )
        );
    }

    /**
     * Formato chave-valor
     *
     * @return array
     */
    public function toArray()
    {
        return array		
	(
            'visa' 	=> Mage::helper('adminhtml')->__('Visa Electron'),
	    'mastercard'  => Mage::helper('adminhtml')->__('Mastercard Maestro')
        );
    }
	
	/**
     * Formato chave
     *
     * @return array
     */
    public function getCodes()
    {
        return array
	(
            'visa',
            'mastercard'
        );
    }
}
