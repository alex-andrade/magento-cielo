<?php

class Brainup_Cielo_Model_Adminhtml_BuyPage
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
            array('value' => 'cielo', 	'label'=>Mage::helper('adminhtml')->__('Buy Page Cielo')),
            array('value' => 'loja', 	'label'=>Mage::helper('adminhtml')->__('Buy Page Loja')),
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
            'cielo' 	=> Mage::helper('adminhtml')->__('Buy Page Cielo'),
            'loja' 		=> Mage::helper('adminhtml')->__('Buy Page Loja'),
        );
    }

}