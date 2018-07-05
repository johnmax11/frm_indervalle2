<?php
	namespace MiProyecto;
    if(!isset($_SESSION)){
        session_start();
    }
    require_once('utiVerificaInicioSession.php');
    
    $mod_e = $_GET['module'];
    $act_e = $_GET['action'];
    
    $_GET['module'] = "seguridad";
    $_GET['action'] = "set_accesos";
    $_GET['event'] = "verificaraccesosrol";
    
    
    //$_POST['bttnAction'] = 'verificaraccesosrol';
    //require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/utiSetVarSession.php');
    //require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/control/Seguridad/con_set_accesos.php');
    //require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/utilidades.php');
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/router/index.php');
    
    $_GET['module'] = $mod_e;
    $_GET['action'] = $act_e;
?>
<script>
    $(document).ready(function(){
        var icons = {
                header: "ui-icon-circle-arrow-e",
                headerSelected: "ui-icon-circle-arrow-s"
        };
        $( "#divMenu" ).accordion({icons: icons,heightStyle: "content"});
        $('.uMenu').menu();
        $.fn.cargar_condiciones_iniciales();
    });
</script>
<div id="divMenu" style='display:none;'>
    <?php
        // set obj de accesos rol //
        $objSsn = new utisetVarSession();
        $arrDatos = $objSsn->get_ssn_accesos_rol();
        for($i=0;$i<count($arrDatos['rows']);$i++){
            if($arrDatos['rows'][$i]['visible']=='N'){
                continue;
            }
    ?>
            <?php
                if($arrDatos['rows'][$i]['seguridad_programas_id']==null){
                    if($i<count($arrDatos['rows'])&&$i>1){
            ?>
                        </ul>
                       </div>
            <?php
                    }
            ?>
                    <h3><?php echo ucwords(strtolower($arrDatos['rows'][$i]['seguridad_modulos_alias'])); ?></h3>
                    <div>
                        <ul class="uMenu">
            <?php
                }else{
            ?>  
                            <li>
                                <a href="index.php?module=<?php echo ucwords(strtolower($arrDatos['rows'][$i]['seguridad_modulos_nombre'])); ?>&action=<?php echo ucwords(strtolower($arrDatos['rows'][$i]['seguridad_programas_nombre']));?>" onclick="$.fn.writeCookie('pmenu',$('#divMenu').accordion('option','active'),0);">
                                    <span class="ui-icon <?php echo $arrDatos['rows'][$i]['imagen']; ?>"></span>
                                    <?php
                                        $str_col_e = '';
                                        if($_REQUEST['module'] == ucwords(strtolower($arrDatos['rows'][$i]['seguridad_modulos_nombre'])) &&
                                           $_REQUEST['action'] == ucwords(strtolower($arrDatos['rows'][$i]['seguridad_programas_nombre']))
                                        ){
                                            $str_col_e = 'font-weight:bold;';
                                        }
                                    ?>
                                    <span style="<?php echo $str_col_e; ?>"><?php echo ucwords(strtolower($arrDatos['rows'][$i]['seguridad_programas_alias'])); ?></span>
                                </a>
                            </li>
            <?php
                }
                if($i==count($arrDatos['rows'])){
            ?> 
                         </ul>
                     </div>
            <?php
                }
            ?>
    <?php
        }
    ?>
</div>
</div>
<p id='divPreloadRueda' align=center>
    <img src='images/preloader_rueda.gif' />
</p>
<script>
    $(document).ready(function(){
        $('#divPreloadRueda').remove();
        $('#divMenu').show();
    });
</script>
