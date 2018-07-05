<?php
namespace MiProyecto{
    error_reporting(E_ALL);
    ini_set('error_reporting', E_ALL | ~E_WARNING);
    ini_set('display_errors',1);
    if(!isset($_SESSION)){
        session_start();
    }
    /**limipiamos la variable de session cache para el proceso*/
    /**incluimos el archivo manejador de errores**/
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/error/error_handler.php');
    set_error_handler(__NAMESPACE__."\myErrorHandler::myErrorHandler");
    /** verfiicamos si existe session del usuario **/	
    if(!isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_USERS'])){		
        echo json_encode(
            array(
                'error'=>true,
                'msj'=>'Usuario con session inactiva, se redireccionara para iniciar session de nuevo',
                'code'=>'location.href="http://demo.vamels.com"'
            )
        );
        exit;
    }
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/views/Utils/utiVerificaInicioSession.php');
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/utiSetVarSession.php');
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/utilidades.php');
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/utilidadesSQL.php');
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/router/autoload.php');
    if(isset($_REQUEST['module']) && isset($_REQUEST['action'])){
        /**iniciamos el autoload**/
        $obj_autoload = new autoload($_REQUEST['module']);
        /**iniciamos el autorequest***/
        $obj_request = new request();
        /**matamos variable d conexion de session*/
        utilidades::begin_script();
        /** validamos los permisos del usuario */	
        /** cargamos de nuevo los accesos a rol */
        $_POST['bttnAction'] = 'verificaraccesosrol';
        if(isset($_REQUEST['event'])){
            $_POST['verifica_from_index'] = array('module'=>$_REQUEST['module'],'action'=>$_REQUEST['action']);
        }
        require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/control/Seguridad/con_set_accesos.php');
        $_REQUEST_aux['module'] = $_REQUEST['module'];
        $_REQUEST_aux['action'] = $_REQUEST['action'];
        /** verificamos excepciones */
        if(isset($_REQUEST['event']) && $_REQUEST['event']!='save_estilos' && $_REQUEST['event']!='validate_crud'){
            $palabra = (isset($_REQUEST['event'])?explode('_',$_REQUEST['event']):'event');
            /* verificamos accesos */
            if(!utilidades::verifica_rol_acceso_in_array(new utisetVarSession(),$_REQUEST_aux,'visible')){				
                if(utilidades::isAjax()){					
                    utilidades::set_response(array('msj'=>"Acceso denegado a este recurso"),true);				
                }else{
                    /*header('location: '.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'].'/index.php?module=Home&action=Home');*/
                    echo "<p>Acceso denegado a este recurso <a href='".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB']."/index.php?module=Home&action=Home'>Volver</a></p>";
                }
                exit;			
            }
            $bol_entro_v = false;
            $str_crud = null;
            /**verificamos create*/
            $arr_insert = array('insert','crear','create','guardarconfiguracion','addeditrows');
            if(in_array(strtolower($palabra[0]),$arr_insert)){
                $str_crud = 'create';
                $bol_entro_v = true;
                if(!utilidades::verifica_rol_acceso_in_array(new utisetVarSession(),$_REQUEST_aux,'insertar')){				
                    if(utilidades::isAjax()){
                        utilidades::set_response(array('msj'=>"Acceso denegado a este recurso"),true);				
                    }else{
                        /*header('location: '.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'].'/index.php?module=Home&action=Home');*/
                        echo "<p>Acceso denegado a este recurso <a href='".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB']."/index.php?module=Home&action=Home'>Volver</a></p>";
                    }
                    exit;			
                }
            }
            /**verificamos read*/
            $arr_read = array('leer','search','buscar','detalles','validar','cargaraccesosbyrol','listar','select','searchroles','searchrows','searchdatosrows');
            if(in_array(strtolower($palabra[0]),$arr_read)){
                $str_crud = 'read';
                $bol_entro_v = true;
                if(!utilidades::verifica_rol_acceso_in_array(new utisetVarSession(),$_REQUEST_aux,'seleccionar')){				
                    if(utilidades::isAjax()){					
                        utilidades::set_response(array('msj'=>"Acceso denegado a este recurso"),true);				
                    }else{
                        /*header('location: '.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'].'/index.php?module=Home&action=Home');*/
                        echo "<p>Acceso denegado a este recurso <a href='".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB']."/index.php?module=Home&action=Home'>Volver</a></p>";
                    }
                    exit;			
                }
            }
            /**verificamos update*/
            $arr_update = array('update','actualizar','create','guardarconfiguracion','reset','save','salvar');
            if(in_array(strtolower($palabra[0]),$arr_update)){
                $str_crud = 'update';
                $bol_entro_v = true;
                if(!utilidades::verifica_rol_acceso_in_array(new utisetVarSession(),$_REQUEST_aux,'actualizar')){				
                    if(utilidades::isAjax()){					
                        utilidades::set_response(array('msj'=>"Acceso denegado a este recurso"),true);				
                    }else{
                        /*header('location: '.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'].'/index.php?module=Home&action=Home');*/
                        echo "<p>Acceso denegado a este recurso <a href='".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB']."/index.php?module=Home&action=Home'>Volver</a></p>";
                    }
                    exit;			
                }
            }
            /**verificamos delete*/
            $arr_delete = array('delete','borrar');
            if(in_array(strtolower($palabra[0]),$arr_delete)){
                $str_crud = 'delete';
                $bol_entro_v = true;
                if(!utilidades::verifica_rol_acceso_in_array(new utisetVarSession(),$_REQUEST_aux,'borrar')){				
                    if(utilidades::isAjax()){					
                        utilidades::set_response(array('msj'=>"Acceso denegado a este recurso"),true);				
                    }else{
                        /*header('location: '.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'].'/index.php?module=Home&action=Home');*/
                        echo "<p>Acceso denegado a este recurso <a href='".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB']."/index.php?module=Home&action=Home'>Volver</a></p>";
                    }
                    exit;			
                }
            }
            /**validamos*/
            if($bol_entro_v==false){
                if(utilidades::isAjax()){					
                    utilidades::set_response(array('msj'=>"Acceso a recurso inexistente"),true);				
                }else{
                    /*header('location: '.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'].'/index.php?module=Home&action=Home');*/
                    echo "<p>Acceso a recurso inexistente <a href='".$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB']."/index.php?module=Home&action=Home'>Volver</a></p>";
                }
                exit;
            }
        }
        /***/
        require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/router/include.php');
        if(isset($_REQUEST['event'])){
            if(!isset($_REQUEST['not_event'])){
                /* instanciamos la clase del controllador */
                eval('$obj = new '.__NAMESPACE__.'\con_'.strtolower($_REQUEST['action']).'();');
                /* invocamos al metodo del controlador */
                eval('$obj->'.$_GET['event'].'Event();');
            }
        }
        /**end script*/
        utilidades::commit_script();
    }else{
        exit;
    }
}