<?php
    if(!isset($_SESSION)){
        session_start();
    }
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/router/index.php');
?>
<script type="text/javascript" src="js/Include/include.js"></script>
<div id="divBody">
    <div align="right">
        <h1>
            <img src="images/metas.png"/>
            Mis Metas
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
                    <input type="hidden" id="hdnid" name="hdnid"/>
                    <table>
                        <tr>
                            <td>Nombre</td>
                        </tr>
                        <tr>
                            <td><input type="text" id="txtNombre_mi_meta" name="txtNombre_mi_meta" pattern=".{3,255}" size="20" class="bttn_class validate" style="text-transform:uppercase;"/></td>
                        </tr>
                        <tr>
                            <td>Descripci&oacute;n</td>
                        </tr>
                        <tr>
                            <td>
                                <textarea type="text" id="txtdescripcion_mi_meta" name="txtdescripcion_mi_meta" class="bttn_class validate"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Fecha Cumplimiento</td>
                        </tr>
                        <tr>
                            <td><input type="text" id="txtFecha_mi_meta" name="txtFecha_mi_meta" size="11" maxlength="11" class="bttn_class validate"/></td>
                        </tr>
                        <tr>
                            <td>
                                Estado
                            </td>
                       </tr>
                       <tr>     
                            <td>
                                <select id="selEstado" name="selEstado">
                                    <option value="A" selected="selected">Activo</option>
                                    <option value="I">Inactivo</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
