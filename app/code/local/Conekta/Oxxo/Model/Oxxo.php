<?php
require_once(dirname(__FILE__) . '/../../Shared/Model/PaymentMethod.php');
class Conekta_Oxxo_Model_Oxxo extends Payment_Method
{
	protected $_code = 'oxxo';
	protected $_formBlockType = 'oxxo/form_oxxo';
	protected $_infoBlockType = 'oxxo/info_oxxo';

	public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->newInFoInstance();
        $info->setCodigoBarras($data->getCodigoBarras());
        return $this;
    }
}
?>
