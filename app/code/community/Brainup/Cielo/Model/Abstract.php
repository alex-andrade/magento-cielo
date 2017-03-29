<?php

class Brainup_Cielo_Model_Abstract extends Mage_Payment_Model_Method_Abstract
{
	public function consultRequest($order)
	{
		$payment = $order->getPayment();

		// pega os dados para requisicao e realiza a consulta		
		$methodCode 		= $payment->getMethodInstance()->getCode();
		$cieloNumber 		= Mage::getStoreConfig('payment/' . $methodCode . '/cielo_number');
		$cieloKey 			= Mage::getStoreConfig('payment/' . $methodCode . '/cielo_key');
		$environment		= Mage::getStoreConfig('payment/' . $methodCode . '/environment');
		$sslFile			= Mage::getStoreConfig('payment/' . $methodCode . '/ssl_file');
		
		$model = Mage::getModel('Brainup_Cielo/webServiceOrder', array('enderecoBase' => $environment, 'caminhoCertificado' => $sslFile));
		
		if($order->getPayment()->getAdditionalInformation('Cielo_tid'))
		{
			$model->tid = $order->getPayment()->getAdditionalInformation ('Cielo_tid');
			$model->cieloNumber = $cieloNumber;
			$model->cieloKey = $cieloKey;
			
			$model->requestConsultation();
			$xml = $model->getXmlResponse();

			if(isset($xml->status))
			{
				$payment->setAdditionalInformation('Cielo_status', (string) $xml->status);
				$payment->save();
			}
		}
		else 
		{
			$quote = Mage::getModel('sales/quote')->loadByIdWithoutStore($order->getQuoteId());
			
			$model->clientOrderNumber = $quote->getPayment()->getPaymentId();
			$model->cieloNumber = $cieloNumber;
			$model->cieloKey = $cieloKey;
			
			$model->requestConsultationByStoreId();
			$xml = $model->getXmlResponse();
			
			$eci = (isset($xml->autenticacao->eci)) ? ((string) $xml->autenticacao->eci) : "";
			$tid = (string) $xml->tid;
			$type =  (string) $xml->{'forma-pagamento'}->bandeira;
			$parcels = (string) $xml->{'forma-pagamento'}->parcelas;
			$status = (string) $xml->status;
			
			$payment->setAdditionalInformation('Cielo_tid', $tid);
			$payment->setAdditionalInformation('Cielo_cardType', $type);
			$payment->setAdditionalInformation('Cielo_installments', $parcels );
			$payment->setAdditionalInformation('Cielo_eci', $eci);
			$payment->setAdditionalInformation('Cielo_status', $status);
			$payment->save();
		}

		return $xml;
	}


	public function cancelRequest($order, $value = false)
	{
		// pega os dados para requisicao e realiza a consulta
		$methodCode 		= $order->getPayment()->getMethodInstance()->getCode();
		$cieloNumber 		= Mage::getStoreConfig('payment/Brainup_Cielo_Cc/cielo_number');
		$cieloKey 			= Mage::getStoreConfig('payment/Brainup_Cielo_Cc/cielo_key');
		$environment		= Mage::getStoreConfig('payment/' . $methodCode . '/environment');
		$sslFile			= Mage::getStoreConfig('payment/' . $methodCode . '/ssl_file');
		
		$model = Mage::getModel('Brainup_Cielo/webServiceOrder', array('enderecoBase' => $environment, 'caminhoCertificado' => $sslFile));
		
		$model->tid = $order->getPayment()->getAdditionalInformation ('Cielo_tid');
		$model->cieloNumber = $cieloNumber;
		$model->cieloKey = $cieloKey;
		$value = ($value !== false) ? Mage::helper('Brainup_Cielo')->formatValueForCielo($value) : false;

		// requisita cancelamento
		if($model->requestCancellation($value))
		{
			$xml = $model->getXmlResponse();

			// atualiza os dados da compra
			if(isset($xml->status))
			{
				$payment = $order->getPayment();
				$payment->setAdditionalInformation('Cielo_status', (string) $xml->status);
				$payment->save();
			}

			return $xml;
		}
		else
		{
			return false;
		}
	}


	public function captureRequest($order)
	{
		$value = Mage::helper('Brainup_Cielo')->formatValueForCielo($order->getGrandTotal());
		
		// pega os dados para requisicao e realiza a consulta
		$methodCode 		= $order->getPayment()->getMethodInstance()->getCode();
		$cieloNumber 		= Mage::getStoreConfig('payment/Brainup_Cielo_Cc/cielo_number');
		$cieloKey 			= Mage::getStoreConfig('payment/Brainup_Cielo_Cc/cielo_key');
		$environment		= Mage::getStoreConfig('payment/' . $methodCode . '/environment');
		$sslFile			= Mage::getStoreConfig('payment/' . $methodCode . '/ssl_file');
		
		$model = Mage::getModel('Brainup_Cielo/webServiceOrder', array('enderecoBase' => $environment, 'caminhoCertificado' => $sslFile));
		
		$model->tid = $order->getPayment()->getAdditionalInformation ('Cielo_tid');
		$model->cieloNumber = $cieloNumber;
		$model->cieloKey = $cieloKey;
		
		// requisita captura
		$model->requestCapture($value);
		return $model->getXmlResponse();
	}
}