<?php
include_once(Mage::getBaseDir('lib') . DS . 'Conekta' . DS . 'lib' . DS . 'Conekta.php');
class Conekta_Bank_Model_Observer{
    public function processPayment($event){
        if($event->payment->getMethod() == Mage::getModel('Conekta_Bank_Model_Bank')->getCode()){
            Conekta::setApiKey(Mage::getStoreConfig('payment/bank/privatekey'));
            $billing = $event->payment->getOrder()->getBillingAddress()->getData();
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
								'email' =>$shipping['email']
								)
							);
						}
            try {
								$charge = Conekta_Charge::create(array(
										'bank'=>array(
												'type'=>'banorte'
										),
										'amount' => intval(((float) $event->payment->getOrder()->grandTotal) * 100),
										'description' => 'Compra en Magento',
										'reference_id' => $event->payment->getOrder()->getIncrementId(),
										'details' => array(
											'name' => preg_replace('!\s+!', ' ', $billing['firstname'] . ' ' . $billing['middlename'] . ' ' . $billing['firstname']),
											'email' => $billing['email'],
											'phone' => $billing['telephone'],
											'billing_address' => array(
												'company_name' => $billing['company'],
												'street1' => $billing['street'],
												'city' =>$billing['city'],
												'state' =>$billing['region'],
												'country' =>$billing['country_id'],
												'zip' =>$billing['postcode'],
												'phone' =>$billing['telephone'],
												'email' =>$billing['email']
											),
											'line_items' => $line_items,
											'shipment' => $shipp
											)
										)
								);
						} catch (Conekta_Error $e){
							throw new Mage_Payment_Model_Info_Exception($e->getMessage());
						}
            $event->payment->setBankServiceName($charge->payment_method->service_name);
            $event->payment->setBankServiceNumber($charge->payment_method->service_number);
            $event->payment->setBankName($charge->payment_method->type);
            $event->payment->setBankReference($charge->payment_method->reference);
            $event->payment->setChargeId($charge->id);
            
            //Update Quote
            $order = $event->payment->getOrder();
            $quote = $order->getQuote();
            $payment = $quote->getPayment();
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
    
    public function implementOrderStatus($event)
    {
        $order = $event->getOrder();
				if ($this->_getPaymentMethod($order) == Mage::getModel('Conekta_Bank_Model_Bank')->getCode()) {
            if ($order->canInvoice())
                $this->_processOrderStatus($order);
        }
        return $this;
    }
 
    private function _getPaymentMethod($order)
    {
        return $order->getPayment()->getMethodInstance()->getCode();
    }
 
    private function _processOrderStatus($order)
    {
				if ($order->hasInvoices() != true) {
					$invoice = $order->prepareInvoice();
					
					// Check if order is virtual
					$virtual = false;
					$items_collection = $order->getItemsCollection(array(), true);
					for ($i = 0; $i < count($items_collection->getColumnValues('sku')); $i ++) {
						$product_type = $items_collection->getColumnValues('product_type');
						if (strcmp($product_type, 'virtual') !== 0) {
								$virtual = true;
								break;
						}
					}
					
					if ($virtual != true) {
						$invoice->register();
						Mage::getModel('core/resource_transaction')
							 ->addObject($invoice)
							 ->addObject($invoice->getOrder())
							 ->save();
					}
	 
					$invoice->sendEmail(true, '');
					$this->_changeOrderStatus($order);
				}
        return true;
    }
 
    private function _changeOrderStatus($order)
    {
        $statusMessage = '';
				$order->addStatusToHistory(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT);
				$order->setData('state', Mage_Sales_Model_Order::STATE_PENDING_PAYMENT);
				$order->save();
    }
}
