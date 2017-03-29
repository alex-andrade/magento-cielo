<?php


class Brainup_Cielo_Block_Adminhtml_Order_View extends Mage_Adminhtml_Block_Sales_Order_View
{
    public function  __construct()
    {
		parent::__construct();
		
		$payment = $this->getOrder()->getPayment();
		$method = $payment->getMethodInstance()->getCode();
		$tid = $payment->getAdditionalInformation('Cielo_tid');
		
		if(!$tid)
		{
			return;
		}
		
		if($method == "Brainup_Cielo_Cc")
		{
			if ($this->_isAllowedAction("cielo-capture"))
			{
				$this->_addButton('brainup_cielo_capture', array
				(
					'label'     => Mage::helper('Brainup_Cielo')->__('Capture'),
					'onclick'   => "captureCieloOrder('" . $tid . "', " . $this->getOrder()->getId() . ");",
					'class'     => 'go'
				));
			}
		}
		
		if($method == "Brainup_Cielo_Cc" || $method == "Brainup_Cielo_Dc")
		{
			if ($this->_isAllowedAction("cielo-consult"))
			{
				$this->_addButton('brainup_cielo_consult', array
				(
					'label'     => Mage::helper('Brainup_Cielo')->__('Consult WebService'),
					'onclick'   => "loadCieloWebServiceData('" . $tid . "', " . $this->getOrder()->getId() . ");",
					'class'     => 'go'
				));
			}
		}
		
		if($method == "Brainup_Cielo_Cc")
		{
			if ($this->_isAllowedAction("cielo-cancel"))
			{
				$this->_addButton('brainup_cielo_cancel', array
				(
					'label'     => Mage::helper('Brainup_Cielo')->__('Cancel on Cielo'),
					'onclick'   => "cancelCieloOrder('" . $tid . "', " . $this->getOrder()->getId() . ");",
					'class'     => 'go'
				));
			}
		}
	}
}
