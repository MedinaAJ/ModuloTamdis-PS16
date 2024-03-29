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

require_once(_PS_MODULE_DIR_.'modulo_tamdis/FindiaTamdis.php');

class AdminFindiaTamdisController extends ModuleAdminController {

	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function initContent()
	{	
		$admin_tamdis = new FindiaTamdis(false);

		if (!$admin_tamdis->module_enabled_and_configured)
		{
			parent::initContent();
			$this->content = $this->displayModuleConfigurationWarning();
			return parent::initContent();
		}
		else
		{
			$this->assignTplVars();
			
			$this->content .= $this->context->smarty->fetch(_PS_MODULE_DIR_.'modulo_tamdis/views/templates/admin/FindiaTamdis.tpl');
		}
		
		parent::initContent();
	}
	
	 public function postProcess() {
		 
	 	if (Tools::getValue('submitDescargarAlbaran')){
			echo 'hola';
			$dir=_PS_MODULE_DIR_ . "modulo_tamdis/";
			$account = '4549';
			$fechaini = date('Y-m-d'). ' 00:00:00';
			//$fechaini = '2017-11-15 00:00:00';
			$fechafin = date('Y-m-d'). ' 23:59:59';
			//$fechafin = '2017-11-27 23:59:59';
			$sql='SELECT o.id_order as pedido ,d.firstname as nombre ,d.lastname as apellidos ,d.address1 as direccion ,d.city as poblacion ,d.postcode as codpostal ,dho.n_packages as bultos ,dho.weight as peso ,dho.paid_ondelivery as reembolso ,o.total_paid as total FROM ' . _DB_PREFIX_ . 'tamdis_order dho,' . _DB_PREFIX_ . 'orders o,' . _DB_PREFIX_ . 'address d WHERE dho.id_order=o.id_order AND o.id_address_delivery=d.id_address AND date_printed>="' . $fechaini . '" AND date_printed<="'. $fechafin.'"';
			echo '<br/>$sql: '.$sql;
            $response = DB::getInstance()->ExecuteS($sql);

            if (count($response) > 0) {
				try {
		 			
					$albaran = null;
					echo '<br/>dir: '.$dir;
					require_once($dir . "pdftamdis/tamdis_albaran.php");
					$albaran = new Albaran(Configuration::get('PS_SHOP_NAME'));
					echo '<br/>';
		 			foreach ($response as $respons) {				
						echo '<br/>linea';
						$albaran->addLine($account, $respons["pedido"], '',$respons["nombre"]. ' ' .$respons["apellidos"], $respons["direccion"], $respons["poblacion"], $respons["codpostal"], $respons["bultos"], $respons["peso"], "", round($respons["reembolso"],2), round($respons["total"],2), "");
						
		 			}
		 			$albaran->Output($dir . "files_generated/albaran.pdf", "F");
		 			
					$this->imprimeArchivo($dir . 'files_generated');
					
		 		}
		 		catch (Exception $exc) {
		 			return self::displayError_tamdis("Error devuelto por el servidor: " . $exc->getMessage() . "<br/>");
		 		}
				
			
			}
		}
	 }
	 
	 function imprimeArchivo($dir) {
		$file = $dir . "/albaran.pdf";
		header("Content-Disposition: attachment; filename=Albaran.pdf");
		header("Content-Type: application/pdf");
		readfile($file);
    	exit;
	}
	
