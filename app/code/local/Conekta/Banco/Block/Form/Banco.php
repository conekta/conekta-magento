<?php
class Conekta_Banco_Block_Form_Banco extends Mage_Payment_Block_Form
{
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('banco/form/banco.phtml');
	}
 
}
