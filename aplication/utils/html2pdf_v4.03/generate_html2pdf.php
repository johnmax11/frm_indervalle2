<?php
namespace MiProyecto{

	/*
	 * To change this template, choose Tools | Templates
	 * and open the template in the editor.
	 */

	/**
	 * Description of generate_html2pdf
	 *
	 * @author Usuario
	 */
	require_once ('html2pdf.class.php');
	class generate_html2pdf {
		//put your code here
		public $_titulo = "";
		public $_url    = "/cueros/tmp/";
		public $_bolFileYN = true;
		public $_bolFooter = true;
		public $_bolStyle  = true;
		public $_nombreArchivo = "archivoPdf.pdf";
		public $_arrColTotalizar = array();
		private $_arrTotalesInt = array();
		public $_arrColExcepciones = array();
		public $_bolDebug = false;
		/*
		 * recibe un array de datos y de acuerdo a los parametros arma el html que lleva el pdf
		 */
		public function create_html_pdf($arrDatos = null){
			if($arrDatos== null || $arrDatos==""){
				return false;
			}
			ob_start();
			try{
				$content = "";
				//
				$content .= $this->open_hoja_pdf();
				// incluimos los stylos
				if($this->_bolStyle == true){
					//$content .= $this->get_stilos();
				}
				//
				$content .= '<table cellspacing="0" 
									style="width: 100%; 
										   border: solid 1px black;"
									align="center"
							 >';
				
				if($this->_titulo != ""){
					$content .= "<tr>
									<th colspan='20' align='center'
										style='background: #F7F7F7; 
											   border: solid 1px #B5CBD7;'><h3>".$this->_titulo."</h3></th></tr>";
				}
				
				$titulostable = "<tr>";
				$boltitulostable = false;
				$bodytable = "";
				foreach($arrDatos as $key => $valor){
					if($key == "rows"){
						foreach($valor as $keyrow => $valorcelda){
							$bodytable .= "<tr>";
							foreach($valorcelda as $keycelda => $valorcampo){
								// verificamos si la columna esta entre las excepciones //
								if(in_array($keycelda,$this->_arrColExcepciones)){
									continue;
								}
								//
								if($boltitulostable == false){
									$titulostable .= "<th align='center' style='background: #F7F7F7;border: solid 0.5px #B5CBD7; '>&nbsp;".ucwords(strtolower($keycelda))."&nbsp;</th>";
								}
								// verificamos si hay q hacer sumatorias
								if(count($this->_arrColTotalizar)>0){
									if(in_array($keycelda, $this->_arrColTotalizar)){
										if(!isset($this->_arrTotalesInt[$keycelda][0])){
											$this->_arrTotalesInt[$keycelda][0] = 0;
										}
										$this->_arrTotalesInt[$keycelda][0] += str_replace(',','',$valorcampo);
									}else{
										$this->_arrTotalesInt[$keycelda][0] = null;
									}
								}
								//
								$bodytable .= "<td ".(is_numeric(str_replace('.','',str_replace(',','',$valorcampo)))?'align="right"':'align="left"')."'>&nbsp;".ucwords(strtolower($valorcampo))."&nbsp;</td>";
							}
							$boltitulostable = true;
							$bodytable .= "</tr>";
						}
					}
				}
				$content .= $titulostable.'</tr>';
				$content .= $bodytable;
				// verificamos si hay q poner totalizadores
				//echo "<pre>".print_r($this->_arrTotalesInt,true)."</pre>";
				if(count($this->_arrColTotalizar)>0){
					$content .= "<tr>";
					foreach($this->_arrTotalesInt as $key => $valor){
						
						if($valor[0] == null){
							$content .= "<th  align='center' style='background: #F7F7F7;border: solid 0.5px #B5CBD7; '>&nbsp;</th>";
						}else{
							$content .= "<th  align='right' style='background: #F7F7F7;border: solid 0.5px #B5CBD7; '>".number_format($valor[0])."</th>";
						}
					}
					$content .= "</tr>";
				}
				//
				$content .= "</table>";
				///verificamos el footer
				if($this->_bolFooter==true){
					$content .= $this->get_footer();
				}
				//
				$content .= $this->close_hoja_pdf();
				//
				$html2pdf = new HTML2PDF('L', 'A4', 'es');
				if($this->_bolDebug){
					$html2pdf->setModeDebug();
				}
				$html2pdf->writeHTML($content);
				$html2pdf->pdf->SetDisplayMode('fullpage');
				$html2pdf->Output($this->_url.$this->_nombreArchivo,"F");
			}catch(HTML2PDF_exception $e){
				echo $e->getMessage();
			}
		}
		/*
		 * abre la pagina del pdf
		 */
		private function open_hoja_pdf(){
			return '<page  footer="date;heure;page" 
						   style="font-size: 10pt
					">';
		}
		/**
		 * cierra la pagin
		 */
		private function close_hoja_pdf(){
			return "</page>";
		}
		/**
		 * set footer de la pagina
		 */
		private function get_footer(){
			return "<page_footer>
						<table>
							<tr>
								<th>Colmena Ebano &copy; - ".@date('Y')."</th>
							</tr>
						</table>
					</page_footer>";
		}

		/**
		 * returna los stilos del html
		 */
		private function get_stilos(){
			return "";
		}
	}
}
