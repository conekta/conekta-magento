<?php
class Conekta_Oxxo_Model_Oxxo extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'oxxo';
	protected $_formBlockType = 'oxxo/form_oxxo';
	protected $_infoBlockType = 'oxxo/info_oxxo';

	public function assignData($data)
	{
		if (!($data instanceof Varien_Object)) {
			$data = new Varien_Object($data);
		}
		$info = $this->getInfoInstance();
		$info->setOxxoExpiryDate($data->getOxxoExpiryDate())
		->setOxxoBarcodeUrl($data->getOxxoBarcodeUrl())
		->setOxxoBarcode($data->getOxxoBarcode())
		;
		return $this;
	}
	
}
?>
