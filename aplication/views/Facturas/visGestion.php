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
            <img src="images/document.png"/>
            Facturas
        </h1>
        <hr/>
    </div>
    <br/>
    <div id="divPreload" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
    <div align="center">
        <div align="center"><p class="validateTips"></p></div>
        <div id="divAlert" class="divAlert" align="center"></div>
        <div id="divFiltros_tab1">
            Tipo:
            <select id="selTipo_rango" name="selTipo_rango">
                <option value="M">Por Mes</option>
                <option value="A">Por A&ntilde;o</option>
                <option value="R" selected="selected">Por Rango</option>
            </select>
            <span id="divMes_filtro_tab1" style='display:none;'>
                <select id="selMes_filtro_tab1" name="selMes_filtro_tab1">
                    <option value="01" <?php echo (@date('m')==1?"selected='selected'":''); ?>">Enero</option>
                    <option value="02" <?php echo (@date('m')==2?"selected='selected'":''); ?>">Febrero</option>
                    <option value="03" <?php echo (@date('m')==3?"selected='selected'":''); ?>">Marzo</option>
                    <option value="04" <?php echo (@date('m')==4?"selected='selected'":''); ?>">Abril</option>
                    <option value="05" <?php echo (@date('m')==5?"selected='selected'":''); ?>">Mayo</option>
                    <option value="06" <?php echo (@date('m')==6?"selected='selected'":''); ?>">Junio</option>
                    <option value="07" <?php echo (@date('m')==7?"selected='selected'":''); ?>">Julio</option>
                    <option value="08" <?php echo (@date('m')==8?"selected='selected'":''); ?>">Agosto</option>
                    <option value="09" <?php echo (@date('m')==9?"selected='selected'":''); ?>">Septiembre</option>
                    <option value="10" <?php echo (@date('m')==10?"selected='selected'":''); ?>">Octubre</option>
                    <option value="11" <?php echo (@date('m')==11?"selected='selected'":''); ?>">Noviembre</option>
                    <option value="12" <?php echo (@date('m')==12?"selected='selected'":''); ?>">Diciembre</option>
                </select>
            </span>
            <span id="divMes_filtro_tab2" style='display:none;'>
                <select id='selAnio_filtro_tab1' name='selAnio_filtro_tab1'>
                    <option value='2014' selected='selected'>2014</option>
                    <option value='2015'>2015</option>
                </select>
            </span>
            <span id="divMes_filtro_tab3">
                De:
                <input type='text' id='txtFechaDe_filtro_tab1' name='txtFechaDe_filtro_tab1' size='11' value='<?php echo @date('Y-m-d'); ?>' class="ui-button ui-widget ui-corner-all"/>
                Hasta:
                <input type='text' id='txtFechaHasta_filtro_tab1' name='txtFechaHasta_filtro_tab1' size='11' value='<?php echo @date('Y-m-d'); ?>' class="ui-button ui-widget ui-corner-all"/>
            </span>
            <input type='button' id='bttn_tab_1' class='bttn_class' value='Buscar'/>
        </div>
        <br/>
        <div id='divTabsPrincipal'>
            <table id='griddatoscx'></table>
            <div id='pagerdatoscx'></div>
        </div>
        <br/>
    </div>
    <div id="divOculto" style="display:none;">
        <div id="divAddEdit" title="Crear Documento">
            <div id="divBodyAddEdit" style='font-family:verdana;font-size:9pt;'>
                <form id="frmAddEdit" action="" method="post" autocomplete="on">
                    <table style="width:100%;font-size:medium;">
                        <tr>
                            <td>
                                Vendedor:
                                <span style='color:red;'><?php echo utisetVarSession::get_ssn_usuario_users(); ?></span>
                            </td>
                            <td>
                                Fecha:
                                <span style='color:red;'><?php echo @date('Y-m-d'); ?></span>
                            </td>
                        </tr>
                    </table>
                    <fieldset class='ui-state-default ui-corner-all'>
                        <legend class='ui-widget-header  ui-corner-all'>Proceso</legend>
                        <table style='width:100%;'>
                            <tr>
                                <td>
                                    Factura
                                    <input type='radio' id='radTipoProcesoFAC' name='radTipoProceso' checked="checked" class="ui-button ui-widget ui-corner-all" value="F"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset class="ui-state-default ui-corner-all">
                        <legend class='ui-widget-header  ui-corner-all'>Datos Cliente</legend>
                        &nbsp;<button id="bttnCreateClientes" style="width:18px; height:18px;" title="Crear Clientes"></button>
                        &nbsp;<button id="bttnSearchClientes" style="width:18px; height:18px;" title="Buscar Clientes"></button>
                        <table style='width:100%;'>
                            <tr>
                                <td>Identificaci&oacute;n:</td>
                                <td>
                                    <input type='text' id='txtIdentificacion_cliente' class="ui-button ui-widget ui-corner-all" class="validate"/>
                                    <input type='hidden' id='hdnIdentificacion_cliente' name='hdnIdentificacion_cliente' class="validate"/>
                                </td>
                                <td>Cli. Sin Datos:</td>
                                <td>
                                    <input type='checkbox' id='chkClienteSinDatos'/>
                                </td>
                            </tr>
                            <tr>
                                <td>Nombre(s):</td>
                                <td><input type='text' id='txtNombres_cliente' class="ucwords ui-button ui-widget ui-corner-all" class="validate"/></td>
                                <td>Apellido(s):</td>
                                <td><input type='text' id='txtApellidos_cliente' class="ucwords ui-button ui-widget ui-corner-all" class="validate"/></td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset class="ui-state-default ui-corner-all">
                        <legend class='ui-widget-header  ui-corner-all'>Detalle</legend>
                        <input type="hidden" id="hdnContRowsDetalle" name="hdnContRowsDetalle" value="0" />
                        &nbsp;<button id="bttnAgregar" style="width:18px; height:18px;" title="Agregar Registro"/>Agregar Registro</button>
                        <table id="tblDetalleFactura" style="width:100%;">
                            <tr id="trFooter">
                                <thead id="tHead_tblDetalleFactura"></thead>
                                <tbody id="tBody_tblDetalleFactura">
                                </tbody>
                                <tfoot>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th><label id="lblSubtotalFooter" style="font-size:10pt;">0</label></th>
                                    <th><label id="lblDctoFooter" style="font-size:10pt;">0</label></th>
                                    <th><label id="lblTotalFooter" style="font-size:10pt;">0</label></th>
                                    <th>&nbsp;</th>
                                </tfoot>
                            </tr>
                        </table>
                        <center>Observaciones: <textarea id="tarObservaciones" name="tarObservaciones" cols="100" rows="1"></textarea></center>
                    </fieldset>
                    <fieldset class="ui-state-default ui-corner-all">
                        <legend class='ui-widget-header  ui-corner-all'>Forma Pago</legend>
                        <table style="width:100%;">
                            <tr>
                                <th>
                                    Pago Total:<input type="radio" id="radFormaPagoPT" name="radFormaPago" value="PT" checked="checked"/>
                                </th>
                                <th>
                                    Abono:<input type="radio" id="radFormaPagoAB" name="radFormaPago" value="AB"/>
                                    <input style="display:none;" type="text" id="txtValorAbono" name="txtValorAbono" size="7" value="0"  class="ui-button ui-widget ui-corner-all"/>
                                </th>
                                <th>
                                    Sin Abono:<input type="radio" id="radFormaPagoSA" name="radFormaPago" value="SA"/>
                                </th>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset class="ui-state-default ui-corner-all">
                        <legend class='ui-widget-header  ui-corner-all'>Finalizar Pago</legend>
                        <table style="width:100%;">
                            <tr>
                                <td style="width:50%;">
                                    <table style="width:100%;">
                                        <tr>
                                            <td align="right">Total Documento:</td>
                                            <td align="left" style="border-right-style:solid;border-right-width:1px;">$ <label id="lblTotalRecibir" style="font-size:14pt;color:blue;">0</label></td>
                                        </tr>
                                        <tr>
                                            <td align="right">Total A Recibir:</td>
                                            <td align="left" style="border-right-style:solid;border-right-width:1px;">$ <label id="lblTotalARecibir" style="font-size:14pt;color:green;">0</label></td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width:50%;">
                                    <table style="width:100%;">
                                        <tr>
                                            <td align="center">
                                                Dinero Recibido:
                                                <input type="text" id="txtDineroRecibido" name="txtDineroRecibido" class="ui-button ui-widget ui-corner-all" onkeyup="this.value=number_format(this.value);"/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </form>
            </div>
        </div>
    </div> <!-- fin div oculto -->
</div>