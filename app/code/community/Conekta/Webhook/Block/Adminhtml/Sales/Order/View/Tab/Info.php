<?php
class Conekta_Webhook_Block_Adminhtml_Sales_Order_View_Tab_Info extends Mage_Adminhtml_Block_Sales_Order_View_Tab_Info
{
    public function getPaymentHtml()
    {
        return "<p>Order was placed using ".$this->getOrder()->getPayment()->getMethod()." payment method</p>";
    }
}