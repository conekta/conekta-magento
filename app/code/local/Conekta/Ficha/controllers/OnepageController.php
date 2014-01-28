<?php
require_once 'Mage/Checkout/controllers/OnepageController.php';
class Conekta_Ficha_OnepageController extends  Mage_Checkout_OnepageController{

	public function savePaymentAction()
	{
		if ($this->_expireAjax()) {
			return;
		}
		try {
			if (!$this->getRequest()->isPost()) {
				$this->_ajaxRedirectResponse();
				return;
			}

			// set payment to quote
			$result = array();
			$data = $this->getRequest()->getPost('payment', array());
			$result = $this->getOnepage()->savePayment($data);

			if (empty($result['error'])) {
				$result['goto_section'] = 'ficha';
				$result['update_section'] = array(
                    'name' => 'ficha',
                    'html' => $this->_getFichaHtml()
				);
			}

		} catch (Mage_Payment_Exception $e) {
			if ($e->getFields()) {
				$result['fields'] = $e->getFields();
			}
			$result['error'] = $e->getMessage();
		} catch (Mage_Core_Exception $e) {
			$result['error'] = $e->getMessage();
		} catch (Exception $e) {
			Mage::logException($e);
			$result['error'] = $this->__('Unable to set Payment Method.');
		}
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}
	public function saveFichaAction(){
		if ($this->_expireAjax()) {
			return;
		}

		// get section and redirect data
		$redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();

		if (!isset($result['error'])) {
			$this->loadLayout('checkout_onepage_review');
			$result['goto_section'] = 'review';
			$result['update_section'] = array(
                    'name' => 'review',
                    'html' => $this->_getReviewHtml()
			);
		}
		if ($redirectUrl) {
			$result['redirect'] = $redirectUrl;
		}

		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}
	protected function _getFichaHtml()
	{
		$layout = $this->getLayout();
		$update = $layout->getUpdate();
		$update->load('checkout_onepage_ficha');
		$layout->generateXml();
		$layout->generateBlocks();
		$output = $layout->getOutput();
		return $output;
	}
}
