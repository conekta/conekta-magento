<?php
class Conekta_Webhook_AjaxController extends Mage_Core_Controller_Front_Action {
  public function listenerAction() {
    $body = @file_get_contents('php://input');
    $event = json_decode($body);
    $charge = $event->data->object;
    $line_items = $charge->details->line_items;
    sleep(3);
    $order = Mage::getModel('sales/order')->loadByIncrementId($charge->reference_id);
    if (strpos($event->type, "charge.paid") !== false) {
      if ($order->getId()) {
        if ($order->hasInvoices() != true) {
          $invoice = $order->prepareInvoice();
          $invoice->register();
          Mage::getModel('core/resource_transaction')
          ->addObject($invoice)
          ->addObject($invoice->getOrder())
          ->save();
          $invoice->sendEmail(true, '');
        }
        $orderStatus = $order->getPayment()->getMethodInstance()->getConfigData('webhook_notification_order_status');
        if (!(strpos($order->getStatus(), $orderStatus) !== false)) {
          $order->addStatusToHistory($orderStatus);
          $order->setData('state', $orderStatus);
          $order->save();
        }
      } else {
        // Order possible has not been persisted yet. Tell Conekta to retry one hour later.
        header('HTTP/1.0 404 Not Found');
        $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
        $this->getResponse()->setHeader('Status','404 File not found');
        $this->_forward('defaultNoRoute');
      }
    }
  }
}
?>
