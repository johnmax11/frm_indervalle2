<?php
namespace MiProyecto{
	/*
	 * To change this template, choose Tools | Templates
	 * and open the template in the editor.
	 */

	/**
	 * Description of templates_pdf
	 *
	 * @author Usuario
	 */
	require_once ('html2pdf.class.php');
	class templates_pdf{
		function __construct(){
		}
		
		/** tpl para genera una factura **/
		public function get_template_factura($arrDatos){
			$html = "";
			$html .= '
					 <table style="width:100%;">
					  <tr>
					   <td style="width:98%;">
						<table style="width:100%;text-align:center;">
							<tr>
								<td style="width:60%;">
									<table style="width:100%;">
										<tr>
											<td style="text-align:center;">
												<img src="'.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/images/logonit.png"/><br/>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<img src="'.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/images/autoretenedorregimensimplificado.png"/>
												<br/>
												<label style="font-size:6pt;text-align:center;">
														Direcci&oacute;n: Av 4Nte 24-79 LC 27<br/>
														Tel&eacute;fono: 396 7115 - 489 8068
												</label>
											</td>
										</tr>
									</table>
								</td>
								<td style="width:40%;text-align:center;">
										<table style="width:100%;text-align:center;">
												<tr>
														<th style="text-align:center;">
																FECHA: '.$arrDatos->fecha_factura.'
														</th>
												</tr>
												<tr><th style="text-align:center;">&nbsp;</th></tr>
												<tr>
														<th style="text-align:center;">
																FACTURA DE VENTA: '.$arrDatos->numero_factura.'
														</th>
												</tr>
										</table>
								</td>
							</tr>
						</table>
				';
				$html .= '
						<table border="1"  style="width:100%;">
							<tr>
								<td style="width:10%;background-color:#eeeeee;">
									Nombre:
								</td>
								<td  style="width:90%;" colspan=5>
									'.strtoupper($arrDatos->nombre_cliente).'
								</td>
							</tr>
							<tr>
								<td style="width:10%;background-color:#eeeeee;">
									C.C:
								</td>
								<td style="">
									'.($arrDatos->nombre_cliente!='VENTA DIRECTA'?$arrDatos->identificacion_cliente:'').'
								</td>
								<td style="width:10%;background-color:#eeeeee;">
									Tel:
								</td>
								<td style=""> 
									'.($arrDatos->nombre_cliente!='VENTA DIRECTA'?$arrDatos->telefono_celular:'').'
								</td>
								<td style="width:10%;background-color:#eeeeee;">
									Cel:
								</td>
								<td style="">
									'.($arrDatos->nombre_cliente!='VENTA DIRECTA'?$arrDatos->telefono_celular:'').'
								</td>
							</tr>
							<tr>
								<td style="width:10%;background-color:#eeeeee;">
									Direcci&oacute;n:
								</td>
								<td  style="font-size:6pt;">
									'.($arrDatos->nombre_cliente!='VENTA DIRECTA'?$arrDatos->direccion:'').'
								</td>
								<td style="width:10%;background-color:#eeeeee;">
									Zona:
								</td>
								<td style="">
									'.($arrDatos->nombre_cliente!='VENTA DIRECTA'?strtoupper($arrDatos->zona):'').'
								</td>
								<td style="width:10%;background-color:#eeeeee;">
									Vendedor:
								</td>
								<td style="">
									'.($arrDatos->nombre_cliente!='VENTA DIRECTA'?strtoupper($arrDatos->nombre_vendedor):'').'
								</td>
							</tr>
						</table>
					';
			$html .= '
						<table border="1" style="width:100%;">
							<tr>
								<th style="width:36.96%;background-color:#eeeeee;">&nbsp;REFERENCIA&nbsp;</th>
								<th style="width:10.76%;background-color:#eeeeee;">&nbsp;TALLA&nbsp;</th>
								<th style="width:16.96%;background-color:#eeeeee;">&nbsp;VR. INICIAL&nbsp;</th>
								<th style="width:10.66%;background-color:#eeeeee;">&nbsp;DCTO&nbsp;</th>
								<th style="width:24.66%;background-color:#eeeeee;">&nbsp;VR. FINAL&nbsp;</th>
							</tr>
					';
				for($i=0;$i<count($arrDatos->cells);$i++){
				
					$html .= '
						<tr>
							<th style="width:36.96%;font-size:8pt;">&nbsp;'.$arrDatos->cells[$i]->nombre_producto.'&nbsp;</th>
							<th style="width:10.76%;font-size:8pt;">&nbsp;'.($arrDatos->cells[$i]->talla=="N/A"?"N/A":(substr($arrDatos->cells[$i]->talla,0,2))==0?round($arrDatos->cells[$i]->talla):substr($arrDatos->cells[$i]->talla,0,4)).'&nbsp;</th>
							<th style="width:16.96%;font-size:8pt;">&nbsp;$ '.$arrDatos->cells[$i]->vr_inicial.'&nbsp;</th>
							<th style="width:10.66%;font-size:8pt;">&nbsp;$ '.$arrDatos->cells[$i]->descuento.'&nbsp;</th>
							<th style="width:24.66%;font-size:8pt;">&nbsp;$ '.$arrDatos->cells[$i]->vr_final.'&nbsp;</th>
						</tr>
					';
				}
			$html .= '				
						</table>	
			';
			
			$html .= '
					<p></p>
				';
				
			$html .= '
				<table border="1" style="width:100%;">
					<tr>
						<td style="width:13%;background-color:#eeeeee;">
							SUB-TOTAL
						</td>
						<td align="right" style="width:'.(!isset($arrDatos->dcto_factura_general) || $arrDatos->dcto_factura_general==null || $arrDatos->dcto_factura_general==0?'20.33':'12').'%;">
							$ '.number_format($arrDatos->subtotal_factura).'
						</td>
											'.(isset($arrDatos->dcto_factura_general) && $arrDatos->dcto_factura_general!=null && $arrDatos->dcto_factura_general!=0?
											   '<td style="width:13%;background-color:#eeeeee;">DCTO</td>
												<td align="right" style="width:'.(!isset($arrDatos->dcto_factura_general) || $arrDatos->dcto_factura_general==null || $arrDatos->dcto_factura_general==0?'20.33':'12').'%;">$ '.number_format($arrDatos->dcto_factura_general).'</td>'
											   :''
											).'
						<td style="width:13%;background-color:#eeeeee;"> 
							IVA
						</td>
						<td align="right"  style="width:'.(!isset($arrDatos->dcto_factura_general) || $arrDatos->dcto_factura_general==null || $arrDatos->dcto_factura_general==0?'20.33':'12').'%;">
							$ '.number_format($arrDatos->iva_factura).'
						</td>
						<td style="width:13%;background-color:#eeeeee;">
							TOTAL
						</td>
						<td align="right" style="width:'.(!isset($arrDatos->dcto_factura_general) || $arrDatos->dcto_factura_general==null || $arrDatos->dcto_factura_general==0?'20.33':'12').'%;">
							$ '.number_format($arrDatos->total_factura).'
						</td>
					</tr>
				</table>
			';
			
