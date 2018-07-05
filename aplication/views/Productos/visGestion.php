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
            <img src="images/nuevoproducto.png"/>
            Productos
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
        <div id="divAddEdit" title="Ingresar/Editar Registro" style='text-transform:uppercase;'>
            <div id="divPreloadAddEdit" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
            <div id="divBodyAddEdit">
                <form id="frmAddEdit" enctype="multipart/form-data" action="aplication/router/?module=productos&action=gestion&event=create_row" method="POST" autocomplete="on">
                    <input type="hidden" id="hdnid" name="hdnid"/>
                    <fieldset class='ui-state-default ui-corner-all'>
                        <legend class='ui-widget-header  ui-corner-all'>Creacion de producto</legend>
                            <table>
                                 <tr>

                                    <th>
                                        Referencia:
                                    </th>
                                    <td> 
                                        <input type="text" id="txtreferencia" name="txtreferencia" class="ui-button ui-widget ui-corner-all validate ucwords" />
                                        &nbsp;<button id="bttnVerificarConsec" style="width:18px; height:18px;" title="Verificar Consecutivo"></button>
                                    </td>
                                    <th>
                                        Categoria: 
                                    </th>
                                     <td>
                                        <select id='selProductos_categorias' name='selProductos_categorias' class='validate'></select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Descripci&oacute;n: 
                                    </th>
                                     <td colspan=4>
                                        <input type="text" size='60' id="txtDescripcion" name="txtDescripcion" class="ui-button ui-widget ui-corner-all ucwords validate  ucwords"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Valor Compra: 
                                    </th>
                                     <td>
                                        <input type="number" id="txtvalor_compra" name="txtvalor_compra" class="ui-button ui-widget ui-corner-all validate"/>
                                    </td>
                                    <th>
                                        Valor: 
                                    </th>
                                     <td>
                                        <input type="number" id="txtvalor" name="txtvalor" class="ui-button ui-widget ui-corner-all validate"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Examinar:</th>
                                     <td> 
                                        <input type="file" id="txtExaminar" multiple="" name="txtExaminar" />
                                    </td>
                                    <td>
                                        <img src='' alt='imagen' width='70' heigth='70' id='espacioimagen' style='border:1px solid;'>
                                    </td>
                                 </tr>
                             </table> 
                    </fieldset>
                </form>
                <br/>
            </div>
        </div>
    </div>
</div>