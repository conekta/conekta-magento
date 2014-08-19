<?php
class Conekta_Oxxo_Block_Info_Oxxo extends Mage_Payment_Block_Info
{
	
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('oxxo/info/oxxo.phtml');
	}
	
	protected function _prepareSpecificInformation($transport = null)
	{
		if (null !== $this->_paymentSpecificInformation) {
			return $this->_paymentSpecificInformation;
		}
		$info = $this->getInfo();
		$transport = new Varien_Object();
		$transport = parent::_prepareSpecificInformation($transport);
		$data = array();
		$data[Mage::helper('payment')->__('Barcode Url')] = $info->getOxxoBarcodeUrl();
		//$transport->addData(array(
			//Mage::helper('payment')->__('Expiry Date') => $info->getOxxoExpiryDate(),
			//Mage::helper('payment')->__('Barcode Url') => $info->getOxxoBarcodeUrl()
		//));
		return $transport->setData(array_merge($data, $transport->getData()));
	}
}