			$html .= '
					<table style="width:100%">
						<tr>
							<td style="font-family:arial;font-size:6pt;" >
								Nuestra Garantia es de un (1) a&ntilde;o!<br/><br/>

								Esta Garantia Incluye:<br/>

								Peladuras o Costuras Descosidas a la Entrega del producto,
								Desprendimiento de la Folia que trae el Cuero,
								Despegue de la Suela o de la Plantilla interna de un Zapato,
								Rotura o desprendimiento de un Tacon, o su Tapa.<br/><br/>

								Amada no es Responsable Por:<br/>
								Desgaste natural del Cuero o de la Suela,
								Mordeduras de Mascotas,
								Peladuras por huecos o pedales de Carro/Moto,
								Roce con texturas Fuertes, Quimicos, o Corrosivos.<br/><br/>

								Productos en Promocion no Tienen Cambio
							</td>
						</tr>
					</table>
			';
			$html .= '</td>';
			$html .= '<td style="width:2%;">';
			$html .= '	<img src="'.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/images/impresobycomputador.png" />';
			$html .= '</td>';
			$html .= '</tr></table>';
			
			return $html;
		}
		
			
		/** tpl para genera una remision **/
		public function get_template_separado($arrDatos){
			$html = "";
			$html .= '
					 <table style="width:100%;">
					  <tr>
					   <td style="width:98%;">
						<table style="width:100%;text-align:center;">
							<tr>
														<td style="width:60%;">
															<table style="width:100%;">
																<tr>
																	<td style="text-align:center;">
																		<img src="'.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/images/logonit.png"/><br/>
																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																		<img src="'.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/images/autoretenedorregimensimplificado.png"/>
																		<br/>
																		<label style="font-size:6pt;text-align:center;">
																			Direcci&oacute;n: Av 4Nte 24-79 LC 27<br/>
																			Tel&eacute;fono: 396 7115 - 489 8068
																		</label>
																	</td>
																</tr>
															</table>
														</td>
														<td style="width:40%;text-align:center;">
															<table style="width:100%;text-align:center;">
																<tr>
																	<th style="text-align:center;">
																		FECHA: '.$arrDatos->fecha_pedido.'
																	</th>
																</tr>
																<tr><th style="text-align:center;">&nbsp;</th></tr>
																<tr>
																	<th style="text-align:center;">
																		SEPARADO: '.$arrDatos->numero_pedido.'
																	</th>
																</tr>
															</table>
														</td>
							</tr>
						</table>
				';
				$html .= '
						<table border="1"  style="width:100%;">
							<tr>
								<td style="width:10%;background-color:#eeeeee;">
									Nombre:
								</td>
								<td  style="width:90%;" colspan=5>
									'.strtoupper($arrDatos->nombre_cliente).'
								</td>
							</tr>
							<tr>
								<td style="width:10%;background-color:#eeeeee;">
									C.C:
								</td>
								<td style="">
									'.($arrDatos->nombre_cliente!='VENTA DIRECTA'?$arrDatos->identificacion_cliente:'').'
								</td>
								<td style="width:10%;background-color:#eeeeee;">
									Tel:
								</td>
								<td style=""> 
									'.($arrDatos->nombre_cliente!='VENTA DIRECTA'?$arrDatos->telefono_celular:'').'
								</td>
								<td style="width:10%;background-color:#eeeeee;">
									Cel:
								</td>
								<td style="">
									'.($arrDatos->nombre_cliente!='VENTA DIRECTA'?$arrDatos->telefono_celular:'').'
								</td>
							</tr>
							<tr>
								<td style="width:10%;background-color:#eeeeee;">
									Direcci&oacute;n:
								</td>
								<td  style="font-size:6pt;">
									'.($arrDatos->nombre_cliente!='VENTA DIRECTA'?$arrDatos->direccion:'').'
								</td>
								<td style="width:10%;background-color:#eeeeee;">
									Zona:
								</td>
								<td style="">
									'.($arrDatos->nombre_cliente!='VENTA DIRECTA'?strtoupper($arrDatos->zona):'').'
								</td>
								<td style="width:10%;background-color:#eeeeee;">
									Vendedor:
								</td>
								<td style="">
									'.($arrDatos->nombre_cliente!='VENTA DIRECTA'?strtoupper($arrDatos->nombre_vendedor):'').'
								</td>
							</tr>
						</table>
					';
			$html .= '
						<table border="1" style="width:100%;">
							<tr>
								<th style="width:20%;background-color:#eeeeee;">&nbsp;REFERENCIA&nbsp;</th>
								<th style="width:20%;background-color:#eeeeee;">&nbsp;TALLA&nbsp;</th>
								<th style="width:20%;background-color:#eeeeee;">&nbsp;VR. INICIAL&nbsp;</th>
								<th style="width:20%;background-color:#eeeeee;">&nbsp;DCTO&nbsp;</th>
								<th style="width:20%;background-color:#eeeeee;">&nbsp;VR. FINAL&nbsp;</th>
							</tr>
					';
				for($i=0;$i<count($arrDatos->cells);$i++){
				
					$html .= '
						<tr>
							<th style="width:20%;font-size:8pt;">&nbsp;'.$arrDatos->cells[$i]->nombre_producto.'&nbsp;</th>
							<th style="width:20%;font-size:8pt;">&nbsp;'.($arrDatos->cells[$i]->talla=="N/A"?"N/A":(substr($arrDatos->cells[$i]->talla,0,2))==0?round($arrDatos->cells[$i]->talla):substr($arrDatos->cells[$i]->talla,0,4)).'&nbsp;</th>
							<th style="width:20%;font-size:8pt;">&nbsp;$ '.$arrDatos->cells[$i]->vr_inicial.'&nbsp;</th>
							<th style="width:20%;font-size:8pt;">&nbsp;$ '.$arrDatos->cells[$i]->descuento.'&nbsp;</th>
							<th style="width:20%;font-size:8pt;">&nbsp;$ '.$arrDatos->cells[$i]->vr_final.'&nbsp;</th>
						</tr>
						<tr>
							<th colspan=10 style="width:100%;font-size:8pt;">Obs:&nbsp;'.$arrDatos->cells[$i]->observaciones.'&nbsp;</th>
						</tr>
					';
				}
			$html .= '				
						</table>	
			';
			
			$html .= '
					<p></p>
				';
				
			$html .= '
				<table border="1" style="width:100%;">
					<tr>
						<td style="width:'.(!isset($arrDatos->dcto_factura_general)||$arrDatos->dcto_factura_general==null||$arrDatos->dcto_factura_general==0?'13':'16.66').'%;background-color:#eeeeee;">
							SUB-TOTAL
						</td>
						<td align="right" style="width:'.(!isset($arrDatos->dcto_factura_general)||$arrDatos->dcto_factura_general==null||$arrDatos->dcto_factura_general==0?'37':'16.66').'%;">
							$ '.number_format($arrDatos->subtotal_pedido).'
						</td>
											'.
											(
											   !isset($arrDatos->dcto_factura_general)||$arrDatos->dcto_factura_general==null||$arrDatos->dcto_factura_general==0?
											   '':
											   '<td style="width:16.66%;background-color:#eeeeee;">DCTO</td>
											   <td align="right" style="width:16.66%;">$ '.number_format($arrDatos->dcto_factura_general).'</td>'
											)
											.'
						<td style="width:'.(!isset($arrDatos->dcto_factura_general)||$arrDatos->dcto_factura_general==null||$arrDatos->dcto_factura_general==0?'13':'16.66').'%;background-color:#eeeeee;">
							TOTAL
						</td>
						<td align="right" style="width:'.(!isset($arrDatos->dcto_factura_general)||$arrDatos->dcto_factura_general==null||$arrDatos->dcto_factura_general==0?'37':'16.66').'%;">
							$ '.number_format($arrDatos->total_pedido).'
						</td>
					</tr>
				</table>
			';
			
			$html .= '
					<table style="width:100%">
						<tr>
							<td style="font-family:arial;font-size:6pt;" >
								* No hace devoluci&oacute;n de dinero ni cambio de producto
							</td>
						</tr>
					</table>
			';
			
			if(isset($arrDatos->cells_p_pagos)){
				$html .= '
					<table style="width:100%;text-align:center;">
						<tr>
							<th style="width:100%;text-align:center;">PLAN DE PAGOS</th>
						</tr>
					</table>
				';
				$html .= '<table style="width:100%;text-align:center;">';
				$html .= '<tr>';
				//$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;border-left-style: solid;border-left-width: 1px;background: #F7F7F7;width:16.66%;border-top-style: solid;border-top-width: 1px;">&nbsp;</th>';
				$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;background: #F7F7F7;border-left-style: solid;border-left-width: 1px;width:20%;border-top-style: solid;border-top-width: 1px;">&nbsp;</th>';
				$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;background: #F7F7F7;width:20%;border-top-style: solid;border-top-width: 1px;">&nbsp;F. PAGO&nbsp;</th>';
				$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;background: #F7F7F7;width:20%;border-top-style: solid;border-top-width: 1px;">&nbsp;VR. A PAGAR&nbsp;</th>';
				$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;background: #F7F7F7;width:20%;border-top-style: solid;border-top-width: 1px;">&nbsp;VR. PAGADO&nbsp;</th>';
				$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;background: #F7F7F7;width:20%;border-top-style: solid;border-top-width: 1px;">&nbsp;SALDO&nbsp;</th>';
				$html .= '</tr>';
				for($i=0;$i<count($arrDatos->cells_p_pagos);$i++){
					$html .= '<tr>';
					//$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;border-left-style: solid;border-left-width: 1px;width:16.66%;">&nbsp;'.$i.'-&nbsp;</th>';
					$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;width:20%;border-left-style: solid;border-left-width: 1px;">&nbsp;'.$arrDatos->cells_p_pagos[$i]->tipo_cuota.'&nbsp;</th>';
					$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;width:20%;">&nbsp;'.$arrDatos->cells_p_pagos[$i]->fecha_de_pago.'&nbsp;</th>';
					$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;width:20%;">&nbsp;'.number_format($arrDatos->cells_p_pagos[$i]->valor_a_pagar).'&nbsp;</th>';
					$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;width:20%;">&nbsp;'.($arrDatos->cells_p_pagos[$i]->valor_pagado==0?'&nbsp;':number_format($arrDatos->cells_p_pagos[$i]->valor_pagado)).'&nbsp;</th>';
					$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;width:20%;">&nbsp;'.number_format($arrDatos->cells_p_pagos[$i]->valor_a_pagar - $arrDatos->cells_p_pagos[$i]->valor_pagado).'&nbsp;</th>';
					$html .= '</tr>';
				}
				$html .= '<tr>';
				//$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;border-left-style: solid;border-left-width: 1px;background: #F7F7F7;width:16.66%;">&nbsp;</th>';
				$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;background: #F7F7F7;width:20%;border-left-style: solid;border-left-width: 1px;">&nbsp;</th>';
				$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;background: #F7F7F7;width:20%;">&nbsp;</th>';
				$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;background: #F7F7F7;width:20%;">&nbsp;'.number_format($arrDatos->vr_total_a_pagar_pp).'&nbsp;</th>';
				$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;background: #F7F7F7;width:20%;">&nbsp;'.number_format($arrDatos->vr_total_pagado_pp).'&nbsp;</th>';
				$html .= '		<th style="border-right-width: 1px;border-right-style:solid;border-bottom-width: 1px;border-bottom-style: solid;background: #F7F7F7;width:20%;">&nbsp;'.number_format($arrDatos->vr_total_saldo_pp).'&nbsp;</th>';
				$html .= '</tr>';
				$html .= '</table>';
			}
			
			$html .= '
						<br/><br/>
						<p>Acepto:</p>
						<br/><br/>
						<p style="width:!00%;">
							________________________
							<br/>
							C.C N°
						</p>
					';
			
			$html .= '</td>';
			$html .= '<td style="width:2%;">';
			$html .= '	<img src="'.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/images/impresobycomputador_corta.png" />';
			$html .= '</td>';
			$html .= '</tr></table>';
			
			return $html;
		}
		
