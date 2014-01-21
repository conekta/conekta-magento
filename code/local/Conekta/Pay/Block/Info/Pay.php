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
		$fecha = ($info->getCcExpMonth() . '/' . $info->getCcExpYear());
		$transport->addData(array(
			Mage::helper('payment')->__('Name on Card') => $info->getCcOwner(),
			Mage::helper('payment')->__('Credit Card Number') => $info->getCcLast4(),
			Mage::helper('payment')->__('Fecha de expiración') => $fecha,
			Mage::helper('payment')->__('Código de autorización') => $info->getCcAuthCode()
		));
		return $transport;
	}
}
