<?php
    namespace MiProyecto;
    if(!isset($_SESSION)){
        session_start();
    }
    /**limipiamos la variable de session cache para el proceso*/
    unset($_SESSION['querys_cache']);

    $mod_e = $_GET['module'];
    $act_e = $_GET['action'];

    $_GET['module'] = "seguridad";
    $_GET['action'] = "usuarios_track";
    $_GET['event'] = "search_historial";
    $_GET['not_event'] = true;
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/router/index.php');
	/****/
    $obj_con_usuarios_track = new con_usuarios_track();
    $arrDatos = $obj_con_usuarios_track->search_historialEvent();
	
    $_GET['module'] = $mod_e;
    $_GET['action'] = $act_e;
?>
<style>
    #menu_h td a:hover{
        color:white;
    } 
</style>
<div style="width:100%;">
    <table width="100%" border="0">
        <tr id="menu_h" class="ui-state-default">
            <td width="9.09%">
                Recientes:
            </td>
            <?php
                if(isset($arrDatos['rows'])){
                    for($i=0;$i<count($arrDatos['rows']);$i++){
                        $arrUrl = explode('?',$arrDatos['rows'][$i]['url_track']);
                        $arrUrl = explode('&',$arrUrl[1]);
            ?>
                    <?php
                        if(strpos($arrUrl[0],'action')){
                            $arrF = explode('=',$arrUrl[0]);
                        }else{
                            $arrF = explode('=',$arrUrl[1]);
                        }
                    ?>
                    <td width="9.09%">
                        <center>
                            <a class="ui-menu-item" href="<?php echo $arrDatos['rows'][$i]['url_track']; ?>">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td>
                                            <img src='images/export.gif' title="Ir a programa"/>
                                            
                                        </td>
                                        <td>
                                            <div class="ui-icon <?php echo $arrDatos['rows'][$i]['class_icon']; ?>"></div>
                                            
                                        </td>
                                        <td>
                                            <?php echo $arrF[1]; ?>
                                        </td>
                                    </tr>
                                </table>
                            </a>
                        </center>
                    </td>
            <?php
                    }
                }
            ?>
        </tr>
    </table>
</div>
<hr/>
