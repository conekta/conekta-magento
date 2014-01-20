<?php
class Conekta_Pay_Block_Info_Pay extends Mage_Payment_Block_Info
{
	protected function _prepareSpecificInformation($transport = null)
	{
		if (null !== $this->_paymentSpecificInformation) {
			return $this->_paymentSpecificInformation;
		}
		$info = $this->getInfo();
		$transport = new Varien_Object();
		$transport = parent::_prepareSpecificInformation($transport);
		$transport->addData(array(
			Mage::helper('payment')->__('Name on Card') => $info->getCcOwner(),
			Mage::helper('payment')->__('Credit Card Type') => $info->getCcType(),
			Mage::helper('payment')->__('Credit Card Number') => $info->getCcNumber(),
			Mage::helper('payment')->__('Expiration Month') => $info->getCcExpMonth(),
			Mage::helper('payment')->__('Expiration Year') => $info->getCcExpYear(),
			Mage::helper('payment')->__('Expiration Year') => $info->getCcAuthCode()
		));
		return $transport;
	}
}
