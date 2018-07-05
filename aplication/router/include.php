<?php
namespace MiProyecto{
    if(!isset($_SESSION)){
        session_start();
    }
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/views/Utils/utiVerificaInicioSession.php');
    if(!isset($_GET['module']) || !isset($_GET['action'])){
        header('location: index.php?module=Home&action=Home');
    }
    $mod = $_GET['module'];
    $act = $_GET['action'];
    /* verificamos si hay que validar la visibilidad */
    if(!isset($_GET['event'])){
        if(!utilidades::verifica_rol_acceso_in_array(new utisetVarSession(),$_REQUEST,'visible')){
            if(utilidades::isAjax()){									
                            utilidades::set_response(array('msj'=>"Acceso denegado a este recurso"),true);							
            }else{				
                echo "<p>Acceso denegado a este recurso <a href='".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB']."/index.php?module=Home&action=Home'>Volver</a></p>";
                /*header('location: '.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'].'/index.php?module=Home&action=Home');*/			
            }			
            exit;
        }
    }else{		
        if(isset($_GET['event']) && $_GET['event']=='validate_crud'){			
            $arr_crud = array(false,false,false,false);			
            /*echo print_r($_REQUEST);*/			
            /**validamos el create*/			
            if(utilidades::verifica_rol_acceso_in_array(new utisetVarSession(),$_REQUEST,'insertar')){
                $arr_crud[0] = true;			
            }			
            /**validamos el read*/			
            if(utilidades::verifica_rol_acceso_in_array(new utisetVarSession(),$_REQUEST,'seleccionar')){
                $arr_crud[1] = true;			
            }			
            /**validamos el update*/			
            if(utilidades::verifica_rol_acceso_in_array(new utisetVarSession(),$_REQUEST,'actualizar')){				
                $arr_crud[2] = true;			
            }			
            /**validamos el delete*/			
            if(utilidades::verifica_rol_acceso_in_array(new utisetVarSession(),$_REQUEST,'borrar')){				
                $arr_crud[3] = true;			
            }			
            utilidades::set_response(array($arr_crud));			
            exit;		
        }else{
            if(isset($_GET['event'])){
                if(!utilidades::verifica_rol_acceso_in_array(new utisetVarSession(),$_REQUEST,'eventos')){
                    if(utilidades::isAjax()){
                        utilidades::set_response(array('msj'=>"Acceso denegado a este recurso"),true);
                    }else{					
                        echo "<p>Acceso denegado a este recurso <a href='".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB']."/index.php?module=Home&action=Home'>Volver</a></p>";
                        /*header('location: '.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'].'/index.php?module=Home&action=Home');*/
                    }
                    exit;
                }
            }
        }
    }
    if(file_exists($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/control/'.ucwords(strtolower($mod)).'/'.'con_'.strtolower($act).'.php')){
        /****/
        require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/control/'.ucwords(strtolower($mod)).'/'.'con_'.strtolower($act).'.php');
    }else{
        if(isset($_REQUEST['event']) && !isset($_REQUEST['row_base'])){
            utilidades::set_response(array('msj'=>'Error: Action/Control no existe!','error'=>true));
        }else{
            if(isset($_REQUEST['event']) && isset($_REQUEST['row_base']) && $_REQUEST['event'] == 'create_row'){
                    $_REQUEST['action_base'] = $_REQUEST['action'];
                    $_REQUEST['action'] = 'base';
                    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/control/Base/con_base.php');
            }
        }
    }
}