		public function get_template_comprobante($arrDatosComprobante,$arrDatostarjeta=null,$arrDatosconsignacion=null){

			$html="";
			$html.="<style type='text/css'>";
			$html.=".titulos{
						background-color: gray;
						font-weight: bold;
					}

					#footer{
						text-align: center;
					}";
			$html.="</style>";
			$html.="<table width='10%' border='1px;' style='font-size:8pt;'>";
			$html.="<tr>";
			$html.="		<td>";		
			$html.="		<img src='".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/images/logoamadaautoretenedor.png' alt='Logo Amada'/>";
			$html.=			"</td>";
			$html.=			"<td class='titulos'>";
			$html.=				"FECHA:";
			$html.=			"</td>";
			$html.=			"<td class='titulos'>";
			$html.=				"RECIBO CAJA";
			$html.=			"</td>";
			$html.=			"<td rowspan='9'>";
			$html.=			"	<table border='1px' style='font-size:10pt;' width='100%'> ";
			$html.=					"<tr> ";
					if (isset($arrDatosComprobante['saldo_favor']) && $arrDatosComprobante['saldo_favor']!=null) {
				$html.="		<td>";	
				$html.=				"<table style='font-size:5pt;' width='50%' height='100%'> ";
				$html.=					"<tr>";
				$html.=						"<th class='titulos'>SALDO FAVOR</th>";
				$html.=					"</tr>";
				$html.=					"<tr>";
				$html.=						"<td>";
				$html.=								"$ ".number_format($arrDatosComprobante['saldo_favor']);
				$html.=						"</td>";
				$html.=					"</tr>";
				$html.=				"</table>";
				$html.="		</td>";	
			}
			if (isset($arrDatosComprobante['efectivo']) && $arrDatosComprobante['efectivo']!=null) {
				$html.="		<td>";	
				$html.=				"<table style='font-size:5pt;' width='50%' height='100%'> ";
				$html.=					"<tr>";
				$html.=						"<th class='titulos'>EFECTIVO</th>";
				$html.=					"</tr>";
				$html.=					"<tr>";
				$html.=						"<td>";
				$html.=								"$ ".number_format($arrDatosComprobante['efectivo']);
				$html.=						"</td>";
				$html.=					"</tr>";
				$html.=				"</table>";
				$html.="		</td>";	
			}
			
			$html.=					"</tr>";
			$html.=			"	</table>";
			$html.=			"</td>";
			$html.=		"</tr>";
			$html.=		"<tr>";
			$html.=			"<td>";
			$html.=				"&nbsp;";			
			$html.=			"</td>";
			$html.=			"<td>"	;
			$html.=					substr($arrDatosComprobante['fecha'], 0,10);		
			$html.=			"</td>";
			$html.=			"<td>";
			$html.=					"RCA ".str_pad($arrDatosComprobante['consecutivo'], 6,0,STR_PAD_LEFT);
			$html.=			"</td>";
			$html.=		"</tr>";
			$html.=		"<tr>";
			$html.=			"<td class='titulos'>";
			$html.=				"NOMBRE";
			$html.=			"</td>";
			$html.=			"<td>".$arrDatosComprobante['nombre_cliente'];
			$html.=			"</td>";
			$html.=		"</tr>";
			$html.=		"<tr>";
			$html.=			"<td class='titulos'>";
			$html.=				"CEDULA";
			$html.=			"</td>";
			$html.=			"<td>".$arrDatosComprobante['identificacion'];
			$html.=		"</td>";
			$html.=		"</tr>";
			$html.=		"<tr>";
			$html.=			"<td class='titulos'>";
			$html.=				"CONCEPTO";
			$html.=			"</td>";
			$html.=			"<td>".$arrDatosComprobante['concepto'];
			$html.=			"</td>";
			$html.=		"</tr>";
			$html.=		"<tr>";
			$html.=			"<td class='titulos'>";
			$html.=				"TOTAL RECIBIDO";
			$html.=		"</td>";
			$html.=			"<td>$ ".number_format($arrDatosComprobante['recibido']);
			$html.=			"</td>";
			$html.=		"</tr>";
			$html.=		"<tr>";
			$html.=			"<td class='titulos'>";
			$html.=				"SALDO";
			$html.=			"</td>";
			$html.=			"<td>$ ".number_format($arrDatosComprobante['saldo']);
			$html.=			"</td>";
			$html.=		"</tr>";
			$html.=		"<tr>";
			$html.=			"<td class='titulos'>";
			$html.=				"RECIBE";
			$html.=			"</td>";
			$html.=			"<td>".$arrDatosComprobante['recibe'];
			$html.=			"</td>";
			$html.=		"</tr>";
			$html.=		"<tr>";
			$html.=			"<td colspan='3' id='footer'>";
			$html.=				"Av. 4 Norte #24 - 79 L27 C.Cial. del Norte - Telefono: 3967115 - Cali Colombia";
			$html.=			"</td>";
			$html.=		"</tr>";
			$html.="</table>";


			return $html;

		}

	}
}
