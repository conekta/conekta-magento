<?php
class Conekta_Oxxo_Block_Form_Oxxo extends Mage_Payment_Block_Form
{
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('oxxo/form/oxxo.phtml');
	}
}
