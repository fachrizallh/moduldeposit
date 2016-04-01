<?php
class ModulDepositPaymentModuleFrontController extends ModuleFrontController{
	public $ssl = true;
	private function checkCurrency()
	{
		// Get cart currency and enabled currencies for this module
		$currency_order = new Currency($this->context->cart->id_currency);
		$currencies_module = $this->module->getCurrency($this->context->cart->id_currency);

		// Check if cart currency is one of the enabled currencies
		if (is_array($currencies_module))
			foreach ($currencies_module as $currency_module)
				if ($currency_order->id == $currency_module['id_currency'])
					return true;

		// Return false otherwise
		return false;
	}
	public function initContent()
	{
		$this->display_column_left = false;
		$this->display_column_right = false;
		// Call parent init content method
		parent::initContent();
		// Check if currency is accepted
		if (!$this->checkCurrency())
			Tools::redirect('index.php?controller=order');
		//require_once(dirname(__FILE__).'/../../classes/bank.php');
		//$paysistem = new bank((int)Tools::getValue('id_pembayaran_bank'), $this->context->cookie->id_lang);	
		// Assign data to Smarty
		$this->context->smarty->assign(array(
			//'paysistem' => $paysistem,
			'nb_products' => $this->context->cart->nbProducts(),
			'cart_currency' => $this->context->cart->id_currency,
			'currencies' => $this->module->getCurrency((int)$this->context->cart->id_currency),
			'total_amount' => $this->context->cart->getOrderTotal(true, Cart::BOTH),
			'path' => $this->module->getPathUri(),
		));
		// Set template
		$this->setTemplate('payment.tpl');
	}
}