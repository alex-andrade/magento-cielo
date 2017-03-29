<?php

class Brainup_Cielo_Model_Adminhtml_Environment
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
            array('value' => 'https://qasecommerce.cielo.com.br', 'label'=>Mage::helper('adminhtml')->__('Teste')),
            array('value' => 'https://ecommerce.cielo.com.br', 'label'=>Mage::helper('adminhtml')->__('Produção')),
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
            'https://qasecommerce.cielo.com.br' => Mage::helper('adminhtml')->__('Teste'),
            'https://ecommerce.cielo.com.br' => Mage::helper('adminhtml')->__('Produção'),
        );
    }

}