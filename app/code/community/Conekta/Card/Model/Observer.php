<?php
include_once(Mage::getBaseDir('lib') . DS . 'Conekta' . DS . 'lib' . DS . 'Conekta.php');
class Conekta_Card_Model_Observer{
  public function processPayment($event){
    if (!class_exists('Conekta')) {
      error_log("Plugin miss Conekta PHP lib dependency. Clone the repository using 'git clone --recursive git@github.com:conekta/conekta-magento.git'", 0);
      throw new Mage_Payment_Model_Info_Exception("Payment module unavailable. Please contact system administrator.");
    }
    if($event->payment->getMethod() == Mage::getModel('Conekta_Card_Model_Card')->getCode()){
      Conekta::setApiKey(Mage::getStoreConfig('payment/card/privatekey'));
      Conekta::setApiVersion("1.0.0");
      Conekta::setLocale(Mage::app()->getLocale()->getLocaleCode());

      $order = $event->payment->getOrder();
      $customer = $order->getCustomer();
      $shipping_address = $order->getShippingAddress();

      $billing = $order->getBillingAddress()->getData();
      $email = $order->getCustomerEmail();
      if ($shipping_address) {
        $shipping_data = $shipping_address->getData();
      }
      $items = $order->getAllVisibleItems();
      $line_items = array();
      $i = 0;
      foreach ($items as $itemId => $item){
        $name = $item->getName();
        $sku = $item->getSku();
        $price = $item->getPrice();
        $description = $item->getDescription();
        $product_type = $item->getProductType();
        $line_items = array_merge($line_items, array(array(
          'name' => $name,
          'sku' => $sku,
          'unit_price' => $price,
          'description' =>$description,
          'quantity' => 1,
          'type' => $product_type
          ))
        );
        $i = $i + 1;
      }
      $shipp = array();
      if (empty($shipping_data) != true) {
        $shipp = array(
          'price' => intval(((float) $order->getShippingAmount()) * 100),
          'service' => $order->getShippingMethod(),
          'carrier' => $order->getShippingDescription(),
          'address' => array(
            'street1' => $shipping_data['street'],
            'city' => $shipping_data['city'],
            'state' => $shipping_data['region'],
            'country' => $shipping_data['country_id'],
            'zip' => $shipping_data['postcode'],
            'phone' =>$shipping_data['telephone'],
            'email' =>$email
            )
          );
      }
      
      try {
        $params = array(
          'card' => $_POST['payment']['conekta_token'],
          'currency' => Mage::app()->getStore()->getCurrentCurrencyCode(),
          'amount' => intval(((float) $order->grandTotal) * 100),
          'description' => 'Compra en Magento',
          'reference_id' => $order->getIncrementId(),
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
            ),
            'coupon_code' => $order->getCouponCode(),
            'custom_fields' => array(
              'customer' => array(
                'website_id' => $customer->getWebsiteId(),
                'entity_id' => $customer->getEntityId(),
                'entity_type_id' => $customer->getEntityTypeId(),
                'attribute_set_id' => $customer->getAttributeSetId(),
                'email' => $customer->getEmail(),
                'group_id' => $customer->getGroupId(),
                'store_id' => $customer->getStoreId(),
                'created_at' => $customer->getCreatedAt(),
                'updated_at' => $customer->getUpdatedAt(),
                'is_active' => $customer->getIsActive(),
                'disable_auto_group_change' => $customer->getDisableAutoGroupChange(),
                'get_tax_vat' => $customer->getTaxvat(),
                'created_in' => $customer->getCreatedIn(),
                'gender' => $customer->getGender(),
                'default_billing' => $customer->getDefaultBilling(),
                'default_shipping' => $customer->getDefaultShipping(),
                'dob' => $customer->getDob(),
                'tax_class_id' => $customer->getTaxClassId()
              ),
              'discount_description' => $order->getDiscountDescription(),
              'discount_amount' => $order->getDiscountAmount(),
              'shipping_amount' => $shipping_address->getShippingAmount(),
              'shipping_description' => $shipping_address->getShippingDescription(),
              'shipping_method' => $shipping_address->getShippingMethod()
            )
          );
        if ($_POST['payment']['monthly_installments'] != 0) {
          $params['monthly_installments'] = $_POST['payment']['monthly_installments'];
        }
        $charge = Conekta_Charge::create($params);
      } catch (Conekta_Error $e) {
        throw new Mage_Payment_Model_Info_Exception($e->message_to_purchaser);
      }
      $event->payment->setCardToken($_POST['payment']['conekta_token']);
      $event->payment->setCardMonthlyInstallments($charge->monthly_installments);
      $event->payment->setChargeAuthorization($charge->payment_method->auth_code);
      $event->payment->setChargeId($charge->id);
      $event->payment->setCcOwner($charge->payment_method->name);
      $event->payment->setCcLast4($charge->payment_method->last4);
      $event->payment->setCcType($charge->payment_method->brand);
      $event->payment->setCardBin($_POST['card']['bin']);

      //Update Quote
      $quote = $order->getQuote();
      $payment = $quote->getPayment();
      $payment->setCardToken($_POST['payment']['conekta_token']);
      $payment->setCardMonthlyInstallments($charge->monthly_installments);
      $payment->setChargeAuthorization($charge->payment_method->auth_code);

      $payment->setCcOwner($charge->payment_method->name);
      $payment->setCcLast4($charge->payment_method->last4);
      $payment->setCcType($charge->payment_method->brand);
      $payment->setCardBin($_POST['card']['bin']);

      $payment->setChargeId($charge->id);
      $quote->collectTotals();
      $quote->save();
      $order->setQuote($quote);
      $order->save();
    }
    return $event;
  }
}