	public function assignTplVars($response = null, $detail = null, $error = null)
	{
		$params = array();
		$params['token'] = Tools::getValue('token');
		$params['back'] = Tools::safeOutput($_SERVER['REQUEST_URI']);
		$params['modulo_tamdis_order_states'] = array(
			'' => $this->l('All'),
			'1' => $this->l('Delivered'),
			'2' => $this->l('In transit'),
			'3' => $this->l('Incidents fixable by customer'),
			'4' => $this->l('Incident management modulo_tamdis'),
			'5' => $this->l('Returned'),
			'6' => $this->l('Sinister'),
			'7' => $this->l('Canceled')
		);
		Tools::safePostVars();
		if (empty($_POST))
		{
			$delivery_valuend_data = date('d-m-Y');
			$start_data = strtotime('-1 day', strtotime(date('Y-m-d')));
			$start_data = date('d-m-Y', $start_data);
		}
		else
		{
			$start_data = Tools::getValue('start_date');
			$delivery_valuend_data = Tools::getValue('end_date');
		}
		$params['delivery_valuend_data'] = $delivery_valuend_data;
		$params['start_data'] = $start_data;
		if ($response == null && $detail == null)
			$tab_view = 'deliveries';
		elseif ($response == true && $detail == null)
			$tab_view = 'deliveries';
		elseif ($response == true && $detail == true)
			$tab_view = 'deliveries';
		
		$params['tab_view'] = $tab_view;
		$params['ps_version'] = 'ps'.(version_compare(_PS_VERSION_, '1.5', '>=') > 1.4 ? '5' : '4');
		$params['img_dir'] = __PS_BASE_URI__.'modules/modulo_tamdis/img/';
		$errors = '';
		if (!empty($error))
			$errors .= $error;

		if (Tools::getValue('error'))
			$errors .= Tools::getValue('codigo').' => '.Tools::getValue('error');

		if (_PS_VERSION_ > '1.5')
		{
			$ps14_tab = '';
			$ps15 = true;
		}
		else
		{
		$ps14_tab = '&tab=FindiaTamdis';
		$ps15 = false;
		}
		$params['ps15'] = $ps15;
		$params['ps16'] = (int)(version_compare(_PS_VERSION_, '1.6', '<'));
			
		if (($response == true) && ($detail == null))
		{
			$string_xml = htmlspecialchars_decode($response->out);
			$string_xml = str_replace('&', '&amp; ', $string_xml);
			$xml = simplexml_load_string($string_xml);

			if ($xml->DESCRIPCION)
				$errors .= $xml->DESCRIPCION;
			else
			{
				if ($xml->attributes()->NUM[0] != 0)
				{
					$deliveries_data = array();

					foreach ($xml->EXPEDICION as $delivery)
					{
						$headers = array(
							'order' => $this->l('Order/Reference'),
							'expedition' => $this->l('Expedition'),
							'name' => $this->l('Name'),
							'description' => $this->l('Description'),
							'date' => $this->l('Date'),
							'delivery' => $this->l('Delivery'),
							'details' => $this->l('Details')
							);

						$headersOcultas = array('EXPEDICION','DESTINA_PAIS' => (string)$delivery->DESTINA_PAIS);

						$deliveries_data[] = array(
							'Pedido/Referencia' => (string)$delivery->REMITE_REF,
							'Expedicion' => (string)$delivery->EXPEDICION_NUM,
							'Nombre' => (string)$delivery->DESTINA_NOMBRE,
							'Descripcion' => (string)$delivery->DESCRIPCION_PARA_CLIENTE,
							'date' => (string)$delivery->FECHA_CAPTURA,
							'EXPEDICION' => (string)$delivery->EXPEDICION_NUM,
							'Detalles' => '',
						);
					}

				}
				else
					$errors .= $this->l('No results.');
			}
		}
		
		$params['ps_order_states'] = $this->getPsOrderStates();
		
		
		$params['headers'] = (isset($headers) && is_array($headers)) ? $headers : false;
		$params['headersOcultas'] = (isset($headersOcultas) && is_array($headersOcultas)) ? $headersOcultas : false;
		//$params['print_type'] = (int)TamdisLib::getConfigurationField('print_type');
		$params['ps14_tab'] = $ps14_tab;
		$params['ps_base_uri'] = _MODULE_DIR_;
		$params['orders'] = $this->getAllOrders();
		$params['id_employee'] = $this->context->employee->id;
		$params['deliveries_data'] = (isset($deliveries_data) && is_array($deliveries_data)) ? $deliveries_data : false;
		$params['current_controller'] = $this->context->controller->controller_name;
		$params['errors'] = $errors;
		$params['pickup_data'] = Pickup::getLastPickup();
		//$params['steady_pickup'] = (bool)TamdisLib::getConfigurationField('pickup');
		$params['countryTo'] = '';
		$params['img_path'] = _MODULE_DIR_.'/modulo_tamdis/img/';
		
		$params['ID'] = Tools::getValue('ID', '');
		$params['reference'] = Tools::getValue('reference', '');
		$params['start_date'] = Tools::getValue('start_date', '');
		$params['end_date'] = Tools::getValue('end_date', '');
		$params['firstname'] = Tools::getValue('firstname', '');
		$params['lastname'] = Tools::getValue('lastname', '');
		$params['state'] = Tools::getValue('state', '');
		$params['postcode'] = Tools::getValue('postcode', '');
		$params['city'] = Tools::getValue('city', '');
		$params['country'] = Tools::getValue('country', '');
		$params['address'] = Tools::getValue('address', '');
		$params['order_state'] = Tools::getValue('order_state', '');
		
		$this->context->smarty->assign($params);
	}
	
