<?php
require_once(dirname(__FILE__) . '/../../Shared/Model/PaymentMethod.php');
class Conekta_Bank_Model_Bank extends Payment_Method
{
	protected $_code = 'bank';
	protected $_formBlockType = 'bank/form_bank';
	protected $_infoBlockType = 'bank/info_bank';

	public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->newInFoInstance();
        $info->setNumeroServicio($data->getNumeroServicio())
			->setNombreServicio($data->getNombreServicio())
			->setReferencia($data->getReferencia())
			->setBank($data->getBank());
        return $this;
    }
}
?>
