<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once (dirname(__FILE__).'/../../classes/deposit.php');
class AdminModulDepositController extends ModuleAdminController{
   public function __construct(){
		$this->bootstrap = true;
		$this->context = Context::getContext();
	 	$this->table = 'pembayaran_deposit';
	 	$this->className = 'deposit';
      $this->addRowAction('edit');
      $this->addRowAction('delete');
      $this->list_no_link =true; //dari AdminTaxRulesGroupController
      $this->fieldImageSettings = array('name' => 'logo', 'dir' => 'moduldeposit');
		$this->fields_list = array(
			'id_pembayaran_deposit' => array('title' => $this->l('ID Deposit'), 'align' => 'center', 'width' => 25),
			'nama_pembeli' => array('title' => $this->l('Nama Pembeli'), 'align' => 'center', 'width' => 25),
			'nominal_tambah' => array('title' => $this->l('Nominal Tambah'), 'type' => 'price', 'align' => 'center', 'width' => 25),
			'saldo_sekarang' => array('title' => $this->l('Saldo Pembeli'), 'type' => 'price', 'align' => 'center', 'width' => 25),
			'active' => array('title' => $this->l('Konfirmasi Deposit'), 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false),
 		);
		parent::__construct();
		$tambah = $this->prosesTambah();
		for($i=0; $i<count($tambah); $i++){
			if($tambah[$i]['active']==1){
				$jumlah = $tambah[$i]['nominal_tambah']+$tambah[$i]['saldo_sekarang'];
				$update = array(
					'nominal_tambah' => 0,
					'saldo_sekarang' => $jumlah,
				);
				Db::getInstance()->update('pembayaran_deposit',$update, 'id_pembayaran_deposit = '.$tambah[$i]['id_pembayaran_deposit'].'');
			}
		}
	}
	
	public function initToolbar(){
		// If display list, we don't want the "add" button
		if (!$this->display || $this->display == 'list')
			return;
		parent::initToolbar();
	}
	
   public function renderForm(){
		$this->display = 'edit';
		$this->initToolbar();
		$this->fields_form = array(
		'tinymce' => TRUE,
		'legend' => array('title' => $this->l('Field'), 'image' =>
                        '../img/admin/tab-categories.gif'),
								'input' => array(
								array(
									'type' => 'text',
									'label' => $this->l('Nama Pembeli'),
									'name' => 'nama_pembeli',
									'id' => 'nama_pembeli', 
									'required' => true,
									'readonly' => true,
									'hint' => $this->l('Nama pembeli yang mempunyai deposit'),
									'size' => 20),
								array(
									'type' => 'text',
									'label' => $this->l('Saldo Tambah'),
									'name' => 'nominal_tambah',
									'id' => 'nominal_tambah', 
									'prefix' => 'Rp',
									'required' => true,
									'hint' => $this->l('Jumlah saldo yang ingin ditambahkan pembeli'),
									'size' => 20),	
								array(
									'type' => 'text',
									'label' => $this->l('Saldo Pembeli'),
									'name' => 'saldo_sekarang',
									'id' => 'saldo_sekarang', 
									'prefix' => 'Rp',
									'required' => true,
									'hint' => $this->l('Jumlah sisa saldo pembeli'),
									'size' => 20),
								array(
									'type' => 'switch',
									'label' => $this->l('Langsung tambahkan'),
									'name' => 'active',
									'id' => 'active', 
									'hint' => $this->l('Apakah anda ingin langsung menambahkan saldo tambah dan saldo pembeli?'),
									'values' => array(
											array(
												'id' => 'active_1',
												'value' => 1,
												'label' => $this->l('Ya')
											),
											array(
												'id' => 'active_0',
												'value' => 0,
												'label' => $this->l('Tidak')
											),
										)
									),	
								),
								
								'submit' => array('title' => $this->l('Save')));
								
      return parent::renderForm();
   }
	public function prosesTambah(){
		$depo = Db::getInstance()->executeS('
		SELECT *
		FROM '._DB_PREFIX_.'pembayaran_deposit');
		return $depo;
	}
}