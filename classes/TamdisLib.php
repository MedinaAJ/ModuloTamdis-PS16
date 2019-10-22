<?php
if (!defined('_PS_VERSION_'))
	exit;
	
class TamdisLib{
	
	public static function getdataOrdert($id_order){
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT *
			FROM `'._DB_PREFIX_.'tamdis_order`
			WHERE `id_order` ='.(int)$id_order
			);		
	}
	
	public static function getdataInicio($id_order){
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT o.id_order, 0 as platform_tamdis,"" as name_p,"" as address_p,"" as city_p,"" as state_p,"" as postel_p,"" as phone1_p,"" as phone2_p,"" as mail_p, oc.weight as weight,1 as n_packages,34 as country
			FROM `'._DB_PREFIX_.'orders` as o,`'._DB_PREFIX_.'order_carrier` as oc
			WHERE o.id_order=oc.id_order AND o.id_order ='.(int)$id_order
			);		
	}
	
	public static function setdataOrdert($id_order){
		$order = new Order((int)$id_order);
		$exists = TamdisLib::getdataOrdert($id_order);
			if ($exists){
				$sq= "UPDATE "._DB_PREFIX_."tamdis_order
					SET `platform_tamdis`='".(int)Tools::getValue('platform')."', `name_p`='".Tools::getValue('namepickup')."', `address_p`='".Tools::getValue('addresspickup')."',`city_p`='".Tools::getValue('citypickup')."',`postel_p`='".Tools::getValue('cppickup')."',`state_p`='".Tools::getValue('statepickup')."',`phone1_p`='".Tools::getValue('phone1pickup')."',`phone2_p`='".Tools::getValue('phone2pickup')."',`mail_p`='".Tools::getValue('mailpickup')."',`n_packages`='".(int)Tools::getValue('npackagestam')."', `weight`='".str_replace(',', '.', (float)Tools::getValue('weighttam'))."'
					WHERE `id_order` =".(int)$id_order;
				echo 'sq: ' . $sq;
				return Db::getInstance()->Execute($sq);
					
			}else{	
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
				 $sq = "INSERT INTO `"._DB_PREFIX_."tamdis_order` (id_order, platform_tamdis, name_p, address_p, city_p, state_p, postel_p, phone1_p, phone2_p, mail_p, weight, n_packages, country, paid_ondelivery)  
					VALUES (".(int)$id_order.", '".(int)Tools::getValue('platform')."', '".Tools::getValue('namepickup')."', '".Tools::getValue('addresspickup')."', '".Tools::getValue('citypickup')."', '".Tools::getValue('statepickup')."', '".Tools::getValue('cppickup')."', '".Tools::getValue('phone1pickup')."', '".Tools::getValue('phone2pickup')."', '".Tools::getValue('mailpickup')."', '".str_replace(',', '.', (float)Tools::getValue('weighttam'))."', '".(int)Tools::getValue('npackagestam')."', 34, '".(float)$price."');";
				 echo 'sq: '.$sq;
					 
				 return Db::getInstance()->Execute($sq);
			}
	}
	
	public static function getMerchantData()
	{
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT *
			FROM `'._DB_PREFIX_.'dhl_merchant`
			WHERE `id_dhl_datos` = 1'
		);
	}
	
	public static function setOrderImprimido($id_order)
	{
		$exists = self::getdataOrdert($id_order);
		if ($exists){
							
			return Db::getInstance()->Execute("
				UPDATE "._DB_PREFIX_."tamdis_order
				SET `printed`=1, `date_printed`=NOW()
				WHERE `id_order` =".(int)$id_order
				);
		}
	}
	
	//****************************************************************************************************************
	public static function stripAccents($string){//**** Quitar acentos *****
	//****************************************************************************************************************
		$tofind = "ÀÁÂÄÅàáâäÒÓÔÖòóôöÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
        $replac = "AAAAAaaaaOOOOooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
        return utf8_encode(strtr(utf8_decode($string), utf8_decode($tofind), $replac));
	}
}

?>