<?php
class Conekta_Bank_Block_Form_Bank extends Mage_Payment_Block_Form
{
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('bank/form/bank.phtml');
	}
}
