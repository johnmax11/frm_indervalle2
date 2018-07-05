<?php 
namespace MiProyecto;
    if(!isset($_SESSION)){
        session_start();
    }
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/router/index.php');
?>
<script type="text/javascript" src="js/Include/include.js"></script>
<script src="js/utilidades/Highcharts-4.0.4/js/highcharts.js"></script>
<script src="js/utilidades/Highcharts-4.0.4/js/highcharts-3d.js"></script>
<script src="js/utilidades/Highcharts-4.0.4/js/modules/exporting.js"></script>
<div id="divBody">
    <div align="right">
        <h1>
            <img src="images/pie.png"/>
            Reportes Ventas
        </h1>
        <hr/>
    </div>
    <br/>
    <div id="divPreload" align="center" style="color:red; font-family: verdana; font-size: xx-small;"></div>
        <div id='filtrofecha'>
            <table style='margin-left:35%;'>
                <tr>
                    <th>
                        A&ntilde;o
                    </th>
                    <td>
                        <select id="selAnio_ventas">
                            <?php
                                for($i=2014;$i<=(int)@date('Y');$i++){
                                    $class = '';
                                    if($i==(int)@date('Y')){
                                        $class = 'selected=selected';
                                    }
                            ?>
                                <option value="<?php echo $i; ?>" <?php echo $class; ?>><?php echo $i; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input id="bttnBuscar" type='button' class='bttn_class' value="Buscar">
                    </td>
                </tr>
            </table>
        </div>
        <div align="center">
            <div align="center"><p class="validateTips"></p></div>
            <div id="divAlert" class="divAlert" align="center"></div>
            <div id="divTabs">
                <ul>
                    <li><a href="#tab1">Ventas</a></li>
                    <li><a href="#tab2">Abonos</a></li>
                    <li><a href="#tab3">Ingresos</a></li>
                </ul>
                <div id="tab1">
                    <div id='divGraficoVentasFacturacion'></div>
                </div>
                <div id="tab2">
                    <div id='divGraficoVentasSeparados'></div>
                </div>
                <div id="tab3">
                    <div id='divGraficoVentasIngresos'></div>
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