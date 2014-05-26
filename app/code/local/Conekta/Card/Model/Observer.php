<?php
include_once(Mage::getBaseDir('lib') . DS . 'Conekta' . DS . 'lib' . DS . 'Conekta.php');
class Conekta_Card_Model_Observer{
    public function processPayment($event){
        if($event->payment->getMethod() == Mage::getModel('Conekta_Card_Model_Card')->getCode()){
            Conekta::setApiKey(Mage::getStoreConfig('payment/card/privatekey'));
            $billing = $event->payment->getOrder()->getBillingAddress()->getData();
            $shipping = $event->payment->getOrder()->getShippingAddress()->getData();
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
						if (true) {
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
										'card' => $_POST['payment']['conekta_token'],
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
            $event->payment->setCardToken($_POST['payment']['conekta_token']);
            $event->payment->setChargeAuthorization($charge->auth_code);
            $event->payment->setChargeId($charge->id);
        }
        return $event;
    }
    
    public function implementOrderStatus($event)
    {
        $order = $event->getOrder();
				$this->_processOrderStatus($order);
        return $this;
    }
 
    private function _getPaymentMethod($order)
    {
        return $order->getPayment()->getMethodInstance()->getCode();
    }
 
    private function _processOrderStatus($order)
    {
        $invoice = $order->prepareInvoice();
 
        $invoice->register();
        Mage::getModel('core/resource_transaction')
           ->addObject($invoice)
           ->addObject($invoice->getOrder())
           ->save();
 
        $invoice->sendEmail(true, '');
        $this->_changeOrderStatus($order);
        return true;
    }
 
    private function _changeOrderStatus($order)
    {
        $statusMessage = '';
				$order->addStatusToHistory(Mage_Sales_Model_Order::STATE_COMPLETE);
				$order->setData('state', Mage_Sales_Model_Order::STATE_COMPLETE);
				$order->save();
    }
}
