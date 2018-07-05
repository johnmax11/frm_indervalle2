<?php

namespace MiProyecto;

if (!isset($_SESSION)) {
    session_start();
}
require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'] . '/aplication/router/index.php');
?>
<script type="text/javascript" src="js/Include/include.js"></script>
<div id="divBody">
    <div align="right">
        <h1>
            <img src="images/tesoreria.png"/>
            Tesoreria
        </h1>
        <hr/>
    </div>
    <br/>
    <div id="divPreload" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
    <div align="center">
        <div align="center"><p class="validateTips"></p></div>
        <div id="divAlert" class="divAlert" align="center"></div>
        <br/>
        <div id='divTabsPrincipal'>
        <div id="divFiltros_tab1">
            Tipo:
            <select id="selTipo_rango" name="selTipo_rango">
                <option value="M">Por Mes</option>
                <option value="A">Por A&ntilde;o</option>
                <option value="R">Por Rango</option>
            </select>
            <span id="divMes_filtro_tab1">
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
            <span id="divMes_filtro_tab3" style='display:none;'>
                De:
                <input type='text' id='txtFechaDe_filtro_tab1' name='txtFechaDe_filtro_tab1' size='11' value='<?php echo @date('Y-m-d'); ?>'/>
                Hasta:
                <input type='text' id='txtFechaHasta_filtro_tab1' name='txtFechaHasta_filtro_tab1' size='11' value='<?php echo @date('Y-m-d'); ?>'/>
            </span>
            <input type='button' id='bttn_tab_1' class='bttn_class' value='Buscar'/>
        </div>
            <br>
            <table id='griddatoscx'></table>
            <div id='pagerdatoscx'></div>
        </div>
        <br/>
    </div>
    <div id="divOculto" style="display:none;">
        <div id="divAddEdit" title="Tesoreria">
            <div id="divBodyAddEdit" style='font-family:verdana;font-size:9pt;'>
                <form id="frmAddEdit" action="" method="post" autocomplete="on">
                </form>
            </div>
        </div>
    </div> <!-- fin div oculto -->
</div>