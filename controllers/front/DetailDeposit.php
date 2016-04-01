<?php
class ModulDepositDetailDepositModuleFrontController extends ModuleFrontController
{
	public $auth = true;
	public $ssl = true;
	public function displayAjax(){
		$this->display();
	}
   /**
	* Assign template vars related to page content
   * @see FrontController::initContent()
   */
	public function setMedia()
    {
        // We call the parent method
        parent::setMedia();
        $this->addJqueryPlugin(array('scrollTo', 'footable','footable-sort', 'validate'));
    }
   public function initContent()
	{
      $this->display_column_left = false;
      $this->display_column_right = false;
		
		parent::initContent();
		$datadeposit = deposit::getDataDeposit($this->context->customer->id);
		$saldo_sekarang = $datadeposit[0]['saldo_sekarang'];
		$nama_pembelinya = $this->context->customer->firstname.' '.$this->context->customer->lastname;
		$this->context->smarty->assign(array(
         'nama' => $nama_pembelinya,
         'saldo' => $saldo_sekarang,
      ));
		$this->setTemplate('detaildeposit.tpl');
   }
}


