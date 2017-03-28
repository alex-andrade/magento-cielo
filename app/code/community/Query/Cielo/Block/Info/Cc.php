<?php

/*
 * Query Commerce Cielo Module - payment method module for Magento,
 * integrating the billing forms with a Cielo's gateway Web Service.
 * Copyright (C) 2013  Fillipe Almeida Dutra
 * Belo Horizonte, Minas Gerais - Brazil
 * 
 * Contact: lawsann@gmail.com
 * Project link: http://code.google.com/p/magento-cielo/
 * Group discussion: http://groups.google.com/group/cielo-magento
 * 
 * Team: 
 * Fillipe Almeida Dutra - lawsann@gmail.com
 * Hermes Luciano Monteiro Junior - hermeslmj@gmail.com
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Query_Cielo_Block_Info_Cc extends Mage_Payment_Block_Info
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
