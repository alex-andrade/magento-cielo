<?php

class Brainup_Cielo_Block_Adminhtml_Order_Invoice_Totals extends Mage_Adminhtml_Block_Sales_Order_Invoice_Totals
{
    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals()
    {
        parent::_initTotals();
		
		$source = $this->getSource();
		
		if($this->getSource()->getInterest() > 0)
		{
			$this->addTotalBefore(new Varien_Object(array
			(
					'code'  		=> 'interest',
					'strong'    	=> true,
					'value' 		=> $this->getSource()->getInterest(),
					'base_value' 	=> $this->getSource()->getBaseInterest(),
					'label' 		=> $this->__('Interest'),
					'area'      	=> 'footer'
			)), 'grand_total');
		}
		
		return $this;
    }
}
