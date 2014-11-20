<?php
class Conekta_Card_Block_Onepage_Success extends Mage_Checkout_Block_Onepage_Success
{
  // protected function _construct() {
  //   $order = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
  //   $this->setTemplate('checkout/success.phtml');
  // }

  public function getPayment()
  {
      $order = Mage::getModel('sales/order')->loadByIncrementId($this->_getData('order_id'));
      $payment = $order->getPayment();
      return $payment;
  }
}
?>