	public static function limpiaCarpeta($dir) {
		 $carpeta = opendir($dir);
		 while ($dirpart1 = readdir($carpeta)) {
		 if ($dirpart1 != "." AND $dirpart1 != "..") unlink($dir . "/" . $dirpart1);
		 }
		 closedir($carpeta);
		 rmdir($dir);
	 }
	
	public function displayModuleConfigurationWarning()
	{
		if (!version_compare(_PS_VERSION_, '1.5', '<'))
			$this->context->smarty->assign('ps_14', true);

		if (!version_compare(_PS_VERSION_, '1.6', '<'))
			$this->context->smarty->assign('ps_16', true);

		$this->context->smarty->assign(array(
			'modulo_tamdis_warning_message' => $this->l('Please, first configure your modulo_tamdis module as a merchant.'),
			'module_instance' => $this->module,
		));
		return $this->context->smarty->fetch(_PS_MODULE_DIR_.'modulo_tamdis/views/templates/admin/warning_message.tpl');
	}
	
	public function getExpeditionData()
	{
		$expedition_data = array();
		Tools::safePostVars();

		if (Tools::isSubmit('start_date'))
			$expedition_data['start_date'] = Tools::getValue('start_date');

		if (Tools::isSubmit('end_date'))
			$expedition_data['end_date'] = Tools::getValue('end_date');

		if (Tools::isSubmit('expedition_number'))
			$expedition_data['expedition_number'] = Tools::getValue('expedition_number');

		if ((Tools::isSubmit('reference_number')) && (Tools::getValue('reference_number')) > 0)
			$expedition_data['reference_number'] = sprintf('%06d', Tools::getValue('reference_number'));
		else
			$expedition_data['reference_number'] = '';

		if (Tools::isSubmit('order_state'))
			$expedition_data['order_state'] = Tools::getValue('order_state');
		return $expedition_data;
	}
	
	public function getAllOrders($id = null, $reference = null, $date_start = null, $date_end = null, $name = null, $address = null, $postal_code = null, $city = null, $state_name = null, $country = null, $order_state = null)
	{
		$sql = 'SELECT o.reference,o.id_order,o.date_add,o.reference, a.firstname, a.lastname, a.address1, a.address2, a.postcode, a.city, cl.name as country, s.name as state, os.name as current_state FROM '._DB_PREFIX_.'orders o INNER JOIN '._DB_PREFIX_.'tamdis_order so ON so.id_order  = o.id_order  INNER JOIN '._DB_PREFIX_.'address a ON o.id_address_delivery = a.id_address INNER JOIN '._DB_PREFIX_.'country_lang cl ON cl.id_country = a.id_country AND cl.id_lang ='.(int)$this->context->language->id.' LEFT JOIN '._DB_PREFIX_.'state s ON s.id_state = a.id_state LEFT JOIN '._DB_PREFIX_.'order_state_lang os ON os.id_order_state=o.current_state AND os.id_lang='.(int)$this->context->language->id;
		$where = array();
		if ((int)$id > 0)
			$where[] = ' o.id_order ='.(int)$id.' ';
		
		if (trim($reference) != '')
			$where[] = ' o.reference like \''.pSQL($reference).'\' ';
		
		
		if (trim($date_start) != '' && trim($date_end) != '')
			$where[] = ' o.date_add between \''.pSQL($date_start).'\' AND   \''.pSQL($date_end).'\' ';
			
		if (trim($date_start) != '' && trim($date_end) == '')
			$where[] = ' o.date_add >= \''.pSQL($date_start).'\'  ';
			
		if (trim($date_start) == '' && trim($date_end) != '')
			$where[] = ' o.date_add <= \''.pSQL($date_start).'\'  ';
			
		if (trim($name) != '')
			$where[] = ' ( a.firstname like = \'%'.pSQL($name).'%\'  OR a.lastname like = \'%'.pSQL($name).'%\'  )  ';
		
		if (trim($address) != '')
			$where[] = ' ( a.address1 like = \'%'.pSQL($address).'%\'  OR a.address2 like = \'%'.pSQL($address).'%\'  )  ';
		
		
		if (trim($postal_code) != '')
			$where[] = ' a.post_code like = \'%'.pSQL($postal_code).'%\'   ';
		
		if (trim($city) != '')
			$where[] = ' a.city like = \'%'.pSQL($city).'%\'   ';
		
		if (trim($state_name) != '')
			$where[] = ' a.state like = \'%'.pSQL($state_name).'%\'   ';
		
		if (trim($country) != '')
			$where[] = ' a.country like = \'%'.pSQL($country).'%\'   ';
	
		if (trim($order_state) != '')
			$where[] = ' os.id_order_state = '.pSQL($order_state).'   ';
		
		if (!empty($where))
			$sql .= ' WHERE '.implode(' AND ', $where);
			
		$sql .= ' ORDER BY o.id_order DESC';
		//echo $sql;
		return DB::getInstance()->executeS($sql);
	}
	
