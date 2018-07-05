<?php
namespace MiProyecto;
    if(!isset($_SESSION)){
        session_start();
    }
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/router/index.php');
?>
<script type="text/javascript" src="js/Home/visHome.js"></script>
<div id="divBody">
    <div align="right">
        <h1>
            <img src="images/login.gif"/>
            Home del Sistema
        </h1>
        <hr/>
    </div>
    <br/>
    <div id="divPreload" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
    <div align="center">
        <div align="center"><p class="validateTips"></p></div>
        <div id="divAlert" class="divAlert" align="center"></div>
        <br/>
        <div align="left">
            <p><h1>Ultimos 5 Ingresos Al Sistema</h1></p>
        </div>
        <div id="divInformacion" align="left"></div>    
        <br/>
    </div>
    <div id="divOculto" style="display:none;">
        <div id="divCambioPassword" title="Cambio de Clave">
            <div id="divPreloadCambioPassword" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
            <div id="divBodyCambioPassword">
                <form id="frmCambioPassword">
                    <table>
                        <tr>
                            <td>Clave Actual:</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="password" id="txtClave_antigua" name="txtClave_antigua" pattern=".{5,25}" class="bttn_class validate"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Clave Nueva:</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="password" id="txtClave_nueva" name="txtClave_nueva" pattern=".{5,25}" class="bttn_class validate"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Confirmar Clave:</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="password" id="txtConfirmar_clave" name="txtConfirmar_clave" pattern=".{5,25}" class="bttn_class validate"/>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
