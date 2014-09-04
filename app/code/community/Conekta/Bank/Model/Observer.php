<?php
include_once(Mage::getBaseDir('lib') . DS . 'Conekta' . DS . 'lib' . DS . 'Conekta.php');
class Conekta_Bank_Model_Observer{
  public function processPayment($event){
    if (!class_exists('Conekta')) {
      error_log("Plugin miss Conekta PHP lib dependency. Clone the repository using 'git clone --recursive git@github.com:conekta/conekta-magento.git'", 0);
      throw new Mage_Payment_Model_Info_Exception("Payment module unavailable. Please contact system administrator.");
    }
    if($event->payment->getMethod() == Mage::getModel('Conekta_Bank_Model_Bank')->getCode()){
      Conekta::setApiKey(Mage::getStoreConfig('payment/bank/privatekey'));
      $billing = $event->payment->getOrder()->getBillingAddress()->getData();
      $email = $event->payment->getOrder()->getEmail();
      if ($event->payment->getOrder()->getShippingAddress()) {
        $shipping = $event->payment->getOrder()->getShippingAddress()->getData();
      }
      $items_collection = $event->payment->getOrder()->getItemsCollection(array(), true);
      $line_items = array();
      for ($i = 0; $i < count($items_collection->getColumnValues('sku')); $i ++) {
        $name = $items_collection->getColumnValues('name');
        $name = $name[$i];
        $sku = $items_collection->getColumnValues('sku');
        $sku = $sku[$i];
        $price = $items_collection->getColumnValues('price');
        $price = $price[$i];
        $description = $items_collection->getColumnValues('description');
        $description = $description[$i];
        $product_type = $items_collection->getColumnValues('product_type');
        $product_type = $product_type[$i];
        $line_items = array_merge($line_items, array(array(
          'name' => $name,
          'sku' => $sku,
          'unit_price' => $price,
          'description' =>$description,
          'quantity' => 1,
          'type' => $product_type
          ))
        );
      }
      $shipp = array();
      if (empty($shipping) != true) {
        $shipp = array(
          'price' => $shipping['grand_total'],
          'address' => array(
            'street1' => $shipping['street'],
            'city' => $shipping['city'],
            'state' => $shipping['region'],
            'country' => $shipping['country_id'],
            'zip' => $shipping['postcode'],
            'phone' =>$shipping['telephone'],
            'email' =>$email
            )
          );
      }
      $days = $event->payment->getMethodInstance()->getConfigData('my_date');
      $expiry_date=Date('Y-m-d', strtotime("+".$days." days"));
      try {
        $charge = Conekta_Charge::create(array(
          'bank'=>array(
            'type'=>'banorte',
            'expires_at'=>$expiry_date
            ),
          'amount' => intval(((float) $event->payment->getOrder()->grandTotal) * 100),
          'description' => 'Compra en Magento',
          'reference_id' => $event->payment->getOrder()->getIncrementId(),
          'details' => array(
            'name' => preg_replace('!\s+!', ' ', $billing['firstname'] . ' ' . $billing['middlename'] . ' ' . $billing['firstname']),
            'email' => $email,
            'phone' => $billing['telephone'],
            'billing_address' => array(
              'company_name' => $billing['company'],
              'street1' => $billing['street'],
              'city' =>$billing['city'],
              'state' =>$billing['region'],
              'country' =>$billing['country_id'],
              'zip' =>$billing['postcode'],
              'phone' =>$billing['telephone'],
              'email' =>$email
              ),
            'line_items' => $line_items,
            'shipment' => $shipp
            )
          )
        );
      } catch (Conekta_Error $e){
        throw new Mage_Payment_Model_Info_Exception($e->getMessage());
      }
      $event->payment->setBankExpiryDate($expiry_date);
      $event->payment->setBankServiceName($charge->payment_method->service_name);
      $event->payment->setBankServiceNumber($charge->payment_method->service_number);
      $event->payment->setBankName($charge->payment_method->type);
      $event->payment->setBankReference($charge->payment_method->reference);
      $event->payment->setChargeId($charge->id);

      //Update Quote
      $order = $event->payment->getOrder();
      $quote = $order->getQuote();
      $payment = $quote->getPayment();
      $payment->setBankExpiryDate($expiry_date);
      $payment->setBankServiceName($charge->payment_method->service_name);
      $payment->setBankServiceNumber($charge->payment_method->service_number);
      $payment->setBankName($charge->payment_method->type);
      $payment->setBankReference($charge->payment_method->reference);
      $payment->setChargeId($charge->id);
      $quote->collectTotals();
      $quote->save();
      $order->setQuote($quote);
      $order->save();
    }
    return $event;
  }
}
