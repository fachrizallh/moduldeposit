<?php
require_once(dirname(__FILE__). '/classes/deposit.php');
class ModulDeposit extends PaymentModule{
   public function __construct() {
		$this->name = 'moduldeposit';
		$this->tab = 'payments_gateways';
		$this->version = '1.0';
		$this->author = 'Fachrizal Lukman Hakim';
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Modul Deposit');
		$this->description = $this->l('Modul yang berguna untuk pembayaran dengan deposit');
	}
   public function install(){
		if (!parent::install())
			return false;
		$sql_file = dirname(__FILE__).'/install/install.sql';
		if (!$this->loadSQLFile($sql_file))
			return false;
		if (!$this->installOrderState())
			return false;
		if(!$this->installTab('AdminParentOrders', 'AdminModulDeposit', 'Deposit Pembayaran'))
			return false;
		if(!$this->registerHook('displayCustomerAccount') ||
         !$this->registerHook('displayNav') || 
         !$this->registerHook('displayPayment') ||
         !$this->registerHook('displayPaymentReturn')) 
			return false;
		return true;
   }
		  
   public function uninstall(){
		if (!parent::uninstall())
			return false;
		$sql_file = dirname(__FILE__).'/install/uninstall.sql';
		if (!$this->loadSQLFile($sql_file))
			return false;
		if(!$this->uninstallTab('AdminModulDeposit'))	
			return false;
		return true;
	}
			
	public function installTab($parent, $class_name, $name){
		$tab = new Tab();
		$tab->id_parent = (int)Tab::getIdFromClassName($parent);
		$tab->name = array();
		foreach (Language::getLanguages(true) as $lang)
			$tab->name[$lang['id_lang']] = $name;
			$tab->class_name = $class_name;
			$tab->module = $this->name;
			$tab->active = 1;
		return $tab->add();
	}
	public function uninstallTab($class_name){
		$id_tab = (int)Tab::getIdFromClassName($class_name);
		$tab = new Tab((int)$id_tab);
		return $tab->delete();
	}

	public function loadSQLFile($sql_file){
		// Get install SQL file content
		$sql_content = file_get_contents($sql_file);
		// Replace prefix and store SQL command in array
		$sql_content = str_replace('PREFIX_', _DB_PREFIX_, 
		$sql_content);
		$sql_requests = preg_split("/;\s*[\r\n]+/", $sql_content);
		// Execute each SQL statement
		$result = true;
		foreach($sql_requests as $request)
		if (!empty($request))
			$result &= Db::getInstance()->execute(trim($request));
		// Return result
		return $result;
	}
	public function hookDisplayCustomerAccount($params){
      return $this->display(__FILE__,'navigasi.tpl');
   }
	
	public function installOrderState(){
		if (Configuration::get('PS_MODUL_DEPOSIT_PAYMENT') < 1){
			$order_state = new OrderState();
			$order_state->send_email = true;
			$order_state->module_name = $this->name;
			$order_state->invoice = true;
			$order_state->color = '#98c3ff';
			$order_state->logable = true;				
			$order_state->shipped = false;
			$order_state->unremovable = false;
			$order_state->delivery = false;
			$order_state->hidden = false;			
			$order_state->paid = false;
			$order_state->deleted = false;
			$order_state->name = array((int)Configuration::get('PS_LANG_DEFAULT') => pSQL($this->l('Order in Progress')));
			$order_state->template = array();
			foreach (LanguageCore::getLanguages() as $l)
			$order_state->template[$l['id_lang']] = 'moduldeposit';
			
			// We copy the mails templates in mail directory
			foreach (LanguageCore::getLanguages() as $l){
				$module_path = dirname(__FILE__).'/views/templates/mails/'.$l['iso_code'].'/';
				$application_path = dirname(__FILE__).'/../../mails/'.$l['iso_code'].'/';
				if (!copy($module_path.'moduldeposit.txt', $application_path.'moduldeposit.txt') ||
				!copy($module_path.'moduldeposit.html', $application_path.'moduldeposit.html'))
				return false;
			}

			if ($order_state->add()){
				// We save the order State ID in Configuration database
				Configuration::updateValue('PS_MODUL_DEPOSIT_PAYMENT', $order_state->id);

				// We copy the module logo in order state logo directory
				copy(dirname(__FILE__).'/logo.gif', dirname(__FILE__).'/../../img/os/'.$order_state->id.'.gif');
				copy(dirname(__FILE__).'/logo.gif', dirname(__FILE__).'/../../img/tmp/order_state_mini_'.$order_state->id.'.gif');
			}
			else
				return false;
		}
		return true;
	}
	public function getHookController($hook_name){
		// Include the controller file
		require_once(dirname(__FILE__).'/controllers/hook/'. $hook_name.'.php');

		// Build dynamically the controller name
		$controller_name = $this->name.$hook_name.'Controller';

		// Instantiate controller
		$controller = new $controller_name($this, __FILE__, $this->_path);

		// Return the controller
		return $controller;
	}

	public function hookDisplayPayment($params){
		$controller = $this->getHookController('displayPayment');
		return $controller->run($params);
	}

	public function hookDisplayPaymentReturn($params){
		$controller = $this->getHookController('displayPaymentReturn');
		return $controller->run($params);
	}
}
?>