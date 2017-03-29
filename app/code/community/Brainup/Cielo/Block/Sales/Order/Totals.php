<?php

class Brainup_Cielo_Block_Sales_Order_Totals extends Mage_Sales_Block_Order_Totals
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
					'code'  => 'interest',
					'field' => 'interest',
					'value' => $this->getSource()->getInterest(),
					'label' => $this->__('Interest')
			)), 'grand_total');
		}
        
        return $this;
    }
}
