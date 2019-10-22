<?php //require_once('fpdf.php');
require_once(_PS_MODULE_DIR_.'esp_dhl/pdf/fpdf.php');
 class Albaran extends FPDF { 
	 private $_lineas = array();
	 private $_paginas = 0;
	 private $_copia = false;
	 private $_cliente;
	 private $_cocodcli_Ancho = 28;
	 private $_cocodcli_xPos = 5;
	 private $_coexpe_Ancho = 15;
	 private $_coexpe_xPos = 33;
	 private $_corefcli_Ancho = 37;
	 private $_corefcli_xPos = 48;
	 private $_consig_Ancho = 35;
	 private $_consig_xPos = 85;
	 private $_codirdes_Ancho = 37;
	 private $_codirdes_xPos = 120;
	 private $_copobdes_Ancho = 20;
	 private $_copobdes_xPos = 157;
	 private $_cocpdes_Ancho = 15;
	 private $_cocpdes_xPos = 177;
	 private $_cobultos_Ancho = 12;
	 private $_cobultos_xPos = 192;
	 private $_cokilos_Ancho = 14;
	 private $_cokilos_xPos = 204;
	 private $_coclpint_Ancho = 9;
	 private $_coclpint_xPos = 218;
	 private $_coreembo_Ancho = 15;
	 private $_coreembo_xPos = 227;
	 private $_covaseg_Ancho = 15;
	 private $_covaseg_xPos = 242;
	 private $_coobser1_Ancho = 30;
	 private $_coobser1_xPos = 257;
	 private $_separacion_lineas = 4;
	 private $_linea_fin_pagina = 200;
 
	 public function __construct($_114a24200c162cd626e2ad499dd50090, $_e21a78ed682dc820bd4a6341c558f549 = 'L', $_fd86f6ed6d593a5a91a81715ae2bd318 = 'mm', $_8cfaf48529c3bf15489c8d04b198db06 = 'A4') {
		 parent::FPDF($_e21a78ed682dc820bd4a6341c558f549, $_fd86f6ed6d593a5a91a81715ae2bd318, $_8cfaf48529c3bf15489c8d04b198db06);
		 $this->SetMargins(5, 5);
		 $this->SetAutoPageBreak(false);
		 $this->_cliente = $_114a24200c162cd626e2ad499dd50090;
	 }
 
	 public function Header() {
		 $this->SetFillColor("230", "230", "230");
		 $this->SetFont('Arial', '', 7);
		 $_2d8098fdbeb6e8ce119c1ebbb67ae727 = new DateTime();
		 $this->setXY(5, 10);
		 $this->Cell(28, 10, $_2d8098fdbeb6e8ce119c1ebbb67ae727->format('d/m/Y'), 1, 0, 'C', true);
		 $this->setXY(33, 10);
		 $this->Cell(115, 10, $this->_cliente, 1, 0, 'C', true);
		 $this->setXY(148, 10);
		 $this->Cell(48, 10, "", 1, 0, 'C', true);
		 $this->setXY(196, 10);
		 $this->Cell(48, 10, utf8_decode("DHL ESPAÑA"), 1, 0, 'C', true);
		 $this->setXY(244, 10);
		 $this->Cell(48, 10, "", 1, 0, 'L', true);
		 $this->setXY(254, 11);
		 $this->Image(dirname(__FILE__) . '/img/dhl.jpg', 254, 11, 30);
		 $this->SetFillColor("255", "255", "255");
		 $this->_printTableHeader(20);
	 }
 
	 public function addLine($_cbad4c7a0026512cdeb58a270ab1997e, $_2dbea28a8a704997496b9c0b77da652c, $_f507b6342205f81f5cc66fef08a61e59, $_e7746263aecabdeb07827317d7de5d9d, $_86b5f0b911a55205b7d8aa69a9b4283e, $_35d29691bb88b4b753ad9b00eb6f9b6d, $_244103767ffb4a52cb674787fc8cbc4b, $_e6073b1f950c546f70573e05ddfbcd6f, $_c222eaa6dcc319e04e0be07e90367b4a, $_58b583b35e20958b50673977626fc13f, $_0addc768cc3b71c1aa5d19b5b95f5cfa, $_1e68df2bf6a4da018da88226a1b06c01, $_c39fb33cc1eba4e326e25217b5b2a841) {
		 if($_58b583b35e20958b50673977626fc13f == "N"){
		 	$_58b583b35e20958b50673977626fc13f = "";
		 }
		 $this->_lineas[] = array( "codCliente" => utf8_decode($_cbad4c7a0026512cdeb58a270ab1997e), "expedicion" => utf8_decode($_2dbea28a8a704997496b9c0b77da652c), "referencia" => utf8_decode($_f507b6342205f81f5cc66fef08a61e59), "consignatario" => utf8_decode($_e7746263aecabdeb07827317d7de5d9d), "direccion" => utf8_decode($_86b5f0b911a55205b7d8aa69a9b4283e), "poblacion" => utf8_decode($_35d29691bb88b4b753ad9b00eb6f9b6d), "cp" => utf8_decode($_244103767ffb4a52cb674787fc8cbc4b), "bultos" => intval($_e6073b1f950c546f70573e05ddfbcd6f), "peso" => intval($_c222eaa6dcc319e04e0be07e90367b4a), "portes" => utf8_decode($_58b583b35e20958b50673977626fc13f), "reembolso" => $_0addc768cc3b71c1aa5d19b5b95f5cfa, "seguro" => $_1e68df2bf6a4da018da88226a1b06c01, "observaciones" => $_c39fb33cc1eba4e326e25217b5b2a841 );
	 }
 
	 private function _printTableHeader() {
		 $this->SetFont('Arial', 'B', 6);
		 $_c8f00f756951a6cba8b7a43baa21ea5c = $this->getY();
		 $this->SetFillColor("240", "240", "240");
		 $this->SetXY($this->_cocodcli_xPos, 20);
		 $this->MultiCell($this->_cocodcli_Ancho, $this->_separacion_lineas, "\nNum. Cuenta \n\n", '1', "C", true);
		 $this->SetXY($this->_coexpe_xPos, 20);
		 $this->MultiCell($this->_coexpe_Ancho, $this->_separacion_lineas, utf8_decode("\nId Pedido \n\n"), '1', "C", true);
		 $this->SetXY($this->_corefcli_xPos, 20);
		 $this->MultiCell($this->_corefcli_Ancho, $this->_separacion_lineas, "\nEtiquetas \n\n", '1', "C", true);
		 $this->SetXY($this->_consig_xPos, 20);
		 $this->MultiCell($this->_consig_Ancho, $this->_separacion_lineas, "\nConsignatario \n\n", '1', "C", true);
		 $this->SetXY($this->_codirdes_xPos, 20);
		 $this->MultiCell($this->_codirdes_Ancho, $this->_separacion_lineas, utf8_decode("\nDirección \n\n"), '1', "C", true);
		 $this->SetXY($this->_copobdes_xPos, 20);
		 $this->MultiCell($this->_copobdes_Ancho, $this->_separacion_lineas, utf8_decode("\nPoblación \n\n"), '1', "C", true);
		 $this->SetXY($this->_cocpdes_xPos, 20);
		 $this->MultiCell($this->_cocpdes_Ancho, $this->_separacion_lineas, "\nCod Postal \n\n", '1', "C", true);
		 $this->SetXY($this->_cobultos_xPos, 20);
		 $this->MultiCell($this->_cobultos_Ancho, $this->_separacion_lineas, "\nBultos \n\n", '1', "C", true);
		 $this->SetXY($this->_cokilos_xPos, 20);
		 $this->MultiCell($this->_cokilos_Ancho, $this->_separacion_lineas, "\nPeso \n\n", '1', "C", true);
		 $this->SetXY($this->_coclpint_xPos, 20);
		 $this->MultiCell($this->_coclpint_Ancho, $this->_separacion_lineas, utf8_decode("\nPortes \n"), '1', "C", true);
		 $this->SetXY($this->_coreembo_xPos, 20);
		 $this->MultiCell($this->_coreembo_Ancho, $this->_separacion_lineas, "\nReembolso \n\n", '1', "C", true);
		 $this->SetXY($this->_covaseg_xPos, 20);
		 $this->MultiCell($this->_covaseg_Ancho, $this->_separacion_lineas, "\nTotal Ped \n\n", '1', "C", true);
		 $this->SetXY($this->_coobser1_xPos, 20);
		 $this->MultiCell($this->_coobser1_Ancho, $this->_separacion_lineas, "\nObservaciones \n\n", '1', "C", true);
		 $this->SetFillColor("255", "255", "255");
		 return $_c8f00f756951a6cba8b7a43baa21ea5c;
	 }
 
	 private function _printLines($_b4e9578c645deca9edf59ce0cf6a6df2) {
		 $this->SetFont('Arial', '', 7);
		 $_a20309649bd2656047add3ceb609dec4 = $_b4e9578c645deca9edf59ce0cf6a6df2;
		 $_f5b175c794b6ac11c2e0b2510425dd91 = 0;
		 for ($_444c1a0869785ea310f04782ee408fa8 = 0;$_444c1a0869785ea310f04782ee408fa8 < count($this->_lineas);$_444c1a0869785ea310f04782ee408fa8++) {
		 	$this->SetY($_a20309649bd2656047add3ceb609dec4);
		 	$_c8f00f756951a6cba8b7a43baa21ea5c = $_a20309649bd2656047add3ceb609dec4;
	 		if ($_c8f00f756951a6cba8b7a43baa21ea5c >= $this->_linea_fin_pagina) {
	 			$this->AddPage();
	 			$_c8f00f756951a6cba8b7a43baa21ea5c = $_b4e9578c645deca9edf59ce0cf6a6df2;
	 		}
	 		$_a20309649bd2656047add3ceb609dec4 = 0;
			$this->SetXY($this->_cocodcli_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			$this->MultiCell($this->_cocodcli_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["codCliente"],40), 1, "R");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
			$this->SetXY($this->_coexpe_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			$this->MultiCell($this->_coexpe_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["expedicion"],20), 1, "R");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
			$this->SetXY($this->_corefcli_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			$this->MultiCell($this->_corefcli_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["referencia"],30), 1, "L");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
			$this->SetXY($this->_consig_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			$this->MultiCell($this->_consig_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["consignatario"],45), 1, "L");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
			$this->SetXY($this->_codirdes_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			$this->MultiCell($this->_codirdes_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["direccion"],45), 1, "L");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
			$this->SetXY($this->_copobdes_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			$this->MultiCell($this->_copobdes_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["poblacion"],25), 1, "L");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
			$this->SetXY($this->_cocpdes_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			$this->MultiCell($this->_cocpdes_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["cp"],30), 1, "R");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
			$this->SetXY($this->_cobultos_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			$this->MultiCell($this->_cobultos_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["bultos"],20), 1, "R");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
			$this->SetXY($this->_cokilos_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			$this->MultiCell($this->_cokilos_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["peso"]." kg",20), 1, "R");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
			$this->SetXY($this->_coclpint_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			//$this->MultiCell($this->_coclpint_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["portes"],30), 1, "C");
			$this->MultiCell($this->_coclpint_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["portes"],15), 1, "L");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
			$this->SetXY($this->_coreembo_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			$this->MultiCell($this->_coreembo_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["reembolso"],30), 1, "R");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
			$this->SetXY($this->_covaseg_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			$this->MultiCell($this->_covaseg_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["seguro"],20), 1, "R");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
			$this->SetXY($this->_coobser1_xPos, $_c8f00f756951a6cba8b7a43baa21ea5c);
			$this->MultiCell($this->_coobser1_Ancho, $this->_separacion_lineas, $this->rellenar($this->_lineas[$_444c1a0869785ea310f04782ee408fa8]["observaciones"],41), 1, "L");
			if ($this->getY() > $_a20309649bd2656047add3ceb609dec4) $_a20309649bd2656047add3ceb609dec4 = $this->getY();
	 	}
	 	return $_a20309649bd2656047add3ceb609dec4;
	 }
 
 	 public function AddPage($_e21a78ed682dc820bd4a6341c558f549 = '', $_8cfaf48529c3bf15489c8d04b198db06 = '') {
		if (!$this->_copia) {
			$this->_paginas++;
		}
		parent::AddPage($_e21a78ed682dc820bd4a6341c558f549, $_8cfaf48529c3bf15489c8d04b198db06);
	 }
 
	 public function Output($_053046d094aa6e227a452c33d78c2c2d = '', $_55d31a2c5588693e19796cc2fe6612a1 = '') {
		$this->AddPage();
		$_b4e9578c645deca9edf59ce0cf6a6df2 = $this->_printLines(32);
		$_b4e9578c645deca9edf59ce0cf6a6df2 += $this->_separacion_lineas;
		$this->AddPage();
		$this->_copia = true;
		$_b4e9578c645deca9edf59ce0cf6a6df2 = $this->_printLines(32);
		$_b4e9578c645deca9edf59ce0cf6a6df2 += $this->_separacion_lineas;
		parent::Output($_053046d094aa6e227a452c33d78c2c2d, $_55d31a2c5588693e19796cc2fe6612a1);
	}
	
	private function rellenar($_4eb5f9a1d192a63bc108918a579a43c0, $_e854a25ddaf02309e478fb42875101b4) {
		while (strlen($_4eb5f9a1d192a63bc108918a579a43c0) < $_e854a25ddaf02309e478fb42875101b4) {
			$_4eb5f9a1d192a63bc108918a579a43c0 = $_4eb5f9a1d192a63bc108918a579a43c0 . " ";
		}
		return $_4eb5f9a1d192a63bc108918a579a43c0;
	}
 }
 ?>