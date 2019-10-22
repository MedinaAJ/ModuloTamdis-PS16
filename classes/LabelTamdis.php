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

//include_once('pdf.php');

class LabelTamdis
{
	public static function createLabels($label_data)
	{
		try
		{
			//echo '<br>ESTOY AQUI';
			if (Validate::isFileName($label_data['pedido'])){
				//$label_name = $label_data['pedido'];
				$label_name = $label_data['name']."_".$label_data['lastname']."_".date('Ymd');
			}else
			{
				$module_instance = Module::getInstanceByName('modulo_tamdis');
				return TamdisLib::displayErrors($label_data['pedido'].' '.$module_instance->l('could not be used as file name', 'Label'));
			}
			
			$claveReembolso = '';
			$valorReembolso = '';
			
			$imprimido=false;
			$parceltype="SMALL";
			$pdfimp="";
			
			
			if (isset($label_data['reembolso']) && ($label_data['iso'] == 'ES' || $label_data['iso'] == 'PT' || $label_data['iso'] == 'AD'))
			{
				$claveReembolso = 'f';
				$valorReembolso = (float)$label_data['reembolso'];
			}

			$total_weight = $label_data['total_kilos'];
			$total_packages = $label_data['total_bultos'];
			$pesoBulto = $total_weight / $total_packages;
 
						
			
				
				
			$merchant_data = $label_data['merchantdata'];
				
			$directory = _PS_MODULE_DIR_.'modulo_tamdis/files/deliveries_labels/';
	
		
			$order_data=$label_data['order_data'];
			$products= $label_data['products'];
			
			$plataforma='';
			if ($order_data['platform_tamdis']!=0)
				$plataforma=$order_data['platform_tamdis'];
								
			$sfile = utf8_encode('E;'.$label_data['pedido'].';'.$label_data['pedido'].'-'.$label_data['cliente'].';4549;;;;;;;;;;4549;;;;;;;'.$plataforma.';;'.$order_data['name_p'].';'.$order_data['name_p'].';'.$order_data['address_p'].';'.$order_data['city_p'].';'.$order_data['state_p'].';'.$order_data['postel_p'].';34;'.$order_data['phone1_p'].';'.$order_data['phone2_p'].';'.$order_data['mail_p'].';'.$label_data['cliente'].';'.$label_data['name'].';'.$label_data['lastname'].';'.$label_data['direccion'].';'.$label_data['city'].';;'.$label_data['CodPost'].';34;;'.$label_data['telefon'].';'.$label_data['telefon2'].';'.$label_data['email'].';0;'.$label_data['total_kilos'].';;'.$label_data['total_bultos'].';;'.$label_data['reembolso'].';;;;1;2;2;2;2;2;2;2;2;2;;1;3;2;;;;;;;');		
			
			
			if (is_writable($directory)){
				$imprimido = true;
				
				//file_exists($directory.$label_name.'.txt');
				file_exists($directory.$label_name.'.ent');
				
				//$file = fopen($directory.$label_name.'.txt', "w");
				$file = fopen($directory.$label_name.'.ent', "w");

				//fwrite($file, $sfile . PHP_EOL);
				fwrite($file, $sfile);
				fputs($file,chr(13).chr(10));
				
				foreach ($products as $row) {
					$vol='0';
					if (strpos(strtolower(TamdisLib::stripAccents($row['product_name'])),'canape')!==false){
						$vol='0,85';
					}elseif(strpos(strtolower(TamdisLib::stripAccents($row['product_name'])),'colchon')!==false){
						$vol='0,65';
					}elseif(strpos(strtolower(TamdisLib::stripAccents($row['product_name'])),'cabecero')!==false){
						$vol='0,2';
					}elseif(strpos(strtolower(TamdisLib::stripAccents($row['product_name'])),'base')!==false){
						$vol='0,2';
					}elseif(strpos(strtolower(TamdisLib::stripAccents($row['product_name'])),'somier')!==false){
						$vol='0,2';
					}
					
					$sfile2 = utf8_encode('A;'.$label_data['pedido'].';'.$row['product_reference'].';'.$row['product_quantity'].';;'.$row['product_name'].';'.(float)$row['product_weight'].';'.$vol.';'.(float)$row['unit_price_tax_incl'].';;;;;;;;;1;');
					//fwrite($file, $sfile2 . PHP_EOL);
					fwrite($file, $sfile2);
					fputs($file,chr(13).chr(10));
				}
				
				for ($i=1;$i<=$label_data['total_bultos'];$i++){
					$sfile3 = utf8_encode('B;'.$label_data['pedido'].';'.$label_data['pedido'].'B0'.$i);
					//fwrite($file, $sfile3 . PHP_EOL);
					fwrite($file, $sfile3);
					fputs($file,chr(13).chr(10));
				}
				
				
				fclose($file);
				
				//Creación del objeto de la clase heredada
				$pdf=new PDFT();
				$pdf->AliasNbPages();
				//Primera página
				/*$pdf->AddPage();
				$pdf->SetFont('Arial','',15);
				$pdf->Cell(40,20);
				$pdf->Write(5,'Para ir a la página 2, pulse ');
				$pdf->SetFont('','U');
				$link=$pdf->AddLink();
				$pdf->Write(5,'aquí',$link);
				$pdf->SetFont('');
				//Segunda página
				$pdf->AddPage();
				$pdf->SetLink($link);
				//Tercerara página
				$pdf->AddPage();*/
				//Títulos de las columnas
				//$header=array('Columna 1','Columna 2','Columna 3','Columna 4');
				for ($i=1;$i<=$label_data['total_bultos'];$i++){
					$pdf->SetY(65);
					$pdf->AddPage();
					$pdf->Ln();
					$pdf->Ln();
					$pdf->Ln();
					//$pdf->TablaBasica($header);
					$pdf->Cell(40,8,"Id pedido: ",1);
				    $pdf->Cell(60,8,$label_data['pedido'],1);
				    $pdf->Ln();
				    $pdf->Cell(40,8,"",1);
				    $pdf->Cell(60,8,$label_data['name'].' '.$label_data['lastname'],1);
				    $pdf->Ln();
				    $pdf->Cell(40,8,"",1);
				    $pdf->Cell(60,8,$label_data['direccion'],1);
				    $pdf->Ln();
				    $pdf->Cell(40,8,"",1);
				    $pdf->Cell(60,8,$label_data['CodPost'].' '.$label_data['city'],1);
				    $pdf->Ln();
				    $pdf->Cell(40,8,"",1);
				    $pdf->Cell(60,8,$label_data['Country'],1);
				    $pdf->Ln();
				    $pdf->Cell(40,8,"",1);
				    $pdf->Cell(60,8,$label_data['telefon'].' '.$label_data['telefon2'],1);
				    $pdf->Ln();
				    $pdf->Cell(40,8,"Bulto: ",1);
				    $pdf->Cell(60,8,$label_data['pedido'].'B0'.$i,1);
				}
				$pdf->Output($directory.$label_name.'.pdf');
				
			}
							
				
			if ($imprimido){	
								
				$servidor_ftp = "ftp.tamdis.com";
				$conexion_id = ftp_connect($servidor_ftp);
				$ftp_usuario = "ventacolchones";
				$ftp_clave = "colchonesventa";
				$ftp_carpeta_local =  $directory;
				$ftp_carpeta_remota = "/";
				$mi_nombredearchivo = $label_name.'.ent';
				$nombre_archivo = $ftp_carpeta_local.$mi_nombredearchivo;
				$archivo_destino = $ftp_carpeta_remota.$mi_nombredearchivo;
				$resultado_login = ftp_login($conexion_id, $ftp_usuario, $ftp_clave);
				
				if ((!$conexion_id) || (!$resultado_login)) {
					   echo  "La conexion ha fallado! al conectar con  $servidor_ftp para usuario $ftp_usuario";
					   exit;
				   } else {
					  // echo "Conectado con $servidor_ftp, para usuario $ftp_usuario";
				   }
				$upload = ftp_put($conexion_id, $archivo_destino, $nombre_archivo, FTP_BINARY);
				if (!$upload) {
					   echo "Ha ocurrido un error al subir el archivo";
				   } else {
					  // echo "Subido $nombre_archivo a $servidor_ftp as $archivo_destino";
				   }
				ftp_close($conexion_id);

					
				Db::getInstance()->Execute('
								INSERT INTO `'._DB_PREFIX_.'order_history`
								(`id_order_history`, `id_employee`, `id_order`, `id_order_state`, `date_add`)
								VALUES
								(NULL, "20", "'.pSQL($label_data['pedido']).'", "285", NOW())
								');
						
				$ressd = Db::getInstance()->getRow('SELECT MAX(delivery_number) AS deli_num FROM ps_orders');
					
				Db::getInstance()->execute('
								UPDATE `'._DB_PREFIX_.'orders` SET `current_state` = 285 WHERE `id_order`= ('.pSQL($label_data['pedido']).')
								');
				Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'orders` SET `delivery_date`=NOW(),`delivery_number`='.$ressd['deli_num'].'+1 WHERE `id_order`= ('.pSQL($label_data['pedido']).')');
						
				TamdisLib::setOrderImprimido($label_data['pedido']);
			}
		}
		catch (PrestaShopException $e)
		{
			return false;
		}
		
		return true;
	}
	
}
