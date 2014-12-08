<?php
class Conekta_Spei_Model_Spei extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'spei';
	protected $_formBlockType = 'spei/form_spei';
	protected $_infoBlockType = 'spei/info_spei';

	public function assignData($data)
	{
		if (!($data instanceof Varien_Object)) {
			$data = new Varien_Object($data);
		}
		$info = $this->getInfoInstance();
		$info->setSpeiClabe($data->getSpeiClabe())
		->setSpeiBank($data->getSpeiBank())
		;
		return $this;
	}
	
}
?>
