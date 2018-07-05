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
            <img src="images/rol.png"/>
            Roles
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
        <div id="divAddEdit" title="Roles">
            <div id="divPreloadAddEdit" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
            <div id="divBodyAddEdit">
                <form id="frmAddEdit" action="" method="post">
                    <input type="hidden" id="hdnid" name="hdnid"/>
                    <table>
                        <tr>
                            <td>Nombre</td>
                        </tr>
                        <tr>
                            <td><input type="text" id="txtNombre_roles" name="txtNombre_roles" size="20" class="bttn_class validate" style="text-transform:uppercase;"/></td>
                        </tr>
                        
                        <tr>
                            <td>Estado</td>
                        </tr>
                        <tr>
                            <td>
                                <select id="selEstadoRoles" name="selEstadoRoles">
                                    <option value="A">Activo</option>
                                    <option value="I">Inactivo</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div> 
        <div id="divConfigAccesos" title="Configuracion de Roles">
            <div id="divPreloadConfigAccesos" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
            <div id="divBodyConfigAccesos">
                <form id="frmConfigRoles" action="" method="post">
                    <input type="hidden" id="hdncantrows" name="hdncantrows"/>
                    <div id="divHtmlConfigRoles"></div>
                </form>
            </div>
        </div> 
    </div>
</div>
