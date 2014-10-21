<?php
class Conekta_Realtime_Block_Info_Realtime extends Mage_Payment_Block_Info
{
	
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('realtime/info/realtime.phtml');
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
		$data[Mage::helper('payment')->__('Barcode Url')] = $info->getRealtimeBarcodeUrl();
		//$transport->addData(array(
			//Mage::helper('payment')->__('Expiry Date') => $info->getRealtimeExpiryDate(),
			//Mage::helper('payment')->__('Barcode Url') => $info->getRealtimeBarcodeUrl()
		//));
		return $transport->setData(array_merge($data, $transport->getData()));
	}
}
