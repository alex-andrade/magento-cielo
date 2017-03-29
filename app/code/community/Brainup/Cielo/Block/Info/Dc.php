<?php


class Brainup_Cielo_Block_Info_Dc extends Mage_Payment_Block_Info
{
    /**
     * Init default template for block
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('cielo/info/dc.phtml');
    }
    
    public function toPdf()
    {
        $this->setTemplate('payment/info/pdf/cc.phtml');
        return $this->toHtml();
    }
}
