<?php
require_once 'Mage/Checkout/controllers/OnepageController.php';
class Conekta_Invoice_OnepageController extends  Mage_Checkout_OnepageController 
{	
	public function saveOrderAction()
	{
		$data = $this->getRequest()->getPost('payment', array());	
		if (strpos($data['method'], 'card') !== false) {
			$quote = Mage::getSingleton('checkout/cart')->getQuote();
			$publickey=Mage::getStoreConfig('payment/card/apikey');
			$privatekey=Mage::getStoreConfig('payment/card/apiprivatekey');
			if($privatekey !=null) {
				$key=$privatekey;
			} else {
				$key=$publickey;
			}
			$currency=Mage::getStoreConfig('payment/card/currency');
			$token_id=$data['cc_tokenid'];
			$grandTotal = $quote->getGrandTotal();
			$exploded_val=explode(".",$grandTotal);
			$exploded_val=$exploded_val['0']*100;
			require_once(dirname(__FILE__) . '/../../conekta-php/lib/Conekta.php');
			Conekta::setApiKey($key);
			$s_info = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getData();
			$b_info = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getData();
			$p_info = $this->getOnepage()->getQuote()->getItemsCollection(array(), true);
			$n_items = count($p_info->getColumnValues('sku'));
			$line_items = array();
			for ($i = 0; $i < $n_items; $i ++) {
				$name = $p_info->getColumnValues('name');
				$name = $name[$i];
				$sku = $p_info->getColumnValues('sku');
				$sku = $sku[$i];
				$price = $p_info->getColumnValues('price');
				$price = $price[$i];
				$description = $p_info->getColumnValues('description');
				$description = $description[$i];
				$product_type = $p_info->getColumnValues('product_type');
				$product_type = $product_type[$i];
				$line_items = array_merge($line_items, array(array(
					"name"=>$name,
					"sku"=>$sku,
					"unit_price"=> $price,
					"description"=>$description,
					"quantity"=> 1,
					"type"=>$product_type
				  ))
				);
			}
			$shipment = array();
			if ($s_info['grand_total'] > 0) {
				$shipment = array(
				  "carrier"=>"estafeta",
				  "service"=>"international",
				  "tracking_id"=>"XXYYZZ-9990000",
				  "price"=> $s_info['grand_total'],
				  "address"=> array(
					"street1"=>"250 Alexis St",
					"city"=>$s_info['city'],
					"state"=>$s_info['region'],
					"country"=>$s_info['country_id'],
					"zip"=>$s_info['postcode'],
				  )
				);
			}
			$reference_id = Mage::getSingleton('checkout/session')->getQuote()->getId();
			try {
				$charge = Conekta_Charge::create(array(
				  "description"=>"Compra en Magento de " . $b_info['email'],
				  "amount"=> $exploded_val,
				  "currency"=> $currency,
				  "reference_id" => $reference_id,
				  "card"=> $token_id,
				  "details"=> array(
					"name"=> preg_replace('!\s+!', ' ', $b_info['firstname'] . ' ' . $b_info['middlename'] . ' ' . $b_info['firstname']),
					"email"=> $b_info['email'],
					"phone"=> $b_info['telephone'],
					"billing_address"=> array(
					  "company_name"=> $b_info['company'],
					  "street1"=> $b_info['street'],
					  "city"=>$b_info['city'],
					  "state"=>$b_info['region'],
					  "country"=>$b_info['country_id'],
					  "zip"=>$b_info['postcode'],
					  "phone"=>$b_info['telephone'],
					  "email"=>$b_info['email']
					),
					"line_items"=> $line_items
					),
					"shipment"=> $shipment
				  )
				);
				parent::saveOrderAction();
			} catch (Conekta_Error $e) {
				Mage::logException($e);
				Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
				$result['success'] = false;
				$result['error'] = true;
				$result['error_messages'] = $e->getMessage();

				if ($gotoSection = $this->getOnepage()->getCheckout()->getGotoSection()) {
					$result['goto_section'] = $gotoSection;
					$this->getOnepage()->getCheckout()->setGotoSection(null);
				}

				if ($updateSection = $this->getOnepage()->getCheckout()->getUpdateSection()) {
					if (isset($this->_sectionUpdateFunctions[$updateSection])) {
						$updateSectionFunction = $this->_sectionUpdateFunctions[$updateSection];
						$result['update_section'] = array(
							'name' => $updateSection,
							'html' => $this->$updateSectionFunction()
						);
					}
					$this->getOnepage()->getCheckout()->setUpdateSection(null);
				}
				$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
			}
		} else {
			parent::saveOrderAction();
		}
		
	}
	

	public function savePaymentAction()
	{
		if ($this->_expireAjax()) {
			return;
		}
		try {
			if (!$this->getRequest()->isPost()) {
				$this->_ajaxRedirectResponse();
				return;
			}

			// set payment to quote
			$result = array();
			$data = $this->getRequest()->getPost('payment', array());
			$result = $this->getOnepage()->savePayment($data);

			if (empty($result['error'])) {
				if (true) {
					$result['goto_section'] = 'invoice';
					$result['update_section'] = array(
						'name' => 'invoice',
						'html' => $this->_getInvoiceHtml()
					);
				} else {
					$this->loadLayout('checkout_onepage_review');
					$result['goto_section'] = 'review';
					$result['update_section'] = array(
						'name' => 'review',
						'html' => $this->_getReviewHtml()
					);
				}
			}

		} catch (Mage_Payment_Exception $e) {
			if ($e->getFields()) {
				$result['fields'] = $e->getFields();
			}
			$result['error'] = $e->getMessage();
		} catch (Mage_Core_Exception $e) {
			$result['error'] = $e->getMessage();
		} catch (Exception $e) {
			Mage::logException($e);
			$result['error'] = $this->__('Unable to set Payment Method.');
		}
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}
	public function saveInvoiceAction(){
		if ($this->_expireAjax()) {
			return;
		}

		// get section and redirect data
		$redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();

		if (!isset($result['error'])) {
			$this->loadLayout('checkout_onepage_review');
			$result['goto_section'] = 'review';
			$result['update_section'] = array(
                    'name' => 'review',
                    'html' => $this->_getReviewHtml()
			);
		}
		if ($redirectUrl) {
			$result['redirect'] = $redirectUrl;
		}

		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}
	protected function _getInvoiceHtml()
	{
		$layout = $this->getLayout();
		$update = $layout->getUpdate();
		$update->load('checkout_onepage_invoice');
		$layout->generateXml();
		$layout->generateBlocks();
		$output = $layout->getOutput();
		return $output;
	}
}
