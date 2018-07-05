<?php
namespace MiProyecto{
    class fac_seguridad_roles{
        public $_request;
        public $_utiSsn;
		
        function __construct(){}

            /*
             * action para buscar un producto por su nombre
             */
            public function searchrolesallAction(){
                $this->set_filters_grilla_basPrivate($page,$limit,$sidx,$sord);
                //// get the direction 
                if(!$sidx){ 
                        $sidx =1; 
                }
                // set valores //
                $obj_dao_seguridad_roles = new dao_seguridad_roles();
                $obj_dao_seguridad_roles->new_vo();
                $obj_dao_seguridad_roles->_vo->set_estado('A');
                $obj_dao_seguridad_roles->_vo->set_id(array(2));
                //// set filters ////
                if(isset($this->_request['filters'])){
                        utilidades::parsear_filters($obj_dao_seguridad_roles->_vo,$this->_request);
                }
                //// connect to the database 
                ////// sacamos los registros en estado activo
                $arrDatos = $obj_dao_seguridad_roles->select_rows()->fetch_object_vo();
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
                        $start =0;
                }
                //// do not put $limit*($page - 1)
                $responce = new \stdClass();
                $responce->page = $page; 
                $responce->total = $total_pages; 
                $responce->records = $count;
                $arrDatos = array();
                $arrDatos = $obj_dao_seguridad_roles->select_rows($sidx, $sord, $start, $limit)->fetch_object_vo();
                $numRows = count($arrDatos);
                for($i=0;$i<$numRows;$i++){
                        $obj_dao_seguridad_roles->new_vo();
                        $obj_dao_seguridad_roles->_vo = $arrDatos[$i];

                        // sacamos el nombre del usuario //
                        $obj_dao_usuarios = new dao_usuarios();
                        $obj_dao_usuarios->new_vo();
                        $obj_dao_usuarios->_vo->set_id($obj_dao_seguridad_roles->_vo->get_creado_por());
                        // ejecutamos //
                        $arrDatos_usuarios = $obj_dao_usuarios->select_rows()->fetch_object_vo();
                        $obj_dao_usuarios->_vo = $arrDatos_usuarios[0];
                        ////////////////////////////////////////
                        $responce->rows[$i]['id']=$obj_dao_seguridad_roles->_vo->get_id();
                        $responce->rows[$i]['cell']=array(
                                $obj_dao_seguridad_roles->_vo->get_id(),
                                utf8_encode($obj_dao_seguridad_roles->_vo->get_nombre()),
                                '',
                                strtoupper($obj_dao_usuarios->_vo->get_usuario()),
                                substr($obj_dao_seguridad_roles->_vo->get_fecha_creacion(),0,10)
                        );
                }

                echo json_encode($responce);
            }

            /***/
            private function set_filters_grilla_basPrivate(&$page,&$limit,&$sidx,&$sord){
                $page = (isset($this->_request['page'])?$this->_request['page']:1); 
                //// get the requested page 
                $limit = (isset($this->_request['rows'])?$this->_request['rows']:999999999);
                //// get how many rows we want to have into the grid 
                $sidx = (isset($this->_request['sidx'])?$this->_request['sidx']:'id');
                //// get index row - i.e. user click to sort 
                $sord = (isset($this->_request['sord'])?$this->_request['sord']:'ASC');
            }

