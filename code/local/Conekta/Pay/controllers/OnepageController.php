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

require_once 'Mage/Checkout/controllers/OnepageController.php';
class Conekta_Oxxo_OnepageController extends Mage_Checkout_OnepageController
{

	public function saveOrderAction()
	{
		$data = $this->getRequest()->getPost('payment', array());	
		$quote = Mage::getSingleton('checkout/cart')->getQuote();
		$publickey=Mage::getStoreConfig('payment/pay/apikey');
		$privatekey=Mage::getStoreConfig('payment/pay/apiprivatekey');
		if($publickey !=null)
		{
			$key=$publickey;
		} else {
			$key=$privatekey;
		}
		$currency=Mage::getStoreConfig('payment/pay/currency');
		$token_id=$data['cc_tokenid'];
		$grandTotal = $quote->getGrandTotal();
		$exploded_val=explode(".",$grandTotal);
		$exploded_val=$exploded_val['0']*100;
		
		require(dirname(__FILE__) . '/../../conekta-php/lib/Conekta.php');
		Conekta::setApiKey($key);
		$charge = Conekta_Charge::create(array('description'=>'Conekta Pay','reference_id'=>$token_id,'amount'=>$exploded_val,'currency'=>$currency,'card'=>$token_id));
		parent::saveOrderAction();
	}

}
