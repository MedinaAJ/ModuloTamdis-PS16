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

//require('fpdf/fpdf.php');
//include_once(_PS_MODULE_DIR_.'modulo_tamdis/pdftamdis/fpdf.php');
//require_once(_PS_MODULE_DIR_.'modulo_dhl/pdf/fpdf.php');
require_once(_PS_MODULE_DIR_.'esp_dhl/pdf/fpdf.php');


class PDFT extends FPDF
{
	 //Cabecera de página
	function Header()
	{
		//Logo
		$this->Image( _PS_MODULE_DIR_.'modulo_tamdis/img/TAMDIS.jpg' , 10 ,8, 35 , 38 , "JPG" ,"http://www.tamdis.com");
		//Arial bold 15
		$this->SetFont('Arial','B',15);
		//Movernos a la derecha
		$this->Cell(80);
		//Título
		$this->Cell(60,10,'Pedido Tamdis',1,0,'C');
		//Salto de línea
		$this->Ln(20);
	}
	
	//Pie de página
	function Footer()
	{		
		//Posición: a 1,5 cm del final
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Número de página
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
   }
   
   function TablaBasica($header)
   {
    //Cabecera
    foreach($header as $col)
    $this->Cell(40,7,$col,1);
    $this->Ln();
    
      $this->Cell(40,5,"hola",1);
      $this->Cell(40,5,"hola2",1);
      $this->Cell(40,5,"hola3",1);
      $this->Cell(40,5,"hola4",1);
      $this->Ln();
      $this->Cell(40,5,"linea ",1);
      $this->Cell(40,5,"linea 2",1);
      $this->Cell(40,5,"linea 3",1);
      $this->Cell(40,5,"linea 4",1);
   }
	
}