            /**/
            public function addeditrolesAction(){
                try{
                        // start transaccion //
                        $obj_dao_seguridad_roles = new dao_seguridad_roles();
                        $obj_dao_seguridad_roles->begin();

                        // consultamos si existe el producto //
                        $obj_dao_seguridad_roles->new_vo();
                        $obj_dao_seguridad_roles->_vo->set_id(($this->_request['hdnid']!=''?$this->_request['hdnid']:-1));
                        // ejecutamos //
                        $arrDatos_seg_roles = $obj_dao_seguridad_roles->select_rows()->fetch_object_vo();

                        $obj_dao_seguridad_roles->new_vo();
                        $obj_dao_seguridad_roles->_vo->set_nombre(strtoupper($this->_request['txtNombre_roles']));
                        $obj_dao_seguridad_roles->_vo->set_estado(isset($this->_request['selEstadoRoles'])?$this->_request['selEstadoRoles']:'A');
                        ////////////////////////////////////////////////
                        if($arrDatos_seg_roles == null){
                                // insertamos //
                                $bolresult = $obj_dao_seguridad_roles->insert_rows();

                                $last_id = $obj_dao_seguridad_roles->get_last_insert_id();

                                // insertamos la config de 1 vez //
                                $this->set_config_accesos_firtPrivate($last_id);
                        }else{
                                // actualizamos //
                                $obj_dao_seguridad_roles->_vo->set_id($this->_request['hdnid']);
                                $bolresult = $obj_dao_seguridad_roles->update_rows();
                        }
                        $obj_dao_seguridad_roles->commit();
                        //
                        utilidades::set_response(array('msj'=>'Proceso terminado correctamente'));
                }catch(Exception $e){
                        $obj_dao_seguridad_roles->rollback();
                        utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
                }

                // end transaccion //
                return true;
            }
            /**/
            public function deleterolesAction(){
                try{
                        // set datos //
                        $obj_dao_seguridad_roles = new dao_seguridad_roles();
                        $obj_dao_seguridad_roles->new_vo();
                        $obj_dao_seguridad_roles->_vo->set_id($this->_request['idrol']);
                        $obj_dao_seguridad_roles->_vo->set_estado('C');
                        $result = $obj_dao_seguridad_roles->update_rows();

                        utilidades::set_response(array("msj"=>"Proceso terminado correctamente"));
                }catch(Exception $e){
                        utilidades::set_response(array("msj"=>"Error: --->".__METHOD__.'--->'.$e->getMessage()));
                }
            }
            /**/
            public function searchdatosrolesAction(){
                $arrRsp = array();

                $obj_dao_seguridad_roles = new dao_seguridad_roles();
                $obj_dao_seguridad_roles->new_vo();
                $obj_dao_seguridad_roles->_vo->set_id($this->_request['idrol']);
                $arrResult = $obj_dao_seguridad_roles->select_rows()->fetch_object_vo();
                $obj_dao_seguridad_roles->_vo = $arrResult[0];

                $arrRsp['rows'][0]['id'] = $obj_dao_seguridad_roles->_vo->get_id();
                $arrRsp['rows'][0]['nombre'] = utf8_encode($obj_dao_seguridad_roles->_vo->get_nombre());
                $arrRsp['rows'][0]['estado'] = $obj_dao_seguridad_roles->_vo->get_estado();

                utilidades::set_response($arrRsp);
            }

