<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2015 PrestaShop SA
 *  @version  Release: 0.4.4
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_'))
	exit;
	
include_once(_PS_MODULE_DIR_.'modulo_tamdis/classes/TamdisLib.php');
include_once(_PS_MODULE_DIR_.'modulo_tamdis/classes/LabelTamdis.php');
include_once(_PS_MODULE_DIR_.'modulo_tamdis/classes/pdft.php');
		
class modulo_tamdis extends Module
{
	public function __construct()
  	{
		$this->name = 'modulo_tamdis';
		$this->tab = 'administration';
		$this->version = '1.0.0';
		$this->author = 'Yo';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
		$this->bootstrap = true;
	 
		parent::__construct();
	 
		$this->displayName = $this->l('Módulo tamdis');
		$this->description = $this->l('Módulo para pruebas de tamdis.');
	 
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	 
		if (!Configuration::get('MODULE_tamdis_UWS_AUT'))      
		  $this->warning = $this->l('No name provided');
  	}
	
	public function install()
	{
	  if (Shop::isFeatureActive())
    	Shop::setContext(Shop::CONTEXT_ALL);	
	  	
	  if (!parent::install() || !$this->registerHook('adminOrder') ||
			!$this->registerHook('orderDetail') || !$this->registerHook('displayOrderConfirmation') || !$this->registerHook('header') || !$this->registerHook('backOfficeHeader'))
		return false;
	  
	  if (!$this->createAdminTab())
		{
			$this->uninstall();
			return false;
		}	
	  return true;
	}
	public function uninstall()
	{
  		if (!parent::uninstall())
    		return false;
 
  		return true;
	}
	
