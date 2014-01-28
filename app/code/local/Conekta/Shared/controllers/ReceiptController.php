<?php
/**
* Magento
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@magentocommerce.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Magento to newer
* versions in the future. If you wish to customize Magento for your
* needs please refer to http://www.magentocommerce.com for more information.
*
* @category    Mage
* @package     Mage_Checkout
* @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
* @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/

include_once 'Mage/Checkout/controllers/OnepageController.php';
class Receipt_Controller extends Mage_Checkout_OnepageController
{
	public function saveOrderAction()
	{
		$data = $this->getRequest()->getPost('payment', array());	
		if($data['method']== 'tarjeta')  {
			$quote = Mage::getSingleton('checkout/cart')->getQuote();
			$publickey=Mage::getStoreConfig('payment/tarjeta/apikey');
			$privatekey=Mage::getStoreConfig('payment/tarjeta/apiprivatekey');
			if($privatekey !=null) {
				$key=$privatekey;
			} else {
				$key=$publickey;
			}
			$currency=Mage::getStoreConfig('payment/tarjeta/currency');
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
	
}
