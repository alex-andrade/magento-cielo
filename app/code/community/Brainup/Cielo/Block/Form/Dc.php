<?php

class Brainup_Cielo_Block_Form_Dc extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('cielo/form/dc.phtml');
    }
	
	/**
     * 
     * Lista opcoes de meses
     * 
     */
    
    public function getMonths()
	{
    	$months = array();
		
		for($i = 1; $i <= 12; $i++)
		{
			$label = ($i < 10) ? ("0" . $i) : $i;
			
			$months[] = array("num" => $i, "label" => $this->htmlEscape($label));
		}
		
		return $months;
	}
	
	/**
     * 
     * Lista opcoes de anos
     * 
     */
    
    public function getYears()
	{
    	$years = array();
		
		$initYear = (int) date("Y");
		
		for($i = $initYear; $i <= $initYear + 10; $i++)
		{
			$years[] = array("num" => $i, "label" => $i);
		}
		
		return $years;
	}
    
    
	/**
     * 
     * Retorna vetor com os codigos dos cartoes habilitados
     * 
     */
    
    public function getAllowedCards()
	{
    	$allowedCards = explode(",", Mage::getStoreConfig('payment/Brainup_Cielo_Dc/card_types'));
    	$allCards = Mage::getModel('Brainup_Cielo/dc_types')->toOptionArray();
    	
    	$validCards = array();
    	
    	foreach($allCards as $card)
    	{
			if(in_array($card['value'], $allowedCards))
			{
				$validCards[] = $card;
			}
    	}
    	
    	return $validCards;
	}
	
	
	/**
     * 
     * Pega os valores da configuracao do modulo
     * 
     */
    
    public function getConfigData($config)
	{
    	return Mage::getStoreConfig('payment/Brainup_Cielo_Dc/' . $config);
	}
}
