<?php

class Brainup_Cielo_AdminController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * 
	 * Funcao responsavel por consultar o status de uma transacao no WebService da 
	 * Cielo
	 * 
	 */

	public function consultAction()
	{
		// verifica se o usuario estah logado na administracao do magento
		Mage::getSingleton('core/session', array('name' => 'adminhtml'));
		$session = Mage::getSingleton('admin/session');
		
		if (!$session->isLoggedIn())
		{
			return;
		}
		
		// pega pedido correspondente
		$orderId = $this->getRequest()->getParam('order');
		$order = Mage::getModel('sales/order')->load($orderId);
		
		$xml = $order->getPayment()->getMethodInstance()->consultRequest($order);
		
		if(isset($xml->status))
		{
			$html = "<b>" . Mage::helper('Brainup_Cielo')->__("Order Status has been successfully updated") . "</b> &nbsp; &nbsp;
					<button type=\"button\" title=\" " . Mage::helper('Brainup_Cielo')->__("Update Information") . "\" onclick=\"document.location.reload(true)\">
					<span>" . Mage::helper('Brainup_Cielo')->__("Reload Page") . "</span>
					</button><br /><br />";
		}
		else
		{
			$html = "";
		}
		
		$this->getResponse()->setBody($html . Mage::helper('Brainup_Cielo')->xmlToHtml($xml));		
	}
	
	
	/**
	 * 
	 * Funcao responsavel por enviar o pedido de captura para o WebService da Cielo
	 * 
	 */

	public function captureAction()
	{
		// verifica se o usuario estah logado na administracao do magento
		Mage::getSingleton('core/session', array('name' => 'adminhtml'));
		$session = Mage::getSingleton('admin/session');
		
		if (!$session->isLoggedIn())
		{
			return;
		}
		
		// pega pedido correspondente
		$orderId = $this->getRequest()->getParam('order');
		$order = Mage::getModel('sales/order')->load($orderId);
		
		$xml = $order->getPayment()->getMethodInstance()->captureRequest($order);
		$status = (string) $xml->status;
		
		// tudo ok, transacao aprovada, cria fatura
		if($status == 6)
		{	
			$html = "<b>" . Mage::helper('Brainup_Cielo')->__("Order captured with success") . "</b> &nbsp; &nbsp;
					<button type=\"button\" title=\" " . Mage::helper('Brainup_Cielo')->__("Update Information") . "\" onclick=\"document.location.reload(true)\">
					<span>" . Mage::helper('Brainup_Cielo')->__("Reload Page") . "</span>
					</button><br /><br />";

			// atualiza os dados da compra
			$payment = $order->getPayment();
			$payment->setAdditionalInformation('Cielo_status', $status);
			$payment->save();
			
			if($order->canInvoice() && !$order->hasInvoices())
			{
				$invoiceId = Mage::getModel('sales/order_invoice_api')->create($order->getIncrementId(), array());
				$invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($invoiceId);
				
				// envia email de confirmacao de fatura
				$invoice->sendEmail(true);
				$invoice->setEmailSent(true);
				$invoice->save();
			}
		}
		else
		{
			$html = "";
		}
		
		$this->getResponse()->setBody($html . Mage::helper('Brainup_Cielo')->xmlToHtml($xml));
	}
	
	
	/**
	 * 
	 * Funcao responsavel por enviar o pedido de cancelamento para o WebService da Cielo
	 * 
	 */

	public function cancelAction()
	{
		// verifica se o usuario estah logado na administracao do magento
		Mage::getSingleton('core/session', array('name' => 'adminhtml'));
		$session = Mage::getSingleton('admin/session');
		
		if (!$session->isLoggedIn())
		{
			return;
		}
		
		// pega pedido correspondente
		$orderId = $this->getRequest()->getParam('order');
		$order = Mage::getModel('sales/order')->load($orderId);
		
		$xml = $order->getPayment()->getMethodInstance()->cancelRequest($order);
		$status = (string) $xml->status;
		
		// tudo ok, transacao cancelada
		if($status == 9)
		{
			$html = "<b>" . Mage::helper('Brainup_Cielo')->__("Order cancelled with success") . "</b> &nbsp; &nbsp;
					<button type=\"button\" title=\" " . Mage::helper('Brainup_Cielo')->__("Update Information") . "\" onclick=\"document.location.reload(true)\">
					<span>" . Mage::helper('Brainup_Cielo')->__("Reload Page") . "</span>
					</button><br /><br />";
		}
		else
		{
			$html = "";
		}
		
		$this->getResponse()->setBody($html . Mage::helper('Brainup_Cielo')->xmlToHtml($xml));
	}
	
	
	/**
	 * 
	 * Funcao responsavel por conferir se usuario pode realizar a acao
	 * 
	 */
	
	protected function _isAllowed()
	{
		$action = 'sales/order/actions/cielo-' . $this->getRequest()->getActionName();
		
		return Mage::getSingleton('admin/session')->isAllowed($action);
	}
} 
