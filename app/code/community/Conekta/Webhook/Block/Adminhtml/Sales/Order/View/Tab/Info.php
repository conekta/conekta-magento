<?php
class Conekta_Webhook_Block_Adminhtml_Sales_Order_View_Tab_Info extends Mage_Adminhtml_Block_Sales_Order_View_Tab_Info
{
    public function getPaymentHtml()
    {
        $_order   = $this->getOrder();
        $_payment = $_order->getPayment();
        $_method  = $_payment->getMethod();
        if ($_method == "oxxo" || $_method == "card" || $_method == "spei") {
          $charge_id = $_payment->getChargeId();
          $grandTotal = Mage::helper('core')->currency($_order->grandTotal, true, true);
          $html = "<p>". $this->__("Order was placed using Conekta %s", strtoupper($_method)) ."</p>" .
          "<p>". $this->__("Order amount %s", $grandTotal) ."</p>" .
          "<p>". $this->__("Conekta Id %s", $charge_id) ."</p>";
          return $html;
        } else {
          return parent::getPaymentHtml();
        }
    }
}