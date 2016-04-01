<?php
class ModulDepositProsesDepositModuleFrontController extends ModuleFrontController
{
	public $auth = true;
	public $ssl = true;
   /**
	* Assign template vars related to page content
   * @see FrontController::initContent()
   */
   public function initContent()
	{
      $this->display_column_left = false;
      $this->display_column_right = false;
		
		parent::initContent();
		//var_dump(deposit::getDataDeposit());
		//var_dump($DataDeposit);
		//Db::getInstance()->executeS('SELECT id_pembeli FROM '._DB_PREFIX_.'pembayaran_deposit WHERE id_pembeli = '.$this->context->customer->id.'');
		$id_pembelinya = $this->context->customer->id;
		$datadeposit = deposit::getDataDeposit($id_pembelinya);
		$nama_banknya = $datadeposit[0]['nama_bank'];
		$referensinya = Db::getInstance()->executeS('SELECT reference FROM '._DB_PREFIX_.'orders WHERE id_customer = '.$id_pembelinya.' and id_order = (SELECT max(id_order) FROM '._DB_PREFIX_.'orders)');
		$info_pembayaran = deposit::getInfoBayar($id_pembelinya, $nama_banknya);
		$this->context->smarty->assign(array(
         'info_pembayaran' => $info_pembayaran,
         'datadeposit' => $datadeposit,
         'referensinya' => $referensinya,
      ));
		$this->setTemplate('infobayar.tpl');
   }
	public function postProcess(){
      if(Tools::isSubmit('mymod_pc_submit_deposit')){
			$nama_pembelinya = $this->context->customer->firstname.' '.$this->context->customer->lastname;
			$id_pembelinya = $this->context->customer->id;
			$id_pembeli = $id_pembelinya;
			$nama_pembeli = $nama_pembelinya;
			$nama_bank = Tools::getValue('nama_bank');
			$nominal_tambah = Tools::getValue('nominal_tambah');
			$timestamp = date('Y-m-d G:i:s');
			$referensi = Order::generateReference();
			$lastCartId = deposit::getLastCartId();
			$nama_module = $this->module->name;
			$getOrderStateId = deposit::getOrderStateId();
			$getLastIdOrder = deposit::getLastIdOrder();
			$getLastIdOrderInvoice = deposit::getLastIdOrderInvoice();
			$getLastIdOrderPayment = deposit::getLastIdOrderPayment();
			$insert = array(
				'id_pembeli' => $id_pembeli,
				'nama_pembeli' => $nama_pembeli,
				'nama_bank' => $nama_bank,
				'nominal_tambah' => $nominal_tambah,
				'tanggal' => $timestamp,
				'active' => 0,
			);
			$insert2 = array(
				'reference' => $referensi,
				'id_customer' => $id_pembeli,
				'id_cart' => $lastCartId[0]['max(id_cart)']+1,
				//'id_cart' => $lastCartId,
				'id_currency' => $this->context->cart->id_currency,
				'id_address_delivery' => $this->context->cart->id_address_delivery,
				'id_address_invoice' => $this->context->cart->id_address_invoice,
				'secure_key' => $this->context->customer->secure_key,
				'payment' => $nama_bank,
				'module' => $nama_module,
				'total_paid' => $nominal_tambah,
				'total_paid_tax_incl' => $nominal_tambah,
				'total_paid_tax_excl' => $nominal_tambah,
				'total_paid_real' => $nominal_tambah,
				'total_products' => $nominal_tambah,
				'total_products_wt' => $nominal_tambah,
				'invoice_date' => $timestamp,
				'date_add' => $timestamp,
			);
			$insert3 = array(
				'id_order' => $getLastIdOrder[0]['max(id_order)']+1,
				'id_order_invoice' => $getLastIdOrderInvoice[0]['max(id_order_invoice)']+1,
				'product_name' => 'Deposit Pembayaran',
				'product_quantity' => 1,
				'product_quantity_in_stock' => 1,
				'product_price' => $nominal_tambah,
				'total_price_tax_incl' => $nominal_tambah,
				'total_price_tax_excl' => $nominal_tambah,
				'unit_price_tax_incl' => $nominal_tambah,
				'unit_price_tax_excl' => $nominal_tambah,
				'original_product_price' => $nominal_tambah,
			);
			$insert4 = array(
				'id_order' => $getLastIdOrder[0]['max(id_order)']+1,
				'id_order_state' => $getOrderStateId[0]['max(id_order_state)'],
				'date_add' => $timestamp,
			);
			$insert5 = array(
				'id_order' => $getLastIdOrder[0]['max(id_order)']+1,
				'number' => $getLastIdOrder[0]['max(id_order)']+1,
				'total_paid_tax_incl' => $nominal_tambah,
				'total_paid_tax_excl' => $nominal_tambah,
				'total_products' => $nominal_tambah,
				'total_products_wt' => $nominal_tambah,
				'date_add' => $timestamp,
			);
			$insert6 = array(
				'id_order_invoice' => $getLastIdOrderInvoice[0]['max(id_order_invoice)']+1,
				'id_order_payment' => $getLastIdOrderPayment[0]['max(id_order_payment)']+1,
				'id_order' => $getLastIdOrder[0]['max(id_order)']+1,
			);
			$insert7 = array(
				'order_reference' => $referensi,
				'id_currency' => $this->context->cart->id_currency,
				'amount' => $nominal_tambah,
				'payment_method' => $nama_bank,
				'conversion_rate' => 1,
				'date_add' => $timestamp,
			);
			$insert8 = array(
				'id_shop_group' => $this->context->cart->id_shop_group,
				'id_shop' => $this->context->cart->id_shop,
				'id_lang' => $this->context->cart->id_lang,
				'id_address_delivery' => $this->context->cart->id_address_delivery,
				'id_address_invoice' => $this->context->cart->id_address_invoice,
				'id_currency' => $this->context->cart->id_currency,
				'id_customer' => $id_pembeli,
				'id_guest' => $this->context->cart->id_guest,
				'secure_key' => $this->context->customer->secure_key,
				'date_add' => $timestamp,
				'date_upd' => $timestamp,
			);
			$insert9 = array(
				'id_cart' => $lastCartId[0]['max(id_cart)']+1,
				//'id_cart' => $lastCartId,
				'id_shop' => $this->context->cart->id_shop,
				'id_address_delivery' => $this->context->cart->id_address_delivery,
				'date_add' => $timestamp,
			);
			//ditambah jika belum ada di database maka insert
			//...............
			//else
			$DataDeposit = deposit::getDataDeposit($id_pembelinya);
			if(count($DataDeposit) > 0) {
				Db::getInstance()->update('pembayaran_deposit',$insert, 'id_pembeli = '.$this->context->customer->id.'');
			}
			else
			{
				Db::getInstance()->insert('pembayaran_deposit',$insert);
			}
			
			Db::getInstance()->insert('orders',$insert2);
			Db::getInstance()->insert('order_detail',$insert3);
			Db::getInstance()->insert('order_history',$insert4);
			Db::getInstance()->insert('order_invoice',$insert5);
			Db::getInstance()->insert('order_invoice_payment',$insert6);
			Db::getInstance()->insert('order_payment',$insert7);
			Db::getInstance()->insert('cart',$insert8);
			Db::getInstance()->insert('cart_product',$insert9);
			$bank_tersedia = deposit::getBank();
			$this->context->smarty->assign(array(
				'bank_tersedia' => $bank_tersedia,
			));
			
			//mulai dr insert sampai Db, digunakan untuk upload ke database (int) dan pSQL untuk menghindari SQL injection saja
			/*$lastKonfirmasi = konfirmasi::getIdKonfirmasi();
			$b = max($lastKonfirmasi);
			$c = $b['id_konfirmasi_produk'];
			Mail::Send(
				$this->context->language->id,
				'template',
				Mail::l('Konfirmasi Pembayaran', $this->context->language->id),
				array(
					'{firstname}' => $this->context->customer->firstname,
					'{lastname}' => $this->context->customer->lastname,
					'{id_konfirmasi_produk}' => $c,
					'{no_order}' => $no_order,
					'{nama_bank}' => $nama_bank,
					'{nama_pengirim}' => $nama_pengirim,
					'{tanggal_transfer}' => $tanggal_transfer2,
					'{jumlah_dana}' => $jumlah_dana,
					'{date_add_konfirmasi}' => $date_add_konfirmasi2
				),
				$this->context->customer->email,
				$this->context->customer->firstname.' '.$this->context->customer->lastname,
				null,
				strval(Configuration::get('PS_SHOP_NAME')),
				null,
				null,
				$this->module->getLocalPath().'mails/');
			$this->context->smarty->assign('konfirmasi', 'ok');*/
			
			/*$nama_pembelinya = $this->context->customer->firstname.' '.$this->context->customer->lastname;
			$id_pembelinya = $this->context->customer->id;
			$id_pembeli = $id_pembelinya;
			$nama_pembeli = $nama_pembelinya;
			$nama_bank = Tools::getValue('nama_bank');
			$nominal_tambah = Tools::getValue('nominal_tambah');
			$timestamp = date('Y-m-d G:i:s');
			$lastCartId = deposit::getLastCartId();
			$cartIdnya = $lastCartId[0]['max(id_cart)']+1;
			$currency = $this->context->currency;
			$total = (float)$nominal_tambah;
			$insert = array(
				'id_pembeli' => $id_pembeli,
				'nama_pembeli' => $nama_pembeli,
				'nama_bank' => $nama_bank,
				'nominal_tambah' => $nominal_tambah,
				'tanggal' => $timestamp,
			);
			$DataDeposit = deposit::getDataDeposit($id_pembelinya);
			if(count($DataDeposit) > 0) {
				Db::getInstance()->update('pembayaran_deposit',$insert, 'id_pembeli = '.$this->context->customer->id.'');
			}
			else
			{
				Db::getInstance()->insert('pembayaran_deposit',$insert);
			}
			//require_once(dirname(__FILE__).'/../../classes/bank.php');
			//$paysistem = new bank((int)Tools::getValue('id_pembayaran_bank'), $this->context->cookie->id_lang);
			
			$extra_vars = array(
				'{total_to_pay}' => Tools::displayPrice($total),
			);
			// Validate order
			$this->module->validateOrder($cartIdnya, Configuration::get('PS_MODUL_DEPOSIT_PAYMENT'), $total,
				//$this->module->displayName, NULL, $extra_vars, (int)$currency->id, false, $customer->secure_key);
				$nama_bank, NULL, $extra_vars, (int)$currency->id, false, $this->context->customer->secure_key);

			// Redirect on order confirmation page
			Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cartIdnya.'&id_module='.
				$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$this->context->customer->secure_key);*/
      }
   }
}