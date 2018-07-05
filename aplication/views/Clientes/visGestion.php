<?php 
namespace MiProyecto;
    if(!isset($_SESSION)){
        session_start();
    }
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/router/index.php');
?>
<script type="text/javascript" src="js/Include/include.js"></script>
<div id="divBody">
    <div align="right">
        <h1>
            <img src="images/my-account.png"/>
            Clientes
        </h1>
        <hr/>
    </div>
    <br/>
    <div id="divPreload" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
    <div align="center">
        <div align="center"><p class="validateTips"></p></div>
        <div id="divAlert" class="divAlert" align="center"></div>
        <br/>
           <table id='griddatoscx'></table>
           <div id='pagerdatoscx'></div>
        <br/>
    </div>
    <div id="divOculto" style="display:none;">
        <div id="divAddEdit" title="Ingresar/Editar Registro">
            <div id="divPreloadAddEdit" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
            <div id="divBodyAddEdit" style='font-family:verdana;font-size:x-small;'>
                <form id="frmAddEdit" action="" method="post" autocomplete="on">
                    <input type="hidden" id="hdnid" name="hdnid"/>
                    <fieldset>
                        <legend>Datos Basicos</legend>
                        <table width=100%>
                            <tr>
                                <th>Tipo Doc:</th>
                                <td>
                                    <select id='selTipoDocumento_clientes' name='selTipoDocumento_clientes'>
                                        <option value='25' selected=selected>Cedula Ciudadania</option>
                                        <option value='27'>Cedula Extranjeria</option>
                                    </select>
                                </td>
                                <th>Identificaci&oacute;n:</th>
                                <td>
                                        <input type='text' id='txtIdentificacion_clientes' name='txtIdentificacion_clientes' class="bttn_class validate"  pattern=".{3,255}"/>
                                </td>
                            </tr>
                            <tr>
                                <th>Nombres:</th>
                                <td>
                                    <input type='text' id='txtNombres_clientes' name='txtNombres_clientes' class="bttn_class validate" pattern=".{3,255}"/>
                                </td>
                                <th>Apellidos:</th>
                                <td>
                                    <input type='text' id='txtApellidos_clientes' name='txtApellidos_clientes' class="bttn_class validate"  pattern=".{3,255}"/>
                                </td>
                            </tr>
                            <tr>
                                <th>F. Cumplea&ntilde;os:</th>
                                <td>
                                    <select id='txtFecha_Nacimiento_clientes' name='txtFecha_Nacimiento_clientes'>
                                        <option value=''>...</option>
                                        <?php
                                                for($i=1;$i<=31;$i++){
                                        ?>
                                                    <option value='<?php echo $i; ?>'>&nbsp;<?php echo $i; ?>&nbsp;</option>
                                        <?php
                                                }
                                        ?>
                                    </select>
                                    <select id='txtFecha_Nacimiento_clientes_mes' name='txtFecha_Nacimiento_clientes_mes'>
                                        <option value=''>...</option>
                                        <?php
                                                $arr_m = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
                                                for($i=1;$i<=12;$i++){
                                        ?>
                                                        <option value='<?php echo $i; ?>'>&nbsp;<?php echo $arr_m[$i]; ?>&nbsp;</option>
                                        <?php
                                                }
                                        ?>
                                    </select>
                                </td>
                                <th>Estado:</th>
                                <td>
                                    <select id='selEstado_clientes' name='selEstado_clientes'>
                                        <option value='A'>Activo</option>
                                        <option value='I'>Inactivo</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset>
                        <legend>Datos Contacto</legend>
                        <table>
                            <tr>
                                <th>Tel Fijo:</th>
                                <td>
                                    <input type='text' id='txtTelfono_fijo_clientes' name='txtTelfono_fijo_clientes' class="bttn_class" />
                                </td>
                                <th>Tel Celular:</th>
                                <td>
                                    <input type='text' id='txtTelefono_celular_clientes' name='txtTelefono_celular_clientes' class="bttn_class" />
                                </td>
                            </tr><tr>
                                <th>Tel Celular WhatsApp:</th>
                                <td>
                                    <input type='text' id='txtTelefono_celular_whatsapp_clientes' name='txtTelefono_celular_whatsapp_clientes' class="bttn_class" />
                                </td>
                                <th>Email:</th>
                                <td>
                                    <input type='email' id='txtEmail_clientes' name='txtEmail_clientes' class="bttn_class" />
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset>
                        <legend>Datos Ubicaci&oacute;n</legend>
                        <table>
                            <tr>
                                <th>Direcci&oacute;n:</th>
                                <td>
                                    <input type='text' id='txtDireccion_clientes' name='txtDireccion_clientes' class="bttn_class" />
                                </td>
                                <th>Barrio:</th>
                                <td>
                                    <input type='text' id='txtBarrio_clientes' name='txtBarrio_clientes' class="bttn_class" />
                                </td>
                            </tr>
                            <tr>
                                <th>Ciudad:</th>
                                <td>
                                    <input type='text' id='txtCiudad_n_clientes' name='txtCiudad_n_clientes' class="bttn_class"  style='text-transform:uppercase;'/>
                                    <input type='hidden' id='hdnCiudad_clientes' name='hdnCiudad_clientes' class=""/>
                                </td>
                                <th>Zona:</th>
                                <td>
                                    <select id='selZona_clientes' name='selZona_clientes'>
                                        <option value=''>...</option>
                                        <option value='Norte'>Norte</option>
                                        <option value='Centro'>Centro</option>
                                        <option value='Sur'>Sur</option>
                                        <option value='Oeste'>Oeste</option>
                                        <option value='Oriente'>Oriente</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset>
                        <legend>Datos Referido</legend>
                        <table>
                            <tr>
                                <th>Donde escucho sobre nosotros?</th>
                                <td>
                                    <select id='selOrigenReferido' name='selOrigenReferido'>
                                        <option value='1'>Amiga/o</option>
                                        <option value='2'>Facebook</option>
                                        <option value='3'>Instagram</option>
                                        <option value='4'>Revistas</option>
                                        <option value='5'>Otro</option>
                                        <option value='6' selected='selected'>N/A</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id='trDatosReferido' style='display:none;'>
                                <th>
                                    <label id='lblOrReferido_1' style='display:none;'>Nombre Cliente Que Refirio:</label>
                                    <label id='lblOrReferido_2' style='display:none;'>Cual:</label>
                                </th>
                                <td>
                                    <input type='text' id='txtOrReferido_1' name='txtOrReferido_1' style='display:none;text-transform:uppercase;' class='bttn_class'/>
                                    <label id='lblNameOrReferido_1'></label>
                                    <input type='hidden' id='hdnOrReferido_1' name='hdnOrReferido_1' />
                                    <input type='text' id='txtOrReferido_2' name='txtOrReferido_2'  style='display:none;' class='bttn_class'/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset>
                            <legend>Otros Datos</legend>
                            <table>
                            </table>
                    </fieldset>
                </form>
            </div>
        </div>
        
        <div id='divShowHistorialCliente' title='Datos Cliente'>
            <div id="divPreloadShowHistorialCliente" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
            <div id="divBodyShowHistorialCliente" style='font-family:verdana;font-size:x-small;'>
            <div id='divTabsHistorialCliente'>
                <ul>
                    <li><a href='#divTabHC1'>D. Basicos</a></li>
                    <li><a href='#divTabHC2'>Facturas</a></li>
                    <li><a href='#divTabHC3'>Referidos</a></li>
                    <li><a href='#divTabHC4'>Devoluciones</a></li>
                    <li><a href='#divTabHC5'>Plan Pagos</a></li>
                </ul>
                <div id='divTabHC1'>
                    <div id="divPreload_TabHC1" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
						<div id='divBody_TabHC1'>
							<form id="frmTabHC1" action="" method="post" autocomplete="on">
								<input type="hidden" id="hdnid_TabHC1" name="hdnid_TabHC1"/>
									<fieldset>
										<legend>Datos Basicos</legend>
										<table width=100%>
											<tr>
												<th>Tipo Doc:</th>
												<td>
													<select id='selTipoDocumento_clientes_TabHC1' name='selTipoDocumento_clientes_TabHC1'>
														<option value='25' selected=selected>Cedula Ciudadania</option>
														<option value='27'>Cedula Extranjeria</option>
													</select>
												</td>
												<th>Identificaci&oacute;n:</th>
												<td>
													<input type='text' id='txtIdentificacion_clientes_TabHC1' name='txtIdentificacion_clientes_TabHC1' class="bttn_class validate"  pattern=".{3,255}"/>
												</td>
											</tr>
											<tr>
												<th>Nombres:</th>
												<td>
													<input type='text' id='txtNombres_clientes_TabHC1' name='txtNombres_clientes_TabHC1' class="bttn_class validate" pattern=".{3,255}"/>
												</td>
												<th>Apellidos:</th>
												<td>
													<input type='text' id='txtApellidos_clientes_TabHC1' name='txtApellidos_clientes_TabHC1' class="bttn_class validate"  pattern=".{3,255}"/>
												</td>
												<th>Talla:</th>
												<td>
													<select id='selTalla_clientes_TabHC1' name='selTalla_clientes_TabHC1'>
														<option value=''>...</option>
														<option value='33.00'>&nbsp;33&nbsp;</option>
														<option value='33.50'>&nbsp;33/5&nbsp;</option>
														<option value='34.00'>&nbsp;34&nbsp;</option>
														<option value='34.50'>&nbsp;34/5&nbsp;</option>
														<option value='35.00'>&nbsp;35&nbsp;</option>
														<option value='35.50'>&nbsp;35/5&nbsp;</option>
														<option value='36.00'>&nbsp;36&nbsp;</option>
														<option value='36.50'>&nbsp;36/5&nbsp;</option>
														<option value='37.00'>&nbsp;37&nbsp;</option>
														<option value='37.50'>&nbsp;37/5&nbsp;</option>
														<option value='38.00'>&nbsp;38&nbsp;</option>
														<option value='38.50'>&nbsp;38/5&nbsp;</option>
														<option value='39.00'>&nbsp;39&nbsp;</option>
														<option value='39.50'>&nbsp;39/5&nbsp;</option>
														<option value='40.00'>&nbsp;40&nbsp;</option>
														<option value='40.50'>&nbsp;40/5&nbsp;</option>
														<option value='41.00'>&nbsp;41&nbsp;</option>
														<option value='41.50'>&nbsp;41/5&nbsp;</option>
														<option value='42.00'>&nbsp;42&nbsp;</option>
														<option value='42.50'>&nbsp;42/5&nbsp;</option>
														<option value='43.00'>&nbsp;43&nbsp;</option>
														<option value='43.50'>&nbsp;43/5&nbsp;</option>
														<option value='44.00'>&nbsp;44&nbsp;</option>
														<option value='44.50'>&nbsp;44/5&nbsp;</option>
														<option value='45.00'>&nbsp;45&nbsp;</option>
														<option value='45.50'>&nbsp;45/5&nbsp;</option>
													</select>
												</td>
											</tr>
											<tr>
												<th>F. Nacimiento:</th>
												<td>
													<select id='txtFecha_Nacimiento_clientes_TabHC1' name='txtFecha_Nacimiento_clientes_TabHC1'>
														<option value=''>...</option>
														<?php
															for($i=1;$i<=31;$i++){
														?>
																<option value='<?php echo $i; ?>'>&nbsp;<?php echo $i; ?>&nbsp;</option>
														<?php
															}
														?>
													</select>
													<select id='txtFecha_Nacimiento_clientes_mes_TabHC1' name='txtFecha_Nacimiento_clientes_mes_TabHC1'>
														<option value=''>...</option>
														<?php
															$arr_m = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
															for($i=1;$i<=12;$i++){
														?>
																<option value='<?php echo $i; ?>'>&nbsp;<?php echo $arr_m[$i]; ?>&nbsp;</option>
														<?php
															}
														?>
													</select>
												</td>
												<th>Estado:</th>
												<td>
													<select id='selEstado_clientes_TabHC1' name='selEstado_clientes_TabHC1'>
														<option value='A'>Activo</option>
														<option value='I'>Inactivo</option>
													</select>
												</td>
											</tr>
										</table>
									</fieldset>
									<fieldset>
										<legend>Datos Contacto</legend>
										<table>
											<tr>
												<th>Tel Fijo:</th>
												<td>
													<input type='text' id='txtTelfono_fijo_clientes_TabHC1' name='txtTelfono_fijo_clientes_TabHC1' class="bttn_class" />
												</td>
												<th>Tel Celular:</th>
												<td>
													<input type='text' id='txtTelefono_celular_clientes_TabHC1' name='txtTelefono_celular_clientes_TabHC1' class="bttn_class" />
												</td>
												<th>Email:</th>
												<td>
													<input type='email' id='txtEmail_clientes_TabHC1' name='txtEmail_clientes_TabHC1' class="bttn_class" />
												</td>
											</tr>
										</table>
									</fieldset>
									<fieldset>
										<legend>Datos Ubicaci&oacute;n</legend>
										<table>
											<tr>
												<th>Direcci&oacute;n:</th>
												<td>
													<input type='text' id='txtDireccion_clientes_TabHC1' name='txtDireccion_clientes_TabHC1' class="bttn_class" />
												</td>
												<th>Barrio:</th>
												<td>
													<input type='text' id='txtBarrio_clientes_TabHC1' name='txtBarrio_clientes_TabHC1' class="bttn_class" />
												</td>
											</tr>
											<tr>
												<th>Ciudad:</th>
												<td>
													<input type='text' id='txtCiudad_n_clientes_TabHC1' name='txtCiudad_n_clientes_TabHC1' class="bttn_class"  style='text-transform:uppercase;'/>
													<input type='hidden' id='hdnCiudad_clientes_TabHC1' name='hdnCiudad_clientes_TabHC1' class=""/>
												</td>
												<th>Zona:</th>
												<td>
													<select id='selZona_clientes_TabHC1' name='selZona_clientes_TabHC1'>
														<option value=''>...</option>
														<option value='Norte'>Norte</option>
														<option value='Sur'>Sur</option>
														<option value='Oeste'>Oeste</option>
														<option value='Oriente'>Oriente</option>
													</select>
												</td>
											</tr>
										</table>
									</fieldset>
									<fieldset>
										<legend>Datos Referido</legend>
										<table>
											<tr>
												<th>Donde escucho sobre nosotros?</th>
												<td>
													<select id='selOrigenReferido_TabHC1' name='selOrigenReferido_TabHC1'>
														<option value='1'>Amiga/o</option>
														<option value='2'>Facebook</option>
														<option value='3'>Instagram</option>
														<option value='4'>Revistas</option>
														<option value='5'>Otro</option>
														<option value='6' selected='selected'>N/A</option>
													</select>
												</td>
											</tr>
											<tr id='trDatosReferido_TabHC1' style='display:none;'>
												<th>
													<label id='lblOrReferido_1_TabHC1' style='display:none;'>Nombre Cliente Que Refirio:</label>
													<label id='lblOrReferido_2_TabHC1' style='display:none;'>Cual:</label>
												</th>
												<td>
													<input type='text' id='txtOrReferido_1_TabHC1' name='txtOrReferido_1_TabHC1' style='display:none;text-transform:uppercase;' class='bttn_class'/>
													<label id='lblNameOrReferido_1_TabHC1'></label>
													<input type='hidden' id='hdnOrReferido_1_TabHC1' name='hdnOrReferido_1_TabHC1' />
													<input type='text' id='txtOrReferido_2_TabHC1' name='txtOrReferido_2_TabHC1'  style='display:none;' class='bttn_class'/>
												</td>
											</tr>
										</table>
									</fieldset>
									
									<fieldset>
										<legend>Otros Datos</legend>
										<table>
											<tr>
												<th>F. Corte Nomina 1:</th>
												<td>
													<select id='txtFecha_corte_nomina_1_clientes_TabHC1' name='txtFecha_corte_nomina_1_clientes_TabHC1'>
														<option value=''>...</option>
														<?php
															for($i=1;$i<=31;$i++){
														?>
																<option value='<?php echo $i; ?>'>&nbsp;<?php echo $i; ?>&nbsp;</option>
														<?php
															}
														?>
													</select>
												</td>
												<th>F. Corte Nomina 2:</th>
												<td>
													<select id='txtFecha_corte_nomina_2_clientes_TabHC1' name='txtFecha_corte_nomina_2_clientes_TabHC1'>
														<option value=''>...</option>
														<?php
															for($i=1;$i<=31;$i++){
														?>
																<option value='<?php echo $i; ?>'>&nbsp;<?php echo $i; ?>&nbsp;</option>
														<?php
															}
														?>
													</select>
												</td>
											</tr>
											<tr>
												<th>Referencia:</th>
												<td>
													<input type='text' id='txtReferencia_clientes_TabHC1' name='txtReferencia_clientes_TabHC1' class="bttn_class" />
												</td>
												<th>Tel. Referencia:</th>
												<td>
													<input type='text' id='txtTelefono_referencia_clientes_TabHC1' name='txtTelefono_referencia_clientes_TabHC1' class="bttn_class" />
												</td>
											</tr>
										</table>
									</fieldset>
							</form>
							<br/>
							<div>
								<input type='button' id='bttnTabHC1' value='Guardar Cambios' class='bttn_class'/>
							</div>
						</div>
					</div>
                <div id='divTabHC2'>
                        <div id="divPreload_TabHC2" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
                        <div id='divBody_TabHC2'>
                            <div id='divTabsHistFacturas'>
                                <ul>
                                    <li><a href='#divTabHisFac2'>Sist. Nuevo</a></li>
                                </ul>
                                <div id='divTabHisFac2'>
                                    <div id="divPreload_TabHisFac2" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
                                    <div id='divBody_TabHisFac2'></div>
                                </div>
                            </div>
                        </div>
                </div>
                <div id='divTabHC3'>
                        <div id="divPreload_TabHC3" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
                        <div id='divBody_TabHC3'>
                                <div id='divTabsReferidos'>
                                        <ul>
                                            <li><a href='#divTabReferidos2'>Sist. Nuevo</a></li>
                                        </ul>
                                        <div id='divTabReferidos2'>
                                                <div id="divPreload_TabReferidos2" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
                                                <div id='divBody_TabReferidos2'></div>
                                        </div>
                                </div>
                        </div>
                </div>
                <div id='divTabHC5'>
                    <div id="divPreload_TabHC5" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
                    <div id='divBody_TabHC5'>
                        <div id='divTabsPlanPagos'>
                            <ul>
                                <li><a href='#divTabPlanPagos2'>Sist. Nuevo</a></li>
                            </ul>
                            <div id='divTabPlanPagos2'>
                                <div id="divPreload_TabPlanPagos2" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
                                <div id='divBody_TabPlanPagos2'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div> <!-- fin div oculto -->
</div>