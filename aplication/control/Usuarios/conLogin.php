<?php
namespace MiProyecto{
    error_reporting(E_ALL);
    ini_set('error_reporting', E_ALL | ~E_WARNING);
    ini_set('display_errors',1);
    if(!isset($_SESSION)){
        session_start();
    }
    if(file_exists('aplication/utils/utiSetVarSession.php')){
        require_once('aplication/utils/utiSetVarSession.php');
        require_once('aplication/utils/utilidades.php');
        require_once('aplication/router/autoload.php');
    }else{
        require_once('../../../aplication/utils/utiSetVarSession.php');
        require_once('../../../aplication/utils/utilidades.php');
        require_once('../../../aplication/router/autoload.php');
    }
    utilidades::begin_script();
    /**
     * @author: John Jairo Cortes Garcia - johnjairo1984@gmail.com
     * @version: 1.0.0
     * Fecha - 19-07-2013
     */

    if(file_exists('aplication/utils/inputfilter-2005-05-09/class.inputfilter.php5')){
            require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/inputfilter-2005-05-09/class.inputfilter.php5');
    }else{
            require_once ('../../../aplication/utils/inputfilter-2005-05-09/class.inputfilter.php5');
    }
    $obFilter = new InputFilter();
    /**Variable Global $_POST libre de XSS e Inyecciones SQL//*/
    $_POST = $obFilter->process($_POST);
    $_GET  = $obFilter->process($_GET);

