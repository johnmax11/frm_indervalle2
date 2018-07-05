<?php
namespace MiProyecto{
	/*
	 * To change this template, choose Tools | Templates
	 * and open the template in the editor.
	 */

	/**
	 * Description of mail_plantillas
	 *
	 * @author vanessa
	 */
	class mail_plantillas {
		public $_strHtmlPlantilla;
		//put your code here
		public function new_tpl_new_usuario(){
			$strHtml = "
						 <table>
							<tr>
								<td>
									Se ha registrado exitosamen el usuario: <i>[|NOMBRE_USUARIO|]</i>
								</td>
							</tr>
							<tr>
								<td>
									La contrase&ntilde;a asignada aleatoriamente por el sistema es: <b>[|CLAVE_USUARIO|]</b>
								</td>
							</tr>
							<tr>
								<td>Link de acceso al sistema: <a href='[|URL_WEB|]'>[|URL_WEB|]</a></td>
							</tr>
							<tr>
								<td>Por favor cambiar la contrase&ntilde;a al ingresar al sistema por primera vez</td>
							</tr>
						 </table>
					   ";
			$this->_strHtmlPlantilla = $strHtml;
		}
		
		/**
		 * @return none plantilla para resetear el password del usuario
		 */
		public function new_tpl_reset_password(){
			$strHtml = "
						 <table>
							<tr>
								<td>
									Se ha reiniciado la clave del usuario: <i>[|NOMBRE_USUARIO|]</i>
								</td>
							</tr>
							<tr>
								<td>
									La contrase&ntilde;a asignada aleatoriamente por el sistema es: <b>[|CLAVE_USUARIO|]</b>
								</td>
							</tr>
							<tr>
								<td>Link de acceso al sistema: <a href='[|URL_WEB|]'>[|URL_WEB|]</a></td>
							</tr>
							<tr>
								<td>Por favor cambiar la contrase&ntilde;a al ingresar al sistema por primera vez</td>
							</tr>
						 </table>
					   ";
			$this->_strHtmlPlantilla = $strHtml;
		}
		
		/***/
		public function new_tpl_titulo_meta(){
			$strHtml = "
						<div>
							<div>
								<p><b>Hola <i style='color:blue;'>[NAME_USER]</i> Recuerda y repite esto conmigo:</b></p>
							</div>
							<div>
								<p>
									<b>¡Seguire cumpliendo mis metas!</b>
								</p>
							</div>
							<div>
								<div style='background-color:#F6F1F1; width:60%; text-align:center'>
									Repasa el titulo de tu Meta porque la tienes que tener cumplida en la fecha:<b>[FECHA_META]</b>
									<p style='font-family:verdana;'>
										<i style='color:blue;'>
											¡¡¡ [TITLE_META] !!!
										</i>
										<br/>
										<a href='[IMG_1]'><img width='100' height='80' src='[IMG_1]' /></a>
										<a href='[IMG_2]'><img width='100' height='80' src='[IMG_2]' /></a>
										<a href='[IMG_3]'><img width='100' height='80' src='[IMG_3]' /></a>
										<a href='[IMG_4]'><img width='100' height='80' src='[IMG_4]' /></a>
									</p>
								</div>
							</div>
							<div>
								<p>
									<b>Tu eres lo que piensas, buena actitud trae buenos resultados</b>
								</p>
							</div>
						</div>
					   ";
			$this->_strHtmlPlantilla = $strHtml;
		}
		
		/***/
		public function new_tpl_descripcion_meta(){
			$strHtml = "
						<div>
							<div>
								<p><b>Hola <i style='color:blue;'>[NAME_USER]</i> Recuerda y repite esto conmigo:</b></p>
							</div>
							<div>
								<p>
									<b>¡Cada minuto que pasa estoy cumpliendo mis METAS!</b>
								</p>
							</div>
							<div>
								<div style='background-color:#F6F1F1; width:60%; text-align:center'>
									A&ntilde;ora tu meta con la pasion mas desenfrenada del mundo porque me prometiste cumplirla en esta
									fecha:<b>[FECHA_META]</b>
									<p style='font-family:verdana;'>
										<i style='color:blue;'>
											¡¡¡ [DESCRIPCION_META] !!!
										</i>
										<br/>
										<a href='[IMG_1]'><img width='100' height='80' src='[IMG_1]' /></a>
										<a href='[IMG_2]'><img width='100' height='80' src='[IMG_2]' /></a>
										<a href='[IMG_3]'><img width='100' height='80' src='[IMG_3]' /></a>
										<a href='[IMG_4]'><img width='100' height='80' src='[IMG_4]' /></a>
									</p>
								</div>
							</div>
							<div>
								<p>
									<b>Todo es posible para el que tiene fe</b>
								</p>
							</div>
							<div>
								<img width='200' height='180' src='[IMG_2]' />
							</div>
						</div>
					   ";
			$this->_strHtmlPlantilla = $strHtml;
		}
		
		/***/
		public function new_tpl_frase_motivadora(){
			$strHtml = "
						<div>
							<div>
								<p><b>Hola <i style='color:blue;'>[NAME_USER]</i>, Toma este regalo, disfrutalo ¡es tuyo!:</b></p>
							</div>
							<div>
								<div style='background-color:#F6F1F1; width:100%; text-align:center'>
									<p style='font-family:verdana;'>
										<i style='color:blue;'>
											¡¡¡ [FRASE] !!!
										</i>
										<br/>
										<a href='[IMG_1]'><img width='100' height='80' src='[IMG_1]' /></a>
										<a href='[IMG_2]'><img width='100' height='80' src='[IMG_2]' /></a>
										<a href='[IMG_3]'><img width='100' height='80' src='[IMG_3]' /></a>
										<a href='[IMG_4]'><img width='100' height='80' src='[IMG_4]' /></a>
									</p>
								</div>
							</div>
						</div>
					   ";
			$this->_strHtmlPlantilla = $strHtml;
		}
	}
}