	private function createDatabases()
	{
		/* Create databases */

		$sql = Tools::file_get_contents(dirname(__FILE__).'/sql/install.sql');
		$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array (_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
		$sql = preg_split('/;\s*[\r\n]+/', trim($sql));
			
		foreach ($sql as $query)
			if (!Db::getInstance()->execute(trim($query)))
				return false;

	}
		
	
	private function deleteSettings()
	{
	}
	
	private function createAdminTab()
	{
		$tab = new Tab();

		foreach (Language::getLanguages() as $language)
			$tab->name[$language['id_lang']] = 'Tamdis';

		$tab->class_name = 'AdminFindiaTamdis';

		$tab->module = $this->name;
		$tab->id_parent = (int)Tab::getIdFromClassName('AdminShipping');

		return $tab->add();
	}
	
	private function uninstallTab()
	{
		$id_tab = (int)Tab::getIdFromClassName('FindiaTamdis');

		if ($id_tab)
		{
			$tab = new Tab((int)$id_tab);
			return $tab->delete();
		}

		return true;
	}
	
		
	public function displayForm()
	{
		// Get default language
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		 
		// Init Fields form array
		$fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Configuration'),
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('URL Authentication: '),
					'name' => 'url_authentication',
					'desc' => $this->l('Introduce la URL de la API de autenticacion'),
					'size' => 150,
					'required' => true
				),
			//),
			//'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('URL Labels: '),
					'name' => 'url_label',
					'desc' => $this->l('Introduce la URL de la API de etiquetas'),
					'size' => 150,
					'required' => true
				),
			//),
			//'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('User API: '),
					'name' => 'user',
					'desc' => $this->l('Introduce el USER de la API'),
					'size' => 150,
					'required' => true
				),
			//),
			//'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('PASS API: '),
					'name' => 'password',
					'desc' => $this->l('Introduce la PASS de la API'),
					'size' => 150,
					'required' => true
				),
				array(
					'type' => 'text',
					'label' => $this->l('ACCOUNT DHL: '),
					'name' => 'n_account',
					'desc' => $this->l('Introduce la CUENTA de DHL'),
					'size' => 150,
					'required' => true
				)
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'button'
			)
		);
		 
		$helper = new HelperForm();
		 
		// Module, token and currentIndex
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		 
		// Language
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		 
		// Title and toolbar
		$helper->title = $this->displayName;
		$helper->show_toolbar = true;        // false -> remove toolbar
		$helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
		$helper->submit_action = 'submit'.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValue(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);	
		return $helper->generateForm($fields_form);
	}
	
		
	public function hookAdminOrder($params)
	{
		$order = new Order((int)$params['id_order']);
		
		if (!TamdisLib::getdataOrdert((int)$order->id) && Tools::getValue('npackagestam')=='' && Tools::getValue('weighttam')==''){
			$order_data = TamdisLib::getdataInicio((int)$order->id);
			
		}else{
			if (Tools::getValue('npackagestam') && Tools::getValue('weighttam')){
				TamdisLib::setdataOrdert((int)$order->id);
				$order_data = TamdisLib::getdataOrdert((int)$order->id);
			}else{
				$order_data = TamdisLib::getdataOrdert((int)$order->id);
			}
		}
		
		$back = Tools::safeOutput($_SERVER['REQUEST_URI']);
		$products = $order->getProductsDetail();
		$customer = new Customer((int)$order->id_customer);
		$address_delivery = new Address((int)$order->id_address_delivery, (int)$cookie->id_lang);
		$post_code = $address_delivery->postcode;
		$name = $address_delivery->firstname;
		$apellidos=$address_delivery->lastname;
		$direccion = $address_delivery->address1.' '.$address_delivery->address2;
		$newcountry = new Country((int)$address_delivery->id_country, (int)$cookie->id_lang);
		
		$label_data = array(
						'pedido' => sprintf('%06d', (int)$order->id),
						'cliente' => (int)$order->getIdCustomer(),
						'total_bultos' => $order_data['n_packages'],
						'total_kilos' => $order_data['weight'],
						'direccion' => $direccion,
						'city' => $address_delivery->city,
						'CodPost' => $post_code,
						'telefon' => $address_delivery->phone_mobile,
						'telefon2' => $address_delivery->phone,
						'name' => $name,
						'lastname' =>$apellidos,
						'companyia' => (!empty($address_delivery->company) ? $address_delivery->company : ''),
						'email' => Validate::isLoadedObject($customer) ? $customer->email : '',
						'dni' => $address_delivery->dni,
						'info_adicional' => $info_adicional_str,
						'country' => $newcountry->name,
						'iso' => $newcountry->iso_code,
						'id_employee' => $cookie->id_employee,
						'total' => $order->getTotalPaid(),
						'back' => $back,
						'merchantdata' =>TamdisLib::getMerchantData(),
						'order_data' => $order_data,
						'products' => $products
		);
					
		$lastpayment ='';
		$price=0;
		foreach ($order->getOrderPaymentCollection() as $payment)
		{
			$lastpayment = $payment->payment_method;
			if (strcmp($lastpayment, 'Contrareembolso') == 0 || strcmp($lastpayment, 'Pago Contrareembolso') == 0)
			{
				$price=$price + $payment->amount;
			}
		}
		
		$label_data['reembolso'] = (float)$price;
		
		
		if (Tools::getValue('Labeltamdis'))
		{
			if ($this->isPrinted((int)$order->id)){
				$success = true;
			}else{
				$success = LabelTamdis::createLabels($label_data);
			}
			if ($success === true)
			{
				if (!$this->setAsPrinted((int)$order->id))
					$this->context->smarty->assign('error', $this->l('Could not set printed value for this order'));
				else{
					$this->printFile($label_data['name'].'_'.$label_data['lastname'],$label_data);
				}
			}
			else
				$this->context->smarty->assign('error', $success);
		}
		
		//$sq="Select * from ps_megaproductcart where id_cart=".$order->id_cart." and id_product=2678 and attributes like '%2104%'" ;
		$carrier = new carrier($order->id_carrier);
		if ($carrier->id_reference == 213){
		//if ($order->id_carrier==214){
			$montag=true;
		}
		
		$path = '../modules/modulo_tamdis/';
		$this->context->smarty->assign(array(
				'request_uri' => $_SERVER['REQUEST_URI'],
				'order_data' => $order_data,
				'path' => $path,
				'montag' => $montag
		));
		return $this->display(__FILE__, 'views/templates/admin/orders.tpl');
		
	}
		
	public function hookBackOfficeHeader($params)
	{
		$tab = version_compare(_PS_VERSION_, '1.5', '<') ? Tools::strtolower(Tools::getValue('tab')) : Tools::strtolower(Tools::getValue('controller'));

		$this->context->controller->addJQuery();
		$this->context->controller->addJqueryPlugin('fancybox');

		$this->context->controller->addCSS($this->_path.'css/modulo_tamdis.css');		
	}
	
	private function isPrinted($id_order, $label = false)
	{
		$field = 'printed';

		return DB::getInstance()->getValue('
			SELECT `'.bqSQL($field).'`
			FROM `'._DB_PREFIX_.'tamdis_order`
			WHERE `id_order` = "'.(int)$id_order.'"
		');
		
	}

	private function setAsPrinted($id_order, $label = false)
	{
		$field = 'printed';

		return DB::getInstance()->Execute('
			UPDATE `'._DB_PREFIX_.'tamdis_order`
			SET `'.bqSQL($field).'` = 1
			WHERE `id_order` = "'.(int)$id_order.'"
		');
	}
	
	private function printFile($name, $datos)
	{	
		
		$directory = _PS_MODULE_DIR_.'modulo_tamdis/files/deliveries_labels/';
		$namep = $name.'_'.date('Ymd');		
		
		if (file_exists($directory.$namep.'.pdf') && ($fp = Tools::file_get_contents($directory.$namep.'.pdf')))
			{
				ob_end_clean();
				header('Content-type: application/pdf');
				//header('Content-Disposition: inline; filename='.$namep.'.pdf');
				header('Content-Disposition: attachment; filename='.$namep.'.pdf');
				header('Content-Transfer-Encoding: binary');
				header('Accept-Ranges: bytes');
	
				echo $fp;
				exit;
			}
		
	}
	
	
}	

