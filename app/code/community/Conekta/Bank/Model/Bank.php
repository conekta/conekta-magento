<?php
class Conekta_Bank_Model_Bank extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'bank';
	protected $_formBlockType = 'bank/form_bank';
	protected $_infoBlockType = 'bank/info_bank';

	public function assignData($data)
	{
		if (!($data instanceof Varien_Object)) {
			$data = new Varien_Object($data);
		}
		$info = $this->getInfoInstance();
		$info->setBankExpiryDate($data->getBankExpiryDate())
		->setBankServiceName($data->getBankServiceName())
		->setBankServiceNumber($data->getBankServiceNumber())
		->setBankName($data->getBankName())
		->setBankReference($data->getBankReference())
		;
		return $this;
	}
	
}
?>
