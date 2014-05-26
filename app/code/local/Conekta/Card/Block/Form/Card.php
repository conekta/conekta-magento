<?php
class Conekta_Card_Block_Form_Card extends Mage_Payment_Block_Form
{
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('card/form/card.phtml');
	}
}
