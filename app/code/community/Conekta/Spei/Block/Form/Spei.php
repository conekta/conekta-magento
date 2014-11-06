<?php
class Conekta_Spei_Block_Form_Spei extends Mage_Payment_Block_Form
{
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('spei/form/spei.phtml');
	}
}
