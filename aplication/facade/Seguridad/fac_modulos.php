<?php
namespace MiProyecto{
    class fac_modulos{
        function __construct(){}
        
        /*
         * action para buscar un producto por su nombre
         */
        public function searchrowsPublic(){
            try{
                $this->set_filters_grilla_basPrivate($page,$limit,$sidx,$sord);
                //// get the direction 
                if(!$sidx){ 
                    $sidx =1; 
                }
                // set valores //
                $obj_dao_seguridad_modulos = new dao_seguridad_modulos();
                $obj_dao_seguridad_modulos->new_vo();
                $obj_dao_seguridad_modulos->_vo->set_estado('A');
                //// set filters ////
                if(request::get_parameter('filters')!=null){
                    utilidades::parsear_filters($obj_dao_seguridad_modulos->_vo);
                }
                //// connect to the database 
                ////// sacamos los registros en estado activo
                $arrDatos = $obj_dao_seguridad_modulos->select_rows()->fetch_object_vo();
                $count = count($arrDatos); 
                if( $count >0 ) { 
                        $total_pages = ceil($count/$limit);
                } else {
                        $total_pages = 0;
                } 
                if ($page > $total_pages) 
                        $page=$total_pages; 
                $start = $limit*$page - $limit; 
                if($start<0){
                        $start = 0;
                }
                //// do not put $limit*($page - 1)
                $responce = new \stdClass();
                $responce->page = $page; 
                $responce->total = $total_pages; 
                $responce->records = $count;
                $arrDatos = array();
                $arrDatos = $obj_dao_seguridad_modulos->select_rows($sidx, $sord, $start, $limit)->fetch_object_vo();
                $numRows = count($arrDatos);
                for($i=0;$i<$numRows;$i++){
                    $obj_vo_seguridad_modulos = $arrDatos[$i];

                    // sacamos el nombre del usuario //
                    $obj_dao_usuarios = new dao_usuarios();
                    $obj_dao_usuarios->new_vo();
                    $obj_dao_usuarios->_vo->set_id($obj_vo_seguridad_modulos->get_creado_por());
                    // ejecutamos //
                    $arrDatos_usuarios = $obj_dao_usuarios->select_rows()->fetch_object_vo();
                    $obj_dao_usuarios->_vo = $arrDatos_usuarios[0];
                    ////////////////////////////////////////
                    /***********/
                    $responce->rows[$i]['id']=$obj_vo_seguridad_modulos->get_id();
                    $responce->rows[$i]['cell']=array(
                        $obj_vo_seguridad_modulos->get_id(),
                        utf8_encode(ucwords(strtolower($obj_vo_seguridad_modulos->get_nombre()))),
                        $obj_vo_seguridad_modulos->get_alias(),
                        strtoupper($obj_dao_usuarios->_vo->get_usuario()),
                        substr($obj_vo_seguridad_modulos->get_fecha_creacion(),0,10)
                    );
                }

                utilidades::set_response($responce);
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }

        /***/
        private function set_filters_grilla_basPrivate(&$page,&$limit,&$sidx,&$sord){
            try{
                $page = (request::get_parameter('page')!=null?request::get_parameter('page'):1); 
                /* get the requested page */
                $limit = (request::get_parameter('rows')!=null?request::get_parameter('rows'):999999999);
                /* get how many rows we want to have into the grid */
                $sidx = (request::get_parameter('sidx')!=null?request::get_parameter('sidx'):'id');
                /* get index row - i.e. user click to sort */
                $sord = (request::get_parameter('sord')!=null?request::get_parameter('sord'):'ASC');
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * @author john jairo cortes <johnmax11@hotmail.com>
         * @return object objeto vo con los datos consultados
         * @param null
         */
        public function get_seguridad_modulos($sidx=null,$sord=null,$start=null,$limit=null,$chrEstado="A"){
            try{
                $objDaoSeguridadModulos = new dao_seguridad_modulos();
                $objDaoSeguridadModulos->_vo->set_estado($chrEstado);
                return $objDaoSeguridadModulos->select_rows($sidx,$sord,$start,$limit)->fetch_object_vo();
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**/
        public function addeditrowsPublic(){
            try{
                // start transaccion //
                $obj_dao_seguridad_modulos = new dao_seguridad_modulos();
                $obj_dao_seguridad_modulos->begin();

                // consultamos si existe el producto //
                $obj_dao_seguridad_modulos->new_vo();
                $obj_dao_seguridad_modulos->_vo->set_id((request::get_parameter('hdnid')!=''?request::get_parameter('hdnid'):-1));

                // ejecutamos //
                $arrDatos_seguridad_modulos = $obj_dao_seguridad_modulos->select_rows()->fetch_object_vo();
                ////////////////////////////////////////////////
                if($arrDatos_seguridad_modulos == null){
                    // insertamos //
                    $obj_dao_seguridad_modulos->_vo->set_nombre(strtoupper(request::get_parameter('txtNombre_modulos')));
                    $obj_dao_seguridad_modulos->_vo->set_alias(strtoupper(request::get_parameter('txtAlias_modulos')));
                    $obj_dao_seguridad_modulos->_vo->set_estado('A');

                    $bolresult = $obj_dao_seguridad_modulos->insert_rows();
                    // creamos el nuevo directorio del modulo //
                    $this->crear_directorio_moduloPrivate();
                }else{
                    $obj_dao_seguridad_modulos->_vo->set_id(request::get_parameter('hdnid'));
                    // select datos old //
                    $arrDOld = $obj_dao_seguridad_modulos->select_rows()->fetch_object_vo();
                    // actualizamos //
                    $obj_dao_seguridad_modulos->_vo->set_estado(request::get_parameter('selEstado'));
                    $obj_dao_seguridad_modulos->_vo->set_nombre(strtoupper(request::get_parameter('txtNombre_modulos')));
                    $obj_dao_seguridad_modulos->_vo->set_alias(strtoupper(request::get_parameter('txtAlias_modulos')));
                    $bolresult = $obj_dao_seguridad_modulos->update_rows();
                    // renombrar la carpeta del modulo //
                    $obj_dao_seguridad_modulos->new_vo();
                    $obj_dao_seguridad_modulos->_vo = $arrDOld[0];
                    $strOldNombre = $obj_dao_seguridad_modulos->_vo->get_nombre();
                    $this->renombrar_directorio_moduloPrivate($strOldNombre);
                }
                $obj_dao_seguridad_modulos->commit();
                //
                utilidades::set_response(array('msj'=>'Proceso terminado correctamente'));
            }catch(\Exception $ex){
                $obj_dao_seguridad_modulos->rollback();
                new ExceptionHandler($ex->getMessage());
            }
        }
        /**/
        public function deleterowsPublic(){
            try{
                // set datos //
                $obj_dao_seguridad_modulos = new dao_seguridad_modulos();
                $obj_dao_seguridad_modulos->new_vo();
                $obj_dao_seguridad_modulos->_vo->set_id(request::get_parameter('idrow'));
                $obj_dao_seguridad_modulos->_vo->set_estado('C');
                $result = $obj_dao_seguridad_modulos->update_rows();

                utilidades::set_response(array("msj"=>"Proceso terminado correctamente"));
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        /**/
        public function searchdatosrowsPublic(){
            try{
                $arrResponse = array();

                $obj_dao_seguridad_modulos = new dao_seguridad_modulos();
                $obj_dao_seguridad_modulos->new_vo();
                $obj_dao_seguridad_modulos->_vo->set_id(request::get_parameter('idrow'));
                $arrResult = $obj_dao_seguridad_modulos->select_rows()->fetch_object_vo();
                $obj_dao_seguridad_modulos->_vo = $arrResult[0];

                $arrResponse['rows'][0]['id'] = $obj_dao_seguridad_modulos->_vo->get_id();
                $arrResponse['rows'][0]['nombre'] = utf8_encode($obj_dao_seguridad_modulos->_vo->get_nombre());
                $arrResponse['rows'][0]['alias'] = utf8_encode($obj_dao_seguridad_modulos->_vo->get_alias());
                $arrResponse['rows'][0]['estado'] = utf8_encode($obj_dao_seguridad_modulos->_vo->get_estado());

                utilidades::set_response($arrResponse);
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**************************************************************************/
        /**
         * crea un directorio con el nombre del modulo
         */
        public function crear_directorio_moduloPrivate(){
            try{
                // verificamos si el directorio existe //
                if(!is_dir($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/control/'.ucfirst(strtolower(request::get_parameter('txtNombre_modulos'))))){
                    // creamos el directorio en la carpeta control //
                    mkdir($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/control/".ucfirst(strtolower(request::get_parameter('txtNombre_modulos'))), 0755);
                }
                // verificamos si existe el directorio en facade //
                if(!is_dir($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/'.ucfirst(strtolower(request::get_parameter('txtNombre_modulos'))))){
                    // creamos el directorio en la carpeta control //
                    mkdir($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/facade/".ucfirst(strtolower(request::get_parameter('txtNombre_modulos'))), 0755);
                }
                // verificamos si existe el directorio en la carpeta js //
                if(!is_dir($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/js/'.ucfirst(strtolower(request::get_parameter('txtNombre_modulos'))))){
                    // creamos el directorio en la carpeta js //
                    mkdir($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/js/".ucfirst(strtolower(request::get_parameter('txtNombre_modulos'))), 0755);
                }

                // verificamos si existe el directorio en la carpeta views //
                if(!is_dir($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/views/'.ucfirst(strtolower(request::get_parameter('txtNombre_modulos'))))){
                    // creamos el directorio en la carpeta js //
                    mkdir($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/views/".ucfirst(strtolower(request::get_parameter('txtNombre_modulos'))), 0755);
                }
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        /**
         * modifica el nombre de un directorio
         */
        public function renombrar_directorio_moduloPrivate($oldname){
            try{
                // verificamos si el directorio existe //
                if(is_dir($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/control/'.ucfirst(strtolower($oldname)))){
                    // creamos el directorio en la carpeta control //
                    rename(
                        $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/control/".ucfirst(strtolower($oldname)),
                        $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/control/".ucfirst(strtolower(request::get_parameter('txtNombre_modulos')))
                    );
                }
                // verificamos si existe el directorio en facade //
                if(is_dir($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/'.ucfirst(strtolower($oldname)))){
                    // creamos el directorio en la carpeta control //
                    rename(
                        $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/facade/".ucfirst(strtolower($oldname)),
                        $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/aplication/facade/".ucfirst(strtolower(request::get_parameter('txtNombre_modulos')))
                    );
                }
                // verificamos si existe el directorio en la carpeta js //
                if(is_dir($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/js/'.ucfirst(strtolower($oldname)))){
                    // creamos el directorio en la carpeta js //
                    rename(
                        $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/js/".ucfirst(strtolower($oldname)),
                        $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']."/js/".ucfirst(strtolower(request::get_parameter('txtNombre_modulos')))
                    );
                }
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
    }
}