	public function printLabels($id_orders = array(), $type = 'txt')
	{
		try
		{
			ob_end_clean();
			header('Content-type: text/plain');
			header('Content-Disposition: attachment; filename=tamdis_labels-'.date('Y-m-d h:i:s', strtotime('now')).'.txt');
			header('Content-Transfer-Encoding: binary');
			header('Accept-Ranges: bytes');
				
			if (!is_array($id_orders)) $id_orders = (array)$id_orders;
				$directory = _PS_MODULE_DIR_.'modulo_tamdis/files/deliveries_labels/';
			
			if ($type == 'txt')
			{

				if (is_array($id_orders))
					foreach ($id_orders as $id_order)
					{
						$name = sprintf('%06d', (int)$id_order);
						if (!file_exists($directory.$name.'.txt') || !($fp = Tools::file_get_contents($directory.$name.'.txt')))
						{
										
								$data_label = $this->getLabelData($id_order);
								if (is_array($data_label))
								{
									$success = Label::createLabels($data_label, 'zebra');
									if ($success === true)
										$this->setAsPrinted((int)$id_order, true);		
							}
						}
					}
				if (is_array($id_orders))
					foreach ($id_orders as $id_order)
					{
						$name = sprintf('%06d', (int)$id_order);

						if (file_exists($directory.$name.'.txt') && ($fp = Tools::file_get_contents($directory.$name.'.txt')))
						{
							
							echo $fp;				
						}
					}
			}
			elseif ($type == 'pdf')
			{
				if (file_exists($directory.$name.'.pdf') && ($fp = Tools::file_get_contents($directory.$name.'.pdf')))
				{
					ob_end_clean();
					header('Content-type: application/pdf');
					header('Content-Disposition: inline; filename='.$name.'.pdf');
					header('Content-Transfer-Encoding: binary');
					header('Accept-Ranges: bytes');

					echo $fp;
				}
			}
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		exit;	
		$this->context->smarty->assign('error', $this->l('Document was already printed, but is missing in module directory', 'FindiaTamdis15'));
	}
	
	private function isPrinted($id_order, $label = false)
	{
		$field = $label ? 'printed_label' : 'printed_pdf';

		return DB::getInstance()->getValue('
			SELECT `'.bqSQL($field).'`
			FROM `'._DB_PREFIX_.'seur_order`
			WHERE `id_order` = "'.(int)$id_order.'"
		');
	}
	
	private function setAsPrinted($id_order, $label = false)
	{
		$field = $label ? 'printed_label' : 'printed_pdf';

		return DB::getInstance()->Execute('
			UPDATE `'._DB_PREFIX_.'seur_order`
			SET `'.bqSQL($field).'` = 1
			WHERE `id_order` = "'.(int)$id_order.'"
		');
	}
	
	private function getLabelData($id_order)
	{
		$label_data = false;
		$cookie = $this->context->cookie;
		$order = new Order($id_order);
		$customer = new Customer($order->id_customer);
		$address_delivery = new Address($order->id_address_delivery);
		
		//$carrier_pos = TamdisLib::getTamdisCarrier('SEP');
		$datospos = '';
		$products = $order->getProductsDetail();
		$order_weigth = 0;
		foreach ($products as $product)
				$order_weigth += (float)$product['product_weight'] * (float)$product['product_quantity'];

		$order_weigth = ($order_weigth < 1.0 ? 1.0 : (float)$order_weigth);
		$name = $address_delivery->firstname.' '.$address_delivery->lastname;
		$direccion = $address_delivery->address1.' '.$address_delivery->address2;
		$newcountry = new Country((int)$address_delivery->id_country, (int)$cookie->id_lang);
		$iso_merchant = TamdisLib::getMerchantField('country');
		$iso_country = Country::getIsoById((int)$address_delivery->id_country);
			if ($iso_country == 'PT')
			{
				$post_code = explode(' ', $address_delivery->postcode);
				$post_code = $post_code[0];
			}
			else
				$post_code = $address_delivery->postcode;

			$international_orders = TamdisLib::getConfigurationField('international_orders');
			$date_calculate = strtotime('-14 day', strtotime(date('Y-m-d')));
			$date_display = date('Y-m-d H:m:i', $date_calculate);
	
		if ((!$international_orders && !($iso_country == 'ES' || $iso_country == 'PT' || $iso_country == 'AD')))	
			return false;
		
		$order_data = TamdisLib::getOrderData((int)$order->id);
		$response_post_code = Town::getTowns($post_code);
		$order_weigth = ((float)$order_weigth != $order_data['peso_bultos'] ? (float)$order_data['peso_bultos'] : (float)$order_weigth);
	
		if ((int)$order->id_carrier == $carrier_pos['id'])
		{
			$datospos = TamdisLib::getOrderPos((int)$order->id_cart);
			if (!empty($datospos))
			{
				$label_data = array(
					'pedido' => sprintf('%06d', (int)$order->id),
					'total_bultos' => $order_data['numero_bultos'],
					'total_kilos' => (float)$order_weigth,
					'direccion_consignatario' => $direccion,
					'consignee_town' => $datospos['city'],
					'codPostal_consignatario' => $datospos['postal_code'],
					'telefono_consignatario' => (!empty($address_delivery->phone_mobile) ? $address_delivery->phone_mobile : $address_delivery->phone),
					'movil' => $address_delivery->phone_mobile,
					'name' => $name,
					'companyia' => $datospos['company'],
					'email_consignatario' => Validate::isLoadedObject($customer) ? $customer->email : '',
					'dni' => $address_delivery->dni,
					'info_adicional' => $info_adicional_str,
					'country' => $newcountry->name,
					'iso' => $newcountry->iso_code,
					'cod_centro' => $datospos['id_Tamdis_pos'],
					'iso_merchant' => $iso_merchant
				);
				$rate_data['cod_centro'] = $datospos['id_Tamdis_pos'];
			}
		}
		else
		{
			$label_data = array(
			'pedido' => sprintf('%06d', (int)$order->id),
			'total_bultos' => $order_data['numero_bultos'],
			'total_kilos' => (float)$order_weigth,
			'direccion_consignatario' => $direccion,
			'consignee_town' => $address_delivery->city,
			'codPostal_consignatario' => $post_code,
			'telefono_consignatario' => (!empty($address_delivery->phone_mobile) ? $address_delivery->phone_mobile : $address_delivery->phone),
			'movil' => $address_delivery->phone_mobile,
			'name' => $name,
			'companyia' => (!empty($address_delivery->company) ? $address_delivery->company : ''),
			'email_consignatario' => Validate::isLoadedObject($customer) ? $customer->email : '',
			'dni' => $address_delivery->dni,
			'info_adicional' => $info_adicional_str,
			'country' => $newcountry->name,
			'iso' => $newcountry->iso_code,
			'iso_merchant' => $iso_merchant,
			'admin_dir' => utf8_encode(_PS_ADMIN_DIR_),
			'id_employee' => $cookie->id_employee,
			'token' => Tools::getAdminTokenLite('AdminOrders'),
			'back' => $back
		);
		
		}
		if (strcmp($order->module, 'Tamdiscashondelivery') == 0)
			$label_data['reembolso'] = (float)$order_data['total_paid'];
	
		return $label_data;
	}
	
	public function setMedia()
	{
		parent::setMedia();
		$this->addCSS(_MODULE_DIR_.'modulo_tamdis/css/modulo_tamdis.css');
		$this->addJQueryUI('ui.datepicker');
	}
	
	public function getPsOrderStates()
	{
		return DB::getInstance()->ExecuteS('SELECT id_order_state, name FROM `'._DB_PREFIX_.'order_state_lang` WHERE `id_lang` = '.(int)$this->context->language->id);
	}
}
