<?php
	
class Brainup_Cielo_Model_Observer
{
	public function addButtons($observer)
	{
		$block = $observer->getEvent()->getBlock();
		
		if ($block && 
			$block->getType() == 'adminhtml/sales_order_view' )
		{
			$order = $block->getOrder();
			$payment = $order->getPayment();
			$method = $payment->getMethodInstance()->getCode();
			$tid = $payment->getAdditionalInformation('Cielo_tid');
			$status = $payment->getAdditionalInformation('Cielo_status');
			
			if($method == "Brainup_Cielo_Cc")
			{
				if($status == 4) 																	// somente transacoes autenticadas podem ser capturadas
				{
					$block->addButton('brainup_cielo_capture', array
					(
						'label'     => Mage::helper('Brainup_Cielo')->__('Capture'),
						'onclick'   => "captureCieloOrder('" . $tid . "', " . $order->getId() . ");",
						'class'     => 'go'
					));
				}
			}
			
			if($method == "Brainup_Cielo_Cc" || $method == "Brainup_Cielo_Dc")
			{
				$block->addButton('brainup_cielo_consult', array
				(
					'label'     => Mage::helper('Brainup_Cielo')->__('Consult WebService'),
					'onclick'   => "loadCieloWebServiceData('" . $tid . "', " . $order->getId() . ");",
					'class'     => 'go'
				));
			}
			
			if($method == "Brainup_Cielo_Cc")
			{
				if ($status != 9 && $order->getState() == "canceled") 								// magento cancelado, mas na cielo nao
				{
					$block->addButton('brainup_cielo_cancel', array
					(
						'label'     => Mage::helper('Brainup_Cielo')->__('Cancel on Cielo'),
						'onclick'   => "cancelCieloOrder('" . $tid . "', " . $order->getId() . ");",
						'class'     => 'go'
					));
				}
			}
		}
	}


	/**
	 *
	 * Observador responsavel pelo cancelamento de pedido
	 *
	 **/

	public function cancelOrder($observer)
	{
		$order = $observer->getEvent()->getOrder();
		
		if( !$order 
			|| !$order->getId() 
			|| ($order->getPayment()->getMethodInstance()->getCode() != "Brainup_Cielo_Cc" 
				&& $order->getPayment()->getMethodInstance()->getCode() != "Brainup_Cielo_Dc"))
		{
			return;
		}

		if($order->getPayment()->getMethodInstance()->cancelRequest($order))
		{
			Mage::getSingleton('core/session')->addSuccess('Pedido cancelado com sucesso na Cielo.'); 
		}
		else
		{
			Mage::getSingleton('core/session')->addError('Erro ao tentar pedido na Cielo. Tente novamente de forma manual e se o problema persistir, por favor entre em contato o suporte.'); 
		}
	}
	
	
	/**
	 *
	 * Observador responsavel pela sincronizacao de estoque
	 * de produtos que sejam comprados na loja
	 *
	 **/

	public function createCreditmemo($observer)
	{
		$creditmemo = $observer->getEvent()->getCreditmemo();
		$order = Mage::getModel('sales/order')->load($creditmemo->getOrderId());
		
		if( !$order 
			|| !$order->getId() 
			|| ($order->getPayment()->getMethodInstance()->getCode() != "Brainup_Cielo_Cc" 
				&& $order->getPayment()->getMethodInstance()->getCode() != "Brainup_Cielo_Dc"))
		{
			return;
		}

		if($order->getPayment()->getMethodInstance()->cancelRequest($order, $creditmemo->getGrandTotal()))
		{
			Mage::getSingleton('core/session')->addSuccess('Pedido cancelado com sucesso na Cielo.'); 
		}
		else
		{
			Mage::getSingleton('core/session')->addError('Erro ao tentar pedido na Cielo. Tente novamente de forma manual e se o problema persistir, por favor entre em contato o suporte.'); 
		}
	}
}