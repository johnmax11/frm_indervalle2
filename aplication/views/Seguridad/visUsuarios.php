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
            <img src="images/user.png"/>
            Usuarios
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
                            <td>Usuario</td>
                        </tr>
                        <tr>
                            <td><input type="text" id="txtUsuario_usuario" name="txtUsuario_usuario" pattern=".{4,15}" size="20" class="bttn_class validate" required/></td>
                        </tr>
                        <tr>
                            <td>Nombre</td>
                        </tr>
                        <tr>
                            <td><input type="text" id="txtNombre_usuario" name="txtNombre_usuario" pattern=".{3,255}" size="20" class="bttn_class validate" style="text-transform:uppercase;" required/></td>
                        </tr>
                        <tr>
                            <td>Apellidos</td>
                        </tr>
                        <tr>
                            <td><input type="text" id="txtApellidos_usuario" name="txtApellidos_usuario" pattern=".{3,255}" size="20" class="bttn_class validate" style="text-transform:uppercase;" required/></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                        </tr>
                        <tr>
                            <td><input type="email" id="txtEmail_usuario" name="txtEmail_usuario" pattern=".{3,255}" size="20" class="bttn_class validate" required/></td>
                        </tr>
                        <tr>
                            <td>Seguridad Roles</td>
                        </tr>
                        <tr>
                            <td>
                                <select id="selSeguridad_roles" name="selSeguridad_roles" class="validate">
                                    <option value="">Seleccione...</option>
                                </select>  
                            </td>
                        </tr>
                        <tr>
                            <td>Estado</td>
                        </tr>
                        <tr>
                            <td>
                                <select id="selEstado_usuario" name="selEstado_usuario" class="validate">
                                    <option value="A">Activo</option>
                                    <option value="I">Inactivo</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Resetear Contrase&ntilde;a</td>
                        </tr>
                        <tr>    
                            <td>
                                <input type="checkbox" id="chkResetPassword"/>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
