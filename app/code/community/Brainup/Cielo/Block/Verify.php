<?php

class Brainup_Cielo_Block_Verify extends Mage_Checkout_Block_Onepage_Success
{
    private $_cieloStatus = -1;
    private $_cieloTid = -1;
    
    
    /**
	 * 
	 * Define mensagem mostrada ao fim da compra
	 * 
	 * @return string
	 * 
	 */
    
    public function getCieloDataHtml()
    {
		$html = "";
		
		if($this->_cieloStatus == 6 || $this->_cieloStatus == 4)
		{
			$html .= $this->__("Your payment was successfully processed.<br />The TID of your transaction is <b>") . $this->_cieloTid . "</b>.";
		}
		else if($this->_cieloStatus == 1 || $this->_cieloStatus == 2 || $this->_cieloStatus == 10)
		{
			$html .= $this->__("Your payment was successfully processed.<br />The TID of your transaction is <b>") . $this->_cieloTid . "</b>.";
		}
		else
		{
			$statusMsg = Mage::helper('Brainup_Cielo')->getStatusMessage($this->_cieloStatus);
			
			$html .= $this->__("Your payment was not successfully processed.<br /> The TID of your transaction is <b>") . 
					 $this->_cieloTid . 
					 $this->__("</b>.<br />Cielo's return message: <b>") . 
					 $statusMsg . 
					 $this->__("</b>.<br />For most information, please access the order's link above or contact us.");
		}
		
		return $html;
    }
    
    
    
    /**
     * 
     * Getters and Setters
     * 
     */
    
    public function setCieloStatus($st)
    {
		$this->_cieloStatus = $st;
    }
    
    public function getCieloStatus()
    {
		return $this->_cieloStatus;
    }
    
    public function setCieloTid($tid)
    {
		$this->_cieloTid = $tid;
    }
    
    public function getCieloTid()
    {
		return $this->_cieloTid;
    }
    
}
 
