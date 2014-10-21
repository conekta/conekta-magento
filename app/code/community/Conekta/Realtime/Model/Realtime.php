<?php
class Conekta_Realtime_Model_Realtime extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'realtime';
	protected $_formBlockType = 'realtime/form_realtime';
	protected $_infoBlockType = 'realtime/info_realtime';

	public function assignData($data)
	{
		if (!($data instanceof Varien_Object)) {
			$data = new Varien_Object($data);
		}
		$info = $this->getInfoInstance();
		$info->setRealtimeExpiryDate($data->getRealtimeExpiryDate())
		->setRealtimeBarcodeUrl($data->getRealtimeBarcodeUrl())
		->setRealtimeBarcode($data->getRealtimeBarcode())
		->setRealtimeStoreName($data->getRealtimeStoreName())
		;
		return $this;
	}
	
}
?>
