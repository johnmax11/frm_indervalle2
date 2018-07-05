<?php
    if(!isset($_SESSION)){
        session_start();
    }
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/control/Usuarios/conLogin.php');
?>
<script type="text/javascript" src="js/Usuarios/visLogin.js"></script>
<div id="divBody" align='center'>
    <div align="right">
        <h1>
            <img src="images/login.gif"/>
            Login
        </h1>
        <hr/>
    </div>
    <br/>
    <div id="divPreload" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
    <div align="center" style='width:80%'>
        <div align="center"><p class="validateTips"></p></div>
        <div id="divAlert" class="divAlert" align="center"></div>
        <br/>
        <fieldset>
            <legend><i>&nbsp;Ingreso al Sistema&nbsp;</i></legend>
            <form id="frmMisDatos" action="" method="post">
                    <table align='center'>
                        <tr>
                            <td>
                                Usuario:<br/>
                                <input type='text' id="txtUser" name="txtUser" class="class_bttn"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Contrase&ntilde;a:<br/>
                                <input type='password' id="txtPass" name="txtPass" class="class_bttn"/>
                            </td>
                        </tr>
                        <tr id="trOficina" style="display:none;">
                            <td>
                                Sucursal:<br/>
                                <select id="selOficina" name="selOficina" class="ui-button ui-widget ui-state-default ui-corner-all"><option value="">Seleccione...</option></select>
                            </td>
                        </tr>
                        <tr id="trBd" style="display:none;">
                            <td>
                                Datos:<br/>
                                <select id="selBd" name="selBd" class="ui-button ui-widget ui-state-default ui-corner-all"><option value="">Seleccione...</option></select>
                            </td>
                        </tr>
                    </table>
            </form>
            <div><br/>
                <input type="button" id="bttnLogin" value="Ingresar al Sistema" class="class_bttn"/>
            </div>
            <br/><br/>
        </fieldset>
        <br/>
    </div>
</div>
