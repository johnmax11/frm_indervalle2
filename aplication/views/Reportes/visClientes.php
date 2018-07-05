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
            <img src="images/misdatos.png"/>
            Reportes Clientes
        </h1>
        <hr/>
    </div>
    <br/>
        <div id='filtrofecha'>
            <table style='margin-left:35%;'>
            </table>
        </div>
        <div align="center">
            <div align="center"><p class="validateTips"></p></div>
            <div id="divAlert" class="divAlert" align="center"></div>
            <div id="divTabs">
                <ul>
                    <li><a href="#tab1">Compras</a></li>
                </ul>
                <div id="tab1">
                    <div id="divPreload_tab1" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
                    <table id='griddatoscx'></table>
                    <div id='pagerdatoscx'></div>
                </div>
            </div>
            <br/>
        </div>
        <div id="divOculto" style="display:none;">
            <div id="divAddEdit" title="Ingresar/Editar Registro">
                <div id="divPreloadAddEdit" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
                <div id="divBodyAddEdit">
                    <form id="frmAddEdit" action="" method="post" autocomplete="on">
                    </form>
                </div>
            </div>
        </div>
</div>