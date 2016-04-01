<?php
class ModulDepositValidationModuleFrontController extends ModuleFrontController{
	public function postProcess()
	{
		// Check if cart exists and all fields are set
		$cart = $this->context->cart;
		if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
			Tools::redirect('index.php?controller=order&step=1');

		// Check if module is enabled
		$authorized = false;
		foreach (Module::getPaymentModules() as $module)
			if ($module['name'] == $this->module->name)
				$authorized = true;
		if (!$authorized)
			die('This payment method is not available.');

		// Check if customer exists
		$customer = new Customer($cart->id_customer);
		if (!Validate::isLoadedObject($customer))
			Tools::redirect('index.php?controller=order&step=1');

		// Set datas
		$currency = $this->context->currency;
		$total = (float)$cart->getOrderTotal(true, Cart::BOTH);
		
		$datadeposit = deposit::getDataDeposit($this->context->customer->id);
		$saldo_sekarang = $datadeposit[0]['saldo_sekarang'];
		$saldosisa = $saldo_sekarang - $total;
		$insert = array(
			'saldo_sekarang' => $saldosisa,
		);
		Db::getInstance()->update('pembayaran_deposit',$insert, 'id_pembeli = '.$this->context->customer->id.'');
		//require_once(dirname(__FILE__).'/../../classes/bank.php');
		//$paysistem = new bank((int)Tools::getValue('id_pembayaran_bank'), $this->context->cookie->id_lang);
		
		$extra_vars = array(
			'{total_to_pay}' => Tools::displayPrice($total),
		);
		// Validate order
		$this->module->validateOrder($cart->id, Configuration::get('PS_MODUL_DEPOSIT_PAYMENT'), $total,
			//$this->module->displayName, NULL, $extra_vars, (int)$currency->id, false, $customer->secure_key);
			$this->module->displayName, NULL, $extra_vars, (int)$currency->id, false, $customer->secure_key);

		// Redirect on order confirmation page
		Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cart->id.'&id_module='.
			$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
		
	}
}