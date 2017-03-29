<?php

class Brainup_Cielo_Block_Form_Cc extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('cielo/form/cc.phtml');
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
     * Lista opcoes de parcelamento
     * 
     */
    
    public function getInstallments()
	{
    	// pega dados de parcelamento
    	$maxInstallments = intval(Mage::getStoreConfig('payment/Brainup_Cielo_Cc/max_parcels_number'));
    	$minInstallmentValue = floatval(Mage::getStoreConfig('payment/Brainup_Cielo_Cc/min_parcels_value'));
		
		$minInstallmentValue = ($minInstallmentValue <= 5.01) ? 5.01 : $minInstallmentValue;
		
		// atualiza taxa de juros para 0,
		// caso o usuario tenha voltado na navegacao
		$quote = Mage::getSingleton('checkout/cart')->getQuote();
		$quote->setInterest(0.0);
		$quote->setBaseInterest(0.0);
		
		$quote->setTotalsCollectedFlag(false)->collectTotals();
		$quote->save();
		
		// pega dados de juros
		$withoutInterest = intval(Mage::getStoreConfig('payment/Brainup_Cielo_Cc/installment_without_interest'));
		$interestValue = floatval(Mage::getStoreConfig('payment/Brainup_Cielo_Cc/installment_interest_value'));
		
		// pega valores do pedido
		$total = Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal();
		
		$installments = array();
		
		for($i = 1; $i <= $maxInstallments; $i++)
		{
			// caso nao haja juros na parcela
			if($i <= $withoutInterest)
			{
				$orderTotal = $total;
				$installmentValue = round($orderTotal / $i, 2);
			}
			// caso haja juros
			else
			{
				$installmentValue = round(Mage::helper('Brainup_Cielo')->calcInstallmentValue($total, $interestValue / 100, $i), 2);
				$orderTotal = $i * $installmentValue;
			}
			
			
			
			// confere se a parcela nao estah abaixo do minimo
			if($minInstallmentValue >= 0 && $installmentValue < $minInstallmentValue)
			{
				break;
			}
			
			// monta o texto da parcela
			if($i == 1)
			{
				$label = "à vista (" . Mage::helper('core')->currency(($total), true, false) . ")";
			}
			else
			{
				if($i <= $withoutInterest)
				{
					$label = $i . "x sem juros (" . Mage::helper('core')->currency(($installmentValue), true, false) . " cada)";
				}
				else
				{
					$label = $i . "x (" . Mage::helper('core')->currency(($installmentValue), true, false) . " cada)";
				}
			}
			
			// adiciona no vetor de parcelas
			$installments[] = array("num" => $i, "label" => $this->htmlEscape($label));
		}
		
		// caso o valor da parcela minima seja maior do que o valor da compra,
		// deixa somente opcao a vista
		if($minInstallmentValue > $total)
		{
			$label = "à vista (" . Mage::helper('core')->currency(($total), true, false) . ")";
			$installments[] = array("num" => 1, "label" => $this->htmlEscape($label));
		}
		
		return $installments;
	}
	
	/**
     * 
     * Retorna vetor com os codigos dos cartoes habilitados
     * 
     */
    
    public function getAllowedCards()
	{
    	$allowedCards = explode(",", Mage::getStoreConfig('payment/Brainup_Cielo_Cc/card_types'));
    	$allCards = Mage::getModel('Brainup_Cielo/cc_types')->toOptionArray();
    	
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
     * Retorna vetor com numero maximo de parcelamento aceito
	 * para cada bandeira
     * 
     */
    
    public function getMaxCardsInstallments()
	{
    	$maxInstallments = intval(Mage::getStoreConfig('payment/Brainup_Cielo_Cc/max_parcels_number'));
		$installmentType = Mage::getStoreConfig('payment/Brainup_Cielo_Cc/installments_type');
    	$allCards = Mage::getModel('Brainup_Cielo/cc_types')->toOptionArray();
    	
    	$installmentsArray = array();
    	
    	foreach($allCards as $card)
    	{
			$installmentsNumber = $maxInstallments;
			
			// caso loja
			if($installmentType == '2')
			{
				$installmentsNumber = ($installmentsNumber > $card['inst_s']) ? $card['inst_s'] : $installmentsNumber;
			}
			// caso administradora
			else if($installmentType == '3')
			{
				$installmentsNumber = ($installmentsNumber > $card['inst_a']) ? $card['inst_a'] : $installmentsNumber;
			}
			
			$installmentsArray[$card['value']] = $installmentsNumber;
    	}
    	
    	return $installmentsArray;
	}

	/**
	*	Retorna todos os tokens que o cliente tem na loja
	*
	*/

	public function getCieloTokens()
	{
		// Soh pesquisa por token se a loja permiter tokenize
		if($this->getConfigData('tokenize') && Mage::getSingleton('customer/session')->isLoggedIn())
		{
			$tablePrefix = (string) Mage::getConfig()->getTablePrefix();
			
			if($tablePrefix)
			{
				$tablePrefix = "_" . $tablePrefix;
			}
			
			$readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
			$customerId = Mage::getSingleton('checkout/cart')->getQuote()->getCustomerId();		
			$query = "SELECT token_id as id,cc_type,last_digits FROM " . $tablePrefix . "brainup_cielo_customer_token WHERE customer_id=".$customerId;
			$cardsAllowed = $this->getAllowedCards();
 
 			$tokens = $readConnection->fetchAll($query);

 			for ($i=0; $i < count($tokens); $i++)
			{
 				foreach ($cardsAllowed as $card)
				{
 					if($tokens[$i]['cc_type'] == $card['value'])
					{
 						$tokens[$i]['image'] = $card['image'];

 					}
 				}
 			}
 			
 			return $tokens;
		}
		else
		{
			return false;
		}
	}
	
	/**
     * 
     * Pega os valores da configuracao do modulo
     * 
     */
    
    public function getConfigData($config)
	{
    	return Mage::getStoreConfig('payment/Brainup_Cielo_Cc/' . $config);
	}
}
