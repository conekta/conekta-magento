<?php
class Conekta_Card_Model_Card extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'card';
	protected $_formBlockType = 'card/form_card';
	protected $_infoBlockType = 'card/info_card';

	public function assignData($data)
	{
		if (!($data instanceof Varien_Object)) {
			$data = new Varien_Object($data);
		}
		$info = $this->getInfoInstance();
		$info->setCardToken($data->getCardToken());
		return $this;
	}
   
}
?>
