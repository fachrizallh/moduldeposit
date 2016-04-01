<?php

class deposit extends ObjectModel
{
   public $id_pemilik;
   public $nominal_tambah;
   public $nama_pembeli;
   public $saldo_sekarang;
	public $image_dir;
   public $active = 1;
	 
    public static $definition = array(
        'table' => 'pembayaran_deposit',
        'primary' => 'id_pembayaran_deposit',
        'fields' => array(
			'nama_pembeli' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 20),
			'nominal_tambah' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 20),
			'saldo_sekarang' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 20),
			'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
		),
    );
	public function __construct($id = null, $id_lang = null){
		$this->image_dir = _PS_IMG_DIR_.'moduldeposit/';
		return parent::__construct($id, $id_lang);
	}
	public static function getBank(){
		$bank = Db::getInstance()->executeS('
		SELECT *
		FROM '._DB_PREFIX_.'pembayaran_bank');
		return $bank;
	}
	public static function getLastCartId(){
		$CartId = Db::getInstance()->executeS('
		SELECT max(id_cart)
		FROM '._DB_PREFIX_.'cart');
		return $CartId;
	}
	public static function getOrderStateId(){
		$StateId = Db::getInstance()->executeS('
		SELECT max(id_order_state)
		FROM '._DB_PREFIX_.'order_state_lang 
		WHERE name = "Awaiting confirmation"');
		return $StateId;
	}
	public static function getLastIdOrder(){
		$LastIdOrder = Db::getInstance()->executeS('
		SELECT max(id_order)
		FROM '._DB_PREFIX_.'orders');
		return $LastIdOrder;
	}
	public static function getLastIdOrderInvoice(){
		$LastIdOrderInvoice = Db::getInstance()->executeS('
		SELECT max(id_order_invoice)
		FROM '._DB_PREFIX_.'order_invoice');
		return $LastIdOrderInvoice;
	}
	public static function getLastIdOrderPayment(){
		$LastIdOrderPayment = Db::getInstance()->executeS('
		SELECT max(id_order_payment)
		FROM '._DB_PREFIX_.'order_payment');
		return $LastIdOrderPayment;
	}
	public static function getDataDeposit($id_pembelinya){
		$DataDeposit = Db::getInstance()->executeS('
		SELECT *
		FROM '._DB_PREFIX_.'pembayaran_deposit WHERE id_pembeli = '.$id_pembelinya.'');
		return $DataDeposit;
	}
	public static function getDataDeposita(){
		$DataDeposit = Db::getInstance()->executeS('
		SELECT *
		FROM '._DB_PREFIX_.'pembayaran_deposit');
	}
	public static function getInfoBayar($id_pembelinya, $nama_banknya){
		$DataDeposit = Db::getInstance()->executeS('
		SELECT *
		FROM '._DB_PREFIX_.'pembayaran_bank INNER JOIN '._DB_PREFIX_.'pembayaran_deposit on (('._DB_PREFIX_.'pembayaran_bank.nama_bank = "'.$nama_banknya.'") and ('._DB_PREFIX_.'pembayaran_deposit.id_pembeli = '.$id_pembelinya.'))');
		return $DataDeposit;
	}
	public function add($autodate = true, $null_values = false){
        
      return parent::add();
   }
   public function update($null_values = FALSE){
      if (parent::update($null_values)) {
         return TRUE;
      }
      return FALSE;
   }
   public function delete()
   {
      if (parent::delete()) {
         return TRUE;
      }    
      return FALSE;
   } 
}