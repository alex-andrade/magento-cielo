<?php


class Brainup_Cielo_Model_Cc_Types
{

    /**
     * Formato vetor de vetores
     *
     * @return array
     */
    public function toOptionArray()
    {
        /**
		 * value: indice
		 * label: descricao
		 * image: nome da imagem
		 * inst_s: numero maximo de parcelas para parcelamento na loja
		 * inst_a: numero maximo de parcelas para parcelamento na administradora
		 */
		
		return array
		(
			array
			(
				'value' 	=> 'visa',
				'label' 	=> Mage::helper('adminhtml')->__('Visa'),
				'image' 	=> 'Visa.png',
				'inst_s' 	=> 12,
				'inst_a' 	=> 1
			),
			array
			(
				'value' 	=> 'mastercard',
				'label' 	=> Mage::helper('adminhtml')->__('Mastercard'),
				'image' 	=> 'Master.png',
				'inst_s' 	=> 12,
				'inst_a' 	=> 1
			),
			array
			(
				'value' 	=> 'diners',
				'label' 	=> Mage::helper('adminhtml')->__('Diners Club'),
				'image' 	=> 'Diners.png',
				'inst_s' 	=> 10,
				'inst_a' 	=> 1
			),
			array
			(
				'value' 	=> 'discover',
				'label' 	=> Mage::helper('adminhtml')->__('Discover'),
				'image' 	=> 'Discover.png',
				'inst_s' 	=> 1,
				'inst_a' 	=> 1
			),
			array
			(
				'value' 	=> 'elo',
				'label' 	=> Mage::helper('adminhtml')->__('Elo'),
				'image' 	=> 'Elo.png',
				'inst_s' 	=> 12,
				'inst_a' 	=> 1
			),
			array
			(
				'value' 	=> 'amex',
				'label' 	=> Mage::helper('adminhtml')->__('American Express'),
				'image' 	=> 'Amex.png',
				'inst_s' 	=> 10,
				'inst_a' 	=> 24
			),
			array
			(
				'value' 	=> 'aura',
				'label' 	=> Mage::helper('adminhtml')->__('Aura'),
				'image' 	=> 'Aura.png',
				'inst_s' 	=> 10,
				'inst_a' 	=> 24
			),
			array
			(
				'value' 	=> 'jcb',
				'label' 	=> Mage::helper('adminhtml')->__('JCB'),
				'image' 	=> 'Jcb.png',
				'inst_s' 	=> 10,
				'inst_a' 	=> 24
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
            'visa' 					=> Mage::helper('adminhtml')->__('Visa'),
            'mastercard' 			=> Mage::helper('adminhtml')->__('Mastercard'),
            'diners' 				=> Mage::helper('adminhtml')->__('Diners Club'),
            'discover' 				=> Mage::helper('adminhtml')->__('Discover'),
            'elo' 					=> Mage::helper('adminhtml')->__('Elo'),
            'amex' 					=> Mage::helper('adminhtml')->__('American Express'),
	   		'aura' 					=> Mage::helper('adminhtml')->__('Aura'),
            'jcb' 					=> Mage::helper('adminhtml')->__('JCB'),
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
            'mastercard',
            'diners',
            'discover',
            'elo',
            'amex',
	 	    'aura',
            'jcb'
        );
    }
}
