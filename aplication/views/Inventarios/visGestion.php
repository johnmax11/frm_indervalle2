<?php

namespace MiProyecto;

if (!isset($_SESSION)) {
    session_start();
}
require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'] . '/aplication/router/index.php');
?>
<script type="text/javascript" src="js/Include/include.js"></script>
<style>

    input{
        text-transform: uppercase;
    }

    .ui-autocomplete {
        max-height: 100px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
    }
    
    .camposPequenios{
        width: 10%;
    }
    
    .camposGrandes{
        width: 40%;
    }

</style>
<div id="divBody">
    <div align="right">
        <h1>
            <img src="images/inventarios.png"/>
            Inventarios
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
            <div id="divBodyAddEdit">
                <form id="frmAddEdit" action="" method="post" autocomplete="on">
                    <input type='hidden' name='hdnid'>
                    <div id="divBodyAddEdit_dinamic">
                        <table style="width:100%;" class='ui-jqgrid ui-widget ui-widget-content ui-corner-all'>
                            <thead>
                                <tr class='ui-state-default ui-th-column ui-th-ltr'>
                                    <th>
                                        &nbsp;
                                    </th>
                                    <th>
                                        REFERENCIA
                                    </th>
                                    <th class="camposGrandes">
                                        DESCRIPCION
                                    </th>
                                    <th class="camposPequenios">
                                        CANTIDAD
                                    </th>
                                    <th>
                                        DESTINO
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody id='tBodyInventario'>
                            </tbody>
                            <tfoot>
                            <input type="hidden" name='contador' id='contador' value=''>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>