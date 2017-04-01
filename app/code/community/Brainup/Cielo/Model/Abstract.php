<?php

use Cielo\API30\Merchant;
use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\Customer;
use Cielo\API30\Ecommerce\Request\CieloRequestException;

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

		if($order->getPayment()->getAdditionalInformation('Cielo_tid'))
		{
            //get payment id
            $paymentId = $order->getPayment()->getAdditionalInformation ('Cielo_tid');

            try {
                //Set enviroment
                $environment = Environment::sandbox();
                if($environment == 'production') Environment::production();

                //create cielo merchant connect
                $merchant = new Merchant($cieloNumber, $cieloKey);

                //consulting sale data
                $sale = (new CieloEcommerce($merchant, $environment))->getSale($paymentId);

            } catch (CieloRequestException $e) {
                throw $e;
            }

            //update payment with cielo data
            if($sale->getPayment()->getStatus())
            {
                $payment->setAdditionalInformation('Cielo_status', (string) $sale->getPayment()->getStatus());
                $payment->save();
            }

		}
		else 
		{
            $quote = Mage::getModel('sales/quote')->loadByIdWithoutStore($order->getQuoteId());
            $paymentId = $quote->getPayment()->getPaymentId();

            try {
                //Set enviroment
                $environment = Environment::sandbox();
                if($environment == 'production') Environment::production();

                //create cielo merchant connect
                $merchant = new Merchant($cieloNumber, $cieloKey);

                //consulting sale data
                $sale = (new CieloEcommerce($merchant, $environment))->getSale($paymentId);

            } catch (CieloRequestException $e) {
                throw $e;
            }

			$eci = (string) $sale->getPayment()->getEci();
			$tid = (string) $sale->getPayment()->getTid();
			$type =  (string) $sale->getPayment()->getType();
			$parcels = (string) $sale->getPayment()->getInstallments();
			$status = (string) $sale->getPayment()->getStatus();
			
			$payment->setAdditionalInformation('Cielo_tid', $tid);
			$payment->setAdditionalInformation('Cielo_cardType', $type);
			$payment->setAdditionalInformation('Cielo_installments', $parcels );
			$payment->setAdditionalInformation('Cielo_eci', $eci);
			$payment->setAdditionalInformation('Cielo_status', $status);
			$payment->save();
		}

		return json_encode($sale);
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