            /**/
            public function cargaraccesosbyrolAction($bolreturn=false,$call_from_index=false){
                /**buscamos el id del modulo y id_programa**/
                if($call_from_index!=false){
                    /**consultamos datos modulo**/
                    $obj_dao_seguridad_modulos = new dao_seguridad_modulos();
                    $obj_dao_seguridad_modulos->_vo->set_nombre($call_from_index['module']);
                    $arr_obj_m = $obj_dao_seguridad_modulos->select_rows()->fetch_object_vo();
                    $obj_dao_seguridad_modulos->_vo = $arr_obj_m[0];
                    /**consultamos datos programa*/
                    $obj_dao_seguridad_programas = new dao_seguridad_programas();
                    $obj_dao_seguridad_programas->_vo->set_nombre($call_from_index['action']);
                    $arr_obj_p = $obj_dao_seguridad_programas->select_rows()->fetch_object_vo();
                    $obj_dao_seguridad_programas->_vo = $arr_obj_p[0];
                    //echo '<pre>'.print_r($obj_dao_seguridad_modulos->_vo,true).'</pre>';
                    //echo '<pre>'.print_r($obj_dao_seguridad_programas->_vo,true).'</pre>';
                }
                
                // consultamos la configuracion del rol //
                $obj_dao_seguridad_roles_accesos = new dao_seguridad_roles_accesos();
                $obj_dao_seguridad_roles_accesos->_vo->set_seguridad_roles_id($this->_request['idrol']);
                /**añadimos datos adicionales*/
                if($call_from_index!=false){
                    if($obj_dao_seguridad_modulos->_vo!=null && $obj_dao_seguridad_programas->_vo!=null){
                        $obj_dao_seguridad_roles_accesos->_vo->set_seguridad_modulos_id($obj_dao_seguridad_modulos->_vo->get_nombre());
                        $obj_dao_seguridad_roles_accesos->_vo->set_seguridad_programas_id($obj_dao_seguridad_programas->_vo->get_nombre());
                    }
                }
                // consultamos los accesos del rol //
                $arrDatos = $obj_dao_seguridad_roles_accesos->select_rows('seguridad_modulos_id,seguridad_programas_id','ASC',0,99999)->fetch_object_vo();
                //echo print_r($arrDatos,true);
                $arrResponse = array();

                $cont_array = 0;
                for($i=0;$i<count($arrDatos);$i++){
                    $obj_dao_seguridad_roles_accesos->new_vo();

                    $obj_dao_seguridad_roles_accesos->_vo = $arrDatos[$i];
                    // consultamos el nombre del rol //
                    ///////
                    //$obj_dao_seguridad_roles = new dao_seguridad_roles($obj_dao_seguridad_roles_accesos->_vo->get_seguridad_roles_id());
                    // consultamos el nombre del modulo //
                    $obj_dao_seguridad_modulos = new dao_seguridad_modulos();
                    $obj_dao_seguridad_modulos->_vo->set_id($obj_dao_seguridad_roles_accesos->_vo->get_seguridad_modulos_id());
                    $obj_dao_seguridad_modulos->_vo->set_estado("A");
                    // ejecutamos el query //
                    $arrDatosMod = $obj_dao_seguridad_modulos->select_rows()->fetch_object_vo();
                    $obj_dao_seguridad_modulos->_vo = $arrDatosMod[0];
                    if($obj_dao_seguridad_modulos->_vo!=null){
                        $arrResponse['rows'][$cont_array]['id'] = $obj_dao_seguridad_roles_accesos->_vo->get_id();
                        $arrResponse['rows'][$cont_array]['seguridad_roles_id'] = $obj_dao_seguridad_roles_accesos->_vo->get_seguridad_roles_id();
                        //$arrResponse['rows'][$cont_array]['seguridad_roles_nombre'] = $obj_dao_seguridad_roles->_vo->get_nombre();
                        ////////////////////////////////////////////////////////////
                        $arrResponse['rows'][$cont_array]['seguridad_modulos_id'] = $obj_dao_seguridad_roles_accesos->_vo->get_seguridad_modulos_id();
                        $arrResponse['rows'][$cont_array]['seguridad_modulos_nombre'] = ucfirst(strtolower($obj_dao_seguridad_modulos->_vo->get_nombre()));
                        $arrResponse['rows'][$cont_array]['seguridad_modulos_alias'] = ucfirst(strtolower($obj_dao_seguridad_modulos->_vo->get_alias()));
                        /////////////////////////////////////////////////////////////
                        $arrResponse['rows'][$cont_array]['seguridad_programas_id'] = $obj_dao_seguridad_roles_accesos->_vo->get_seguridad_programas_id();
                        // consultamos el nombre del programa si es != null //
                        $arrResponse['rows'][$cont_array]['seguridad_programas_nombre'] = null;
                        $arrResponse['rows'][$cont_array]['imagen'] = null;
                        if($arrResponse['rows'][$cont_array]['seguridad_programas_id']!=null){
                            $obj_dato_seguridad_programas = new dao_seguridad_programas();
                            $obj_dato_seguridad_programas->_vo->set_id($obj_dao_seguridad_roles_accesos->_vo->get_seguridad_programas_id());
                            $obj_dato_seguridad_programas->_vo->set_estado("A");
                            // ejecutamos el query //
                            $arrDatosPro = $obj_dato_seguridad_programas->select_rows()->fetch_object_vo();
                            $obj_dato_seguridad_programas->_vo = $arrDatosPro[0];
                            if($obj_dato_seguridad_programas->_vo!=null){
                                $arrResponse['rows'][$cont_array]['seguridad_programas_nombre'] = ucfirst(strtolower($obj_dato_seguridad_programas->_vo->get_nombre()));
                                $arrResponse['rows'][$cont_array]['seguridad_programas_alias'] = ucfirst(strtolower($obj_dato_seguridad_programas->_vo->get_alias()));
                                $arrResponse['rows'][$cont_array]['imagen'] = $obj_dato_seguridad_programas->_vo->get_imagen();
                            }
                        }
                        ////////////////////////////////////////////////////////////////////
                        $arrResponse['rows'][$cont_array]['visible'] = $obj_dao_seguridad_roles_accesos->_vo->get_visible();
                        $arrResponse['rows'][$cont_array]['insertar'] = $obj_dao_seguridad_roles_accesos->_vo->get_insertar();
                        $arrResponse['rows'][$cont_array]['seleccionar'] = $obj_dao_seguridad_roles_accesos->_vo->get_seleccionar();
                        $arrResponse['rows'][$cont_array]['actualizar'] = $obj_dao_seguridad_roles_accesos->_vo->get_actualizar();
                        $arrResponse['rows'][$cont_array]['borrar'] = $obj_dao_seguridad_roles_accesos->_vo->get_borrar();
                        $arrResponse['rows'][$cont_array]['eventos'] = $obj_dao_seguridad_roles_accesos->_vo->get_eventos();
                        $arrResponse['rows'][$cont_array]['creado_por'] = $obj_dao_seguridad_roles_accesos->_vo->get_creado_por();
                        $arrResponse['rows'][$cont_array]['fecha_creacion'] = $obj_dao_seguridad_roles_accesos->_vo->get_fecha_creacion();
                        $cont_array ++;
                    }
                }
                //echo print_r($arrResponse,true);
                if($bolreturn==false){
                    utilidades::set_response($arrResponse);
                }else{
                    return $arrResponse;
                }
            }

            /**/
            public function guardarconfiguracionAction(){
                    try{
                            $obj_dao_seguridad_roles_acc = new dao_seguridad_roles_accesos();
                            $obj_dao_seguridad_roles_acc->begin();
                            // recorremos las filas para actualiza por el id //
                            if(isset($this->_request['hdncantrows'])){
                                    for($i=0;$i<$this->_request['hdncantrows'];$i++){
                                            if(!isset($this->_request['hdnid-'.$i])){
                                                    continue;
                                            }
                                            $obj_dao_seguridad_roles_acc->new_vo();

                                            // set datos //
                                            $obj_dao_seguridad_roles_acc->_vo->set_id($this->_request['hdnid-'.$i]);

                                            $obj_dao_seguridad_roles_acc->_vo->set_visible($this->_request['selSINO-'.$i.'-1']);
                                            $obj_dao_seguridad_roles_acc->_vo->set_insertar($this->_request['selSINO-'.$i.'-2']);
                                            $obj_dao_seguridad_roles_acc->_vo->set_seleccionar($this->_request['selSINO-'.$i.'-3']);
                                            $obj_dao_seguridad_roles_acc->_vo->set_actualizar($this->_request['selSINO-'.$i.'-4']);
                                            $obj_dao_seguridad_roles_acc->_vo->set_borrar($this->_request['selSINO-'.$i.'-5']);
                                            // execute //
                                            $result = $obj_dao_seguridad_roles_acc->update_rows();
                                    } // fin for //
                            }
                            $obj_dao_seguridad_roles_acc->commit();
                            utilidades::set_response(array("msj"=>"Proceso terminado correctamente"));
                    }
                    catch(Exception $e){
                            $obj_dao_seguridad_roles_acc->rollback();
                            utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
                    }
            }

            /**/
            public function verificaraccesosrolAction($call_from_index){
                // consultamos el id rol por el id de session //
                $obj_dao_usuarios = new dao_usuarios($this->_utiSsn->get_ssn_id_users());
                $this->_request['idrol'] = $obj_dao_usuarios->_vo->get_seguridad_roles_id();
                $arrDatosAcc = $this->cargaraccesosbyrolAction(true,$call_from_index);
                // set session accesos roles //
                $this->_utiSsn->set_ssn_accesos_rol($arrDatosAcc);
                ////////////////////////////////////////////////
                /// consultamos el ultimo ingreso del usuario //
                $obj_dao_usuarios_ingresos = new dao_usuarios_ingresos();
                $obj_dao_usuarios_ingresos->new_vo();
                // set datos //
                $obj_dao_usuarios_ingresos->_vo->set_usuarios_id($this->_utiSsn->get_ssn_id_users());
                // execute //
                $arrDIUs = $obj_dao_usuarios_ingresos->select_rows('id','DESC',0,1)->fetch_object_vo();
                $obj_dao_usuarios_ingresos->_vo = $arrDIUs[0];
                //////////////////////////////
                // set variables de session //
                $this->_utiSsn->set_ssn_empresa_id($obj_dao_usuarios_ingresos->_vo->get_empresas_id());
                $this->_utiSsn->set_ssn_oficina_id($obj_dao_usuarios_ingresos->_vo->get_empresas_oficinas_id());
                // consultamos el nombre de la empresa //
                $obj_dao_empresas = new dao_empresas($obj_dao_usuarios_ingresos->_vo->get_empresas_id());
                $this->_utiSsn->set_ssn_empresa_nombre($obj_dao_empresas->_vo->get_nombre());
                // consultamos el nombre de la oficina //
                $obj_dao_empresas_oficinas = new dao_empresas_oficinas($obj_dao_usuarios_ingresos->_vo->get_empresas_oficinas_id());
                $this->_utiSsn->set_ssn_oficina_nombre($obj_dao_empresas_oficinas->_vo->get_nombre());
            }

            /***/
            private function set_config_accesos_firtPrivate($last_id_rol){
                    $obj_dao_seguridad_roles_accesos = new dao_seguridad_roles_accesos();
                    // insertamos la config por 1 vez //
                    /// consultamos los modulos //
                    $obj_dao_seguridad_modulos = new dao_seguridad_modulos();
                    $arrDatos_mod = $obj_dao_seguridad_modulos->select_rows()->fetch_object_vo();
                    for($i=0;$i<count($arrDatos_mod);$i++){
                        $obj_dao_seguridad_modulos->new_vo();
                        $obj_dao_seguridad_modulos->_vo = $arrDatos_mod[$i];

                        $obj_dao_seguridad_roles_accesos->new_vo();
                        $obj_dao_seguridad_roles_accesos->_vo->set_visible($obj_dao_seguridad_modulos->_vo->get_id()>1?'N':'S');
                        $obj_dao_seguridad_roles_accesos->_vo->set_insertar($obj_dao_seguridad_modulos->_vo->get_id()>1?'N':'S');
                        $obj_dao_seguridad_roles_accesos->_vo->set_seleccionar($obj_dao_seguridad_modulos->_vo->get_id()>1?'N':'S');
                        $obj_dao_seguridad_roles_accesos->_vo->set_actualizar($obj_dao_seguridad_modulos->_vo->get_id()>1?'N':'S');
                        $obj_dao_seguridad_roles_accesos->_vo->set_borrar($obj_dao_seguridad_modulos->_vo->get_id()>1?'N':'S');

                        $obj_dao_seguridad_roles_accesos->_vo->set_seguridad_roles_id($last_id_rol);
                        $obj_dao_seguridad_roles_accesos->_vo->set_seguridad_modulos_id($obj_dao_seguridad_modulos->_vo->get_id());

                        // execute //
                        $obj_dao_seguridad_roles_accesos->insert_rows($obj_dao_seguridad_roles_accesos->_vo);

                        // consultamos los programas de cada modulo //
                        $obj_dao_seguridad_programas = new dao_seguridad_programas();
                        $obj_dao_seguridad_programas->new_vo();
                        $obj_dao_seguridad_programas->_vo->set_seguridad_modulos_id($obj_dao_seguridad_modulos->_vo->get_id());
                        // execute //
                        $arrDatos_prog = $obj_dao_seguridad_programas->select_rows()->fetch_object_vo();

                        for($j=0;$j<count($arrDatos_prog);$j++){
                            $obj_dao_seguridad_programas->new_vo();
                            $obj_dao_seguridad_programas->_vo = $arrDatos_prog[$j];

                            $obj_dao_seguridad_roles_accesos->new_vo();
                            $obj_dao_seguridad_roles_accesos->_vo->set_visible($obj_dao_seguridad_programas->_vo->get_id()>1?'N':'S');
                            $obj_dao_seguridad_roles_accesos->_vo->set_insertar($obj_dao_seguridad_programas->_vo->get_id()>1?'N':'S');
                            $obj_dao_seguridad_roles_accesos->_vo->set_seleccionar($obj_dao_seguridad_programas->_vo->get_id()>1?'N':'S');
                            $obj_dao_seguridad_roles_accesos->_vo->set_actualizar($obj_dao_seguridad_programas->_vo->get_id()>1?'N':'S');
                            $obj_dao_seguridad_roles_accesos->_vo->set_borrar($obj_dao_seguridad_programas->_vo->get_id()>1?'N':'S');

                            $obj_dao_seguridad_roles_accesos->_vo->set_seguridad_roles_id($last_id_rol);
                            $obj_dao_seguridad_roles_accesos->_vo->set_seguridad_modulos_id($obj_dao_seguridad_modulos->_vo->get_id());
                            $obj_dao_seguridad_roles_accesos->_vo->set_seguridad_programas_id($obj_dao_seguridad_programas->_vo->get_id());

                            // execute //
                            $obj_dao_seguridad_roles_accesos->insert_rows($obj_dao_seguridad_roles_accesos->_vo);
                        }
                    }
            }
	}
}
