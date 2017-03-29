<?php

class Brainup_Cielo_Block_Info_Cc extends Mage_Payment_Block_Info
{
    /**
     * Init default template for block
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('cielo/info/cc.phtml');
    }
    
    public function toPdf()
    {
        $this->setTemplate('payment/info/pdf/cc.phtml');
        return $this->toHtml();
    }

    public function getAuthorizationCodeLabel($authorizationCode)
    {
        switch($authorizationCode)
        {
            case "00":
                $label = "Authorized";
                break;
            case "01":
                $label = "Please contact the card issuer";
                break;
            case "04":
                $label = "Card with restrictions: please contact the card issuer";
                break;
            case "05":
                $label = "Unauthorized";
                break;
            case "06":
                $label = "Please try again";
                break;
            case "07":
                $label = "Card with restrictions: please contact the card issuer";
                break;
            case "12":
                $label = "Invalid transaction";
                break;
            case "13":
                $label = "Invalid value";
                break;
            case "14":
                $label = "Invalid card";
                break;
            case "15":
                $label = "Invalid issuer";
                break;
            case "41":
                $label = "Card with restrictions: please contact the card issuer";
                break;
            case "51":
                $label = "Insufficient funds";
                break;
            case "54":
                $label = "Expired card";
                break;
            case "57":
                $label = "Transaction not permitted";
                break;
            case "58":
                $label = "Transaction not permitted";
                break;
            case "62":
                $label = "Card with restrictions: please contact the card issuer";
                break;
            case "63":
                $label = "Card with restrictions: please contact the card issuer";
                break;
            case "76":
                $label = "Please try again";
                break;
            case "78":
                $label = "Please unblock the card";
                break;
            case "82":
                $label = "Invalid transaction";
                break;
            case "91":
                $label = "Bank offline";
                break;
            case "96":
                $label = "Please try again";
                break;
            case "AA":
                $label = "Please try again";
                break;
            case "AC":
                $label = "Debit card passed as credit card";
                break;
            case "GA":
                $label = "Please wait Cielo contact";
                break;
            case "N7":
                $label = "Invalid security code";
                break;
            default:
                $label = "";
        }
        
        return $this->__($label) . " (" . $authorizationCode . ")";
    }
}