    $strPath=(isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'])?$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']:'');
    if(!file_exists('aplication/utils/inputfilter-2005-05-09/class.inputfilter.php5')){
            $strPath = '../../../';
    }
    /* include de los objetos necesarios*/
    if(file_exists('aplication/model/cl_dao/dao_usuarios.php')){
        require_once ('aplication/model/cl_dao/dao_usuarios.php');
        require_once ('aplication/model/cl_dao/dao_usuarios_ingresos.php');
        require_once ('aplication/model/cl_dao/dao_empresas.php');
        require_once ('aplication/model/cl_dao/dao_empresas_oficinas.php');
        require_once ('aplication/model/cl_dao/dao_empresas_oficinas_usuarios.php');
        require_once ('aplication/model/cl_dao/dao_parametros.php');
    }else{
        require_once ('../../../aplication/model/cl_dao/dao_usuarios.php');
        require_once ('../../../aplication/model/cl_dao/dao_usuarios_ingresos.php');
        require_once ('../../../aplication/model/cl_dao/dao_empresas.php');
        require_once ('../../../aplication/model/cl_dao/dao_empresas_oficinas.php');
        require_once ('../../../aplication/model/cl_dao/dao_empresas_oficinas_usuarios.php');
        require_once ('../../../aplication/model/cl_dao/dao_parametros.php');
    }
    /**iniciamos el autoload**/
    $obj_autoload = new autoload('Login');
    $actionBttn = (isset($_GET['bttnAction'])?$_GET['bttnAction']:(isset($_POST['bttnAction'])?$_POST['bttnAction']:""));
    /****/
    if(isset($actionBttn)){
        $objUtiVarSession = new utisetVarSession();
        /**clean variable de session cache*/
        unset($_SESSION['querys_cache']);
        switch($actionBttn){
            case 'login_user':
                if (isset($_POST['txtUser']) && $_POST['txtPass']){

                    $objDaoUsers = new dao_usuarios();
                    /* verificamos si el usuario existe */
                    /*/// set valores //*/
                    $objDaoUsers->_vo->set_usuario($_POST['txtUser']);
                    /*// ejecutamos //*/
                    $arrRsp = $objDaoUsers->select_rows()->fetch_object_vo();
                    if($arrRsp==null){
                            echo json_encode(array(
                                "valido"=>false,
                                "msj"=>"Usuario No existe!"
                            ));
                    }else{
                        $objDaoUsers->new_vo();
                        $objDaoUsers->_vo = $arrRsp[0];
                        /* validamos el password del usuario */
                        if(($objDaoUsers->_vo->get_clave()) != md5($_POST['txtUser'].'_'.$_POST['txtPass'])){
                             echo json_encode(array(
                                  "valido"=>false,
                                  "msj"=>"Password Incorrecto!"
                             ));
                        }else{
                             /* validamos que el usuario este autorizado enb la oficina */
                             $obj_dao_empresas_oficinas_usuarios = new dao_empresas_oficinas_usuarios();
                             $obj_dao_empresas_oficinas_usuarios->_vo->set_usuarios_id($objDaoUsers->_vo->get_id());
                             /* execute */
                             $arrDOF = $obj_dao_empresas_oficinas_usuarios->select_rows()->fetch_object_vo();
                             $bolAut = false;
                             for($i=0;$i<count($arrDOF);$i++){
                                $obj_dao_empresas_oficinas_usuarios->new_vo();
                                $obj_dao_empresas_oficinas_usuarios->_vo = $arrDOF[$i];
                                if($obj_dao_empresas_oficinas_usuarios->_vo->get_usuarios_id() == $objDaoUsers->_vo->get_id()){
                                   if($obj_dao_empresas_oficinas_usuarios->_vo->get_empresas_oficinas_id() == $_POST['selOficina']){
                                        $bolAut = true;
                                        break;
                                   }
                                }
                             }
                             /* verificamos si esta autorizado */
                             if($bolAut==false || count($arrDOF)==0){
                                utilidades::set_response(array('valido'=>false,"msj"=>"Usuario no autorizado para ingresar a esta sucursal"));
                                return false;;
                             }else{
                                $vo_aux = $objDaoUsers->_vo;
                                /*set variables de session */
                                $objUtiVarSession->set_ssn_id_users($objDaoUsers->_vo->get_id());
                                $objUtiVarSession->set_ssn_first_name_users($vo_aux->get_nombre());
                                $objUtiVarSession->set_ssn_usuario_users($vo_aux->get_usuario());
                                $objUtiVarSession->set_ssn_empresa_id($obj_dao_empresas_oficinas_usuarios->_vo->get_empresas_id());
                                $objUtiVarSession->set_ssn_oficina_id($_POST['selOficina']);
                                $objUtiVarSession->set_ssn_estilo($vo_aux->get_estilo());
                                /* consultamos el nombre de la empresa */
                                $obj_dao_empresas = new dao_empresas();
                                $obj_dao_empresas->_vo->set_id($obj_dao_empresas_oficinas_usuarios->_vo->get_empresas_id());
                                $arrDEM = $obj_dao_empresas->select_rows()->fetch_object_vo();
                                $obj_dao_empresas->_vo = $arrDEM[0];
                                $objUtiVarSession->set_ssn_empresa_nombre($obj_dao_empresas->_vo->get_nombre());
                                /* consultamos el nombre de la oficina */
                                $obj_dao_empresas_oficinas = new dao_empresas_oficinas();
                                $obj_dao_empresas_oficinas->_vo->set_id($_POST['selOficina']);
                                $arrDOF = $obj_dao_empresas_oficinas->select_rows()->fetch_object_vo();
                                $obj_dao_empresas_oficinas->_vo = $arrDOF[0];
                                $objUtiVarSession->set_ssn_oficina_nombre($obj_dao_empresas_oficinas->_vo->get_nombre());

                                /* actualizamos el ultimo_ingreso */
                                $objDaoUsers->new_vo();
                                $objDaoUsers->_vo->set_id($vo_aux->get_id());
                                $objDaoUsers->_vo->set_ultimo_ingreso(@date('Y-m-d H:i:s'));
                                /*execute */
                                $result = $objDaoUsers->update_rows();

                                /* ingresamos el inicio de session */
                                $obj_dao_users_ingresos = new dao_usuarios_ingresos();
                                $obj_dao_users_ingresos->_vo->set_usuarios_id($objDaoUsers->_vo->get_id());
                                $obj_dao_users_ingresos->_vo->set_direccion_ip((isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']!=null?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']));
                                $obj_dao_users_ingresos->_vo->set_empresas_id($obj_dao_empresas_oficinas_usuarios->_vo->get_empresas_id());
                                $obj_dao_users_ingresos->_vo->set_empresas_oficinas_id($_POST['selOficina']);
                                /* ejecutamos */
                                $result = $obj_dao_users_ingresos->insert_rows();

                                echo json_encode(array(
                                    "valido"=>true,
                                    "msj"=>"Usuario valido, En breve sera redireccionado a la pagina principal",
                                    "url"=>"index.php?module=Home&action=Home"
                                ));
                             }
                        }
                    }

                }
                else{
                    return 0;
                }
            break;
        case "verificar_oficinasacc":
            try{
                /* verificamos las oficinas */
                $obj_dao_empresas_oficinas = new dao_empresas_oficinas();
                $obj_dao_empresas_oficinas->_vo->set_estado("A");
                $arrDatos = $obj_dao_empresas_oficinas->select_rows()->fetch_object_vo();

                $arrRsp_1 = array();
                for($i=0;$i<count($arrDatos);$i++){
                    $obj_dao_empresas_oficinas->new_vo();

                    $obj_dao_empresas_oficinas->_vo = $arrDatos[$i];

                    $arrRsp_1['rows'][$i]['nombre'] = $obj_dao_empresas_oficinas->_vo->get_nombre();
                    $arrRsp_1['rows'][$i]['id'] = $obj_dao_empresas_oficinas->_vo->get_id();
                }
                /* verificamos los parametros */
                $obj_dao_parametros = new dao_parametros();
                $arrDatos = $obj_dao_parametros->select_rows()->fetch_object_vo();

                $arrRsp_2 = array();
                for($i=0;$i<count($arrDatos);$i++){
                    $obj_dao_parametros->new_vo();
                    $obj_dao_parametros->_vo = $arrDatos[$i];

                    $str_bd = explode(';',$obj_dao_parametros->_vo->get_bd());

                    $arrRsp_2['rows'][$i]['id'] = $str_bd[1];
                    $arrRsp_2['rows'][$i]['nombre'] = $str_bd[1];
                }

                utilidades::set_response(array($arrRsp_1,$arrRsp_2));
            }catch(Exception $e){
                utilidades::set_response(array('Error: --->'.$e->getMessage()),true);
            }	   
            break;
        case "verificar_oficinasacc_usuario":
                    /* consultamos el id del usuario */
                    $objDaoUsers = new dao_usuarios();
                    $objDaoUsers->new_vo();
                    /* set datos */
                    $objDaoUsers->_vo->set_usuario($_POST['user']);

                    /*// ejecutamos //*/
                    $arrRsp = $objDaoUsers->select_rows()->fetch_object_vo();
                    if($arrRsp==null){
                            echo json_encode(array(
                                "valido"=>false,
                                "msj"=>"Usuario No existe!"
                            ));
                    }else{
                        $objDaoUsers->new_vo();
                        $objDaoUsers->_vo = $arrRsp[0];
                        /* consultamos las oficinas que el usuario puede acceder */
                        $obj_dao_ofi_us = new dao_empresas_oficinas_usuarios();
                        $obj_dao_ofi_us->new_vo();

                        /* set datos */
                        $obj_dao_ofi_us->_vo->set_usuarios_id($objDaoUsers->_vo->get_id());
                        /* execute */
                        $arrRsp = $obj_dao_ofi_us->select_rows()->fetch_object_vo();
                        if($arrRsp==null){
                            echo json_encode(array(
                                "valido"=>false,
                                "msj"=>"Usuario No autorizado para ninguna oficina!"
                            ));
                        }else{
                            $obj_dao_ofi_us->new_vo();
                            $obj_dao_ofi_us->_vo = $arrRsp[0];

                            $arrRsp_1 = array();
                            $arrRsp_1['rows'][0]['oficinas_id'] = $obj_dao_ofi_us->_vo->get_empresas_oficinas_id();
                            $arrRsp_1['valido'] = true;
                            /* set response */
                            utilidades::set_response($arrRsp_1);
                        }
                    }
                    break;
            }
    }
    /***/
    utilidades::commit_script();
}