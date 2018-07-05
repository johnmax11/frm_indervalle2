<?php
namespace MiProyecto{
    class fac_gestion_clientes{
        function __construct(){}
        /*
         * action para buscar un producto por su nombre
         */
        public function get_datos_grillaPublic(){
            try{
                $this->set_filters_grilla_basPrivate($page,$limit,$sidx,$sord);
                /* get the direction */
                if(!$sidx){ 
                    $sidx =1; 
                }
                /**consultamos los datos del cliente**/
                $arrDatos = $this->get_clientes_datos_basicos();
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
                /* do not put $limit*($page - 1)*/
                $responce = new \stdClass();
                $responce->page = $page; 
                $responce->total = $total_pages; 
                $responce->records = $count;
                /* verificamos campos fk */
                if(utilidades::verifica_campo_fk($sidx)==false){
                    $arrDatos = array();
                    $arrDatos = $this->get_clientes_datos_basicos($sidx, $sord, $start, $limit);
                }
                $numRows = count($arrDatos);
                $obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos();
                
                for($i=0;$i<$numRows;$i++){
                    $obj_dao_clientes_datos_basicos->new_vo();
                    $obj_dao_clientes_datos_basicos->_vo = $arrDatos[$i];

                    /* sacamos el nombre del usuario */
                    $obj_dao_usuarios = new dao_usuarios($obj_dao_clientes_datos_basicos->_vo->get_creado_por());

                    /**sacamos los datos de contacto*/
                    $obj_cli_contacto = $this->get_clientes_datos_contactos($obj_dao_clientes_datos_basicos->_vo->get_id());
                    $obj_dao_clientes_datos_contactos = new dao_clientes_datos_contactos();
                    $obj_dao_clientes_datos_contactos->_vo = $obj_cli_contacto[0];
                    /***/
                    $responce->rows[$i]['id']=$obj_dao_clientes_datos_basicos->_vo->get_id();
                    $responce->rows[$i]['cell']=array(
                        $obj_dao_clientes_datos_basicos->_vo->get_id(),
                        $obj_dao_clientes_datos_basicos->_vo->get_nombres(),
                        $obj_dao_clientes_datos_basicos->_vo->get_apellidos(),
                        $obj_dao_clientes_datos_basicos->_vo->get_identificacion(),
                        $obj_dao_clientes_datos_contactos->_vo->get_telefono_celular(),
                        utilidades::get_mes_string_spanish(substr($obj_dao_clientes_datos_basicos->_vo->get_fecha_nacimiento(),6,2)).' '.substr($obj_dao_clientes_datos_basicos->_vo->get_fecha_nacimiento(),8),
                        '',
                        strtoupper($obj_dao_usuarios->_vo->get_usuario()),
                        substr($obj_dao_clientes_datos_basicos->_vo->get_fecha_creacion(),0,10)
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
        
        /**consultar datos cliente**/
        public function get_datos_clientes_by_nombre_autocompletePublic(){
            try{
                $arrDR = $this->get_clientes_datos_basicos();
                
                /***/
                $arrResponse = array();
                // verificamos //
                if(count($arrDR)>0){
                    $obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos();
                    for($i=0;$i<count($arrDR);$i++){
                        $obj_dao_clientes_datos_basicos->new_vo();
                        $obj_dao_clientes_datos_basicos->_vo = $arrDR[$i];

                        $arrResponse[$i]['id'] = $obj_dao_clientes_datos_basicos->_vo->get_id();
                        switch(request::get_parameter('field')){
                            case "nombres":
                                $arrResponse[$i]['label'] = $obj_dao_clientes_datos_basicos->_vo->get_identificacion().' - '.($obj_dao_clientes_datos_basicos->_vo->get_nombres().' '.$obj_dao_clientes_datos_basicos->_vo->get_apellidos());
                                $arrResponse[$i]['value'] = ($obj_dao_clientes_datos_basicos->_vo->get_nombres());
                                break;
                            case "apellidos":
                                $arrResponse[$i]['label'] = $obj_dao_clientes_datos_basicos->_vo->get_identificacion().' - '.($obj_dao_clientes_datos_basicos->_vo->get_nombres().' '.$obj_dao_clientes_datos_basicos->_vo->get_apellidos());
                                $arrResponse[$i]['value'] = ($obj_dao_clientes_datos_basicos->_vo->get_apellidos());
                                break;
                            case "identificacion":
                                $arrResponse[$i]['label'] = $obj_dao_clientes_datos_basicos->_vo->get_identificacion().' - '.($obj_dao_clientes_datos_basicos->_vo->get_nombres().' '.$obj_dao_clientes_datos_basicos->_vo->get_apellidos());
                                $arrResponse[$i]['value'] = ($obj_dao_clientes_datos_basicos->_vo->get_identificacion());
                                break;
                        }
                        $arrResponse[$i]['identificacion'] = ($obj_dao_clientes_datos_basicos->_vo->get_identificacion());
                        $arrResponse[$i]['nombres'] = ($obj_dao_clientes_datos_basicos->_vo->get_nombres());
                        $arrResponse[$i]['apellidos'] = ($obj_dao_clientes_datos_basicos->_vo->get_apellidos());
                    }
                }
                utilidades::set_response($arrResponse);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**consultar en datos_basicos**/
        private function get_clientes_datos_basicos($sidx=null, $sord=null, $start=null, $limit=null,$estado='A'){
            try{
                /* set valores */
                $obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos();
                $obj_dao_clientes_datos_basicos->_vo->set_estado($estado);
                
                /** exist filter varios */
                if(request::get_parameter('clientes_datos_basicos_id')!=null){
                    $obj_dao_clientes_datos_basicos->_vo->set_id(request::get_parameter('clientes_datos_basicos_id'));
                }
                if(request::get_parameter('nombres')!=null){
                    $obj_dao_clientes_datos_basicos->_vo->set_nombres(request::get_parameter('nombres'));
                }
                if(request::get_parameter('apellidos')!=null){
                    $obj_dao_clientes_datos_basicos->_vo->set_apellidos(request::get_parameter('apellidos'));
                }
                if(request::get_parameter('identificacion')!=null){
                    $obj_dao_clientes_datos_basicos->_vo->set_identificacion(request::get_parameter('identificacion'));
                }
                if(request::get_parameter('term')!=null){
                    switch(request::get_parameter('field')){
                        case "identificacion":
                            $obj_dao_clientes_datos_basicos->_vo->set_identificacion("EXP||LIKE||%".request::get_parameter('term')."%");
                            break;
                        case "nombres":
                            $obj_dao_clientes_datos_basicos->_vo->set_nombres("EXP||LIKE||%".ucwords(request::get_parameter('term'))."%");
                            break;
                    }
                }
                /* set filters */
                if(request::get_parameter('filters')!=null){
                    utilidades::parsear_filters($obj_dao_clientes_datos_basicos->_vo);
                }
                /* sacamos los registros en estado activo*/
                return $obj_dao_clientes_datos_basicos->select_rows($sidx, $sord, $start, $limit)->fetch_object_vo();
            }catch (\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /***/
        private function get_clientes_datos_contactos($clientes_datos_basicos_id=null){
            try{
                $obj_dao_clientes_datos_contactos = new dao_clientes_datos_contactos();
                
                if($clientes_datos_basicos_id!=null){
                    $obj_dao_clientes_datos_contactos->_vo->set_clientes_datos_basicos_id($clientes_datos_basicos_id);
                }                
                return $obj_dao_clientes_datos_contactos->select_rows()->fetch_object_vo();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
    
        /****/
        private function get_clientes_datos_ubicaciones($clientes_datos_basicos_id=null){
            try{
                $obj_dao_clientes_datos_ubicaciones = new dao_clientes_datos_ubicaciones();
                
                if($clientes_datos_basicos_id!=null){
                    $obj_dao_clientes_datos_ubicaciones->_vo->set_clientes_datos_basicos_id($clientes_datos_basicos_id);
                }
                
                return $obj_dao_clientes_datos_ubicaciones->select_rows()->fetch_object_vo();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /***/
        public function salvar_clientes_datos_nuevoPublic(){
            $obj_dao_base = new dao_base();
            try{
                $obj_dao_base->begin();
                
                /**insertamos datos basicos**/
                $last_insert_id = $this->insert_clientes_datos_basicos();
                
                /**insertamos datos de contacto**/
                $this->insert_clientes_datos_contactos($last_insert_id);
                
                /**insertamos datos de ubicacion**/
                $this->insert_clientes_datos_ubicaciones($last_insert_id);
                
                $obj_dao_base->commit();
                
                utilidades::set_response(array('msj'=>'Proceso terminado correctamente'));
            } catch (\Exception $ex) {
                $obj_dao_base->rollback();
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /****/
        public function actualizar_clientes_datosPublic(){
            $obj_dao_base = new dao_base();
            try{
                $obj_dao_base->begin();
                
                /**actualizzamos datos basicoss*/
                $this->update_clientes_datos_basicos();
                
                /**actualizamos datos d contacto**/
                $this->update_clientes_datos_contactos(request::get_parameter('hdnid'));
                
                /**actualizamos datos de ubicacion**/
                $this->update_clientes_datos_ubicaciones(request::get_parameter('hdnid'));
                
                $obj_dao_base->commit();
                
                utilidades::set_response(array('msj'=>'Proceso terminado correctamente'));
            } catch (\Exception $ex) {
                $obj_dao_base->rollback();
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /****/
        private function insert_clientes_datos_basicos(){
            try{
                /**validamos que no exista otro cliente con el mismo numero de cedula*/
                if($this->validate_exist_cliente_by_identificacion(request::get_parameter('txtIdentificacion_clientes'))==true){
                    throw new \Exception("Existe otro CLIENTE con el numero de identificacion (".request::get_parameter('txtIdentificacion_clientes')."), imposible crear otro con el mismo numero de documento");
                }
                
                /**validamos que el nombre y apellido no existan**/
                if($this->validate_exist_cliente_by_nombres_apellidos(request::get_parameter('txtNombres_clientes'),request::get_parameter('txtApellidos_clientes'))){
                    throw new \Exception("Existe otro cliente con el NOMBRE(S) y APELLIDO(S) (".request::get_parameter('txtNombres_clientes').' '.request::get_parameter('txtApellidos_clientes')."), imposible crear otro con el mismo nombre");
                }
                
                $obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos();
                $obj_dao_clientes_datos_basicos->_vo->set_identificacion(request::get_parameter('txtIdentificacion_clientes'));
                $obj_dao_clientes_datos_basicos->_vo->set_tipo_identificacion(request::get_parameter('selTipoDocumento_clientes'));
                $obj_dao_clientes_datos_basicos->_vo->set_nombres(ucwords(strtolower(request::get_parameter('txtNombres_clientes'))));
                $obj_dao_clientes_datos_basicos->_vo->set_apellidos(ucwords(strtolower(request::get_parameter('txtApellidos_clientes'))));
                $obj_dao_clientes_datos_basicos->_vo->set_estado(request::get_parameter('selEstado_clientes'));
                /****/
                if(request::get_parameter('txtFecha_Nacimiento_clientes')!="" && request::get_parameter('txtFecha_Nacimiento_clientes_mes')!=""){
                    $strFecha_nac = @date('Y').'-'.
                                    request::get_parameter('txtFecha_Nacimiento_clientes_mes').'-'.
                                    request::get_parameter('txtFecha_Nacimiento_clientes');
                    $obj_dao_clientes_datos_basicos->_vo->set_fecha_nacimiento($strFecha_nac);
                }
                $obj_dao_clientes_datos_basicos->_vo->set_origen_referido(request::get_parameter('selOrigenReferido'));
                if(request::get_parameter('selOrigenReferido')==1){
                    /**ingresamos el referido*/
                    $obj_dao_clientes_datos_basicos->_vo->set_origen_referido_cliente_id(request::get_parameter('hdnOrReferido_1'));
                }else{
                    /**ingresamos el otro**/
                    $obj_dao_clientes_datos_basicos->_vo->set_origen_referido_otro(request::get_parameter('txtOrReferido_2'));
                }
                /***insertamos en clientes datos basicos**/
                $obj_dao_clientes_datos_basicos->insert_rows();
                return $obj_dao_clientes_datos_basicos->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**ingresamos los datos de clientes contactos***/
        private function insert_clientes_datos_contactos($clientes_datos_basicos_id){
            try{
                $obj_dao_clientes_datos_contactos = new dao_clientes_datos_contactos();
                $obj_dao_clientes_datos_contactos->_vo->set_clientes_datos_basicos_id($clientes_datos_basicos_id);
                if(request::get_parameter('txtTelfono_fijo_clientes')!=null){
                    $obj_dao_clientes_datos_contactos->_vo->set_telefono_fijo(request::get_parameter('txtTelfono_fijo_clientes'));
                }
                
                if(request::get_parameter('txtTelefono_celular_clientes')!=null){
                    $obj_dao_clientes_datos_contactos->_vo->set_telefono_celular(request::get_parameter('txtTelefono_celular_clientes'));
                }
                
                if(request::get_parameter('txtTelefono_celular_whatsapp_clientes')!=null){
                    $obj_dao_clientes_datos_contactos->_vo->set_telefono_celular_whatsapp(request::get_parameter('txtTelefono_celular_whatsapp_clientes'));
                }
                
                if(request::get_parameter('txtEmail_clientes')!=null){
                    $obj_dao_clientes_datos_contactos->_vo->set_email(request::get_parameter('txtEmail_clientes'));
                }
                
                /**insertamos**/
                $obj_dao_clientes_datos_contactos->insert_rows();
                
                return true;
            } catch (Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
    
        /** ingresamos los datos de clientes ubicaciones*/
        private function insert_clientes_datos_ubicaciones($clientes_datos_basicos_id){
            try{
                $obj_clientes_datos_ubicaciones = new dao_clientes_datos_ubicaciones();
                $obj_clientes_datos_ubicaciones->_vo->set_clientes_datos_basicos_id($clientes_datos_basicos_id);
                
                if(request::get_parameter('txtDireccion_clientes')!=null){
                    $obj_clientes_datos_ubicaciones->_vo->set_direccion(request::get_parameter('txtDireccion_clientes'));
                }
                
                if(request::get_parameter('txtBarrio_clientes')!=null){
                    $obj_clientes_datos_ubicaciones->_vo->set_barrio(request::get_parameter('txtBarrio_clientes'));
                }
                
                if(request::get_parameter('hdnCiudad_clientes')!=null){
                    $obj_clientes_datos_ubicaciones->_vo->set_parametros_ciudades_id(request::get_parameter('hdnCiudad_clientes'));
                }
                
                if(request::get_parameter('selZona_clientes')!=null){
                    $obj_clientes_datos_ubicaciones->_vo->set_zona(request::get_parameter('selZona_clientes'));
                }
                
                /****/
                $obj_clientes_datos_ubicaciones->insert_rows();
                
                return true;
            } catch (Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * consulta losdatos de un cliente por el id
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @param null
         * @return json
         */
        public function get_clientes_datos_by_idPublic(){
            try{
                $arr_response = new \stdClass();
                /**set id cliente*/
                request::set_request("clientes_datos_basicos_id",request::get_parameter('idrow'));
                /**consultar datos basicos**/
                $arrObj_cli = $this->get_clientes_datos_basicos();
                
                $arr_response->rows[0] = new \stdClass();
                
                $arr_response->rows[0]->id = $arrObj_cli[0]->get_id();
                $arr_response->rows[0]->nombres = $arrObj_cli[0]->get_nombres();
                $arr_response->rows[0]->apellidos = $arrObj_cli[0]->get_apellidos();
                $arr_response->rows[0]->identificacion = $arrObj_cli[0]->get_identificacion();
                $arr_response->rows[0]->tipo_identificacion = $arrObj_cli[0]->get_tipo_identificacion();
                
                $arr_response->rows[0]->dia_nac = null;
                $arr_response->rows[0]->mes_nac = null;
                if($arrObj_cli[0]->get_fecha_nacimiento()!=null){
                    $arr_f = explode("-",$arrObj_cli[0]->get_fecha_nacimiento());
                    if(count($arr_f)>0){
                        $arr_response->rows[0]->dia_nac = (int)$arr_f[2];
                        $arr_response->rows[0]->mes_nac = (int)$arr_f[1];
                    }
                }
                $arr_response->rows[0]->estado = $arrObj_cli[0]->get_estado();
                $arr_response->rows[0]->origen_referido = $arrObj_cli[0]->get_origen_referido();
                $arr_response->rows[0]->origen_referido_otro = null;
                $arr_response->rows[0]->origen_referido_cliente_id = null;
                if($arrObj_cli[0]->get_origen_referido()==1){
                    /**buscamos el referido**/
                    $arr_response->rows[0]->origen_referido_cliente_id = $arrObj_cli[0]->get_origen_referido_cliente_id();
                    $obj_clientes_datos_basicos_r = new dao_clientes_datos_basicos($arrObj_cli[0]->get_origen_referido_cliente_id());
                    $arr_response->rows[0]->nombre_referidor = $obj_clientes_datos_basicos_r->_vo->get_nombres();
                    $arr_response->rows[0]->nombre_referidor_completo = ($obj_clientes_datos_basicos_r->_vo->get_nombres().' '.$obj_clientes_datos_basicos_r->_vo->get_apellidos().' - '.$obj_clientes_datos_basicos_r->_vo->get_identificacion());
                }
                if($arrObj_cli[0]->get_origen_referido()==5){
                    /**buscamos el otro origen**/
                    $arr_response->rows[0]->origen_referido_otro = $arrObj_cli[0]->get_origen_referido_otro();
                }
                
                /***datos de contacto***/
                $arrObj_d_contac = $this->get_clientes_datos_contactos(request::get_parameter('idrow'));
                $arr_response->rows[0]->telefono_fijo = $arrObj_d_contac[0]->get_telefono_fijo();
                $arr_response->rows[0]->telefono_celular = $arrObj_d_contac[0]->get_telefono_celular();
                $arr_response->rows[0]->telefono_celular_whatsapp = $arrObj_d_contac[0]->get_telefono_celular_whatsapp();
                $arr_response->rows[0]->email = $arrObj_d_contac[0]->get_email();
                
                /***datos de ubicacion**/
                $arrObj_d_ubica = $this->get_clientes_datos_ubicaciones(request::get_parameter('idrow'));
                $arr_response->rows[0]->direccion = $arrObj_d_ubica[0]->get_direccion();
                $arr_response->rows[0]->barrio = $arrObj_d_ubica[0]->get_barrio();
                $arr_response->rows[0]->zona = $arrObj_d_ubica[0]->get_zona();
                $arr_response->rows[0]->parametros_ciudades_id = $arrObj_d_ubica[0]->get_parametros_ciudades_id();
                $obj_dao_parametros_ciudades = new dao_parametros_ciudades($arrObj_d_ubica[0]->get_parametros_ciudades_id());
                $arr_response->rows[0]->nombre_ciudad = $obj_dao_parametros_ciudades->_vo->get_Name();
                
                utilidades::set_response($arr_response);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /****/
        private function validate_exist_cliente_by_identificacion($n_identificacion){
            try{
                $obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos();
                $obj_dao_clientes_datos_basicos->_vo->set_identificacion(trim($n_identificacion));
                
                if(request::get_parameter('hdnid')!=null && request::get_parameter('hdnid')!=''){
                    $obj_dao_clientes_datos_basicos->_vo->set_id("EXP||NOT IN||".request::get_parameter('hdnid')."");
                }
                
                $arrObj_dat = $obj_dao_clientes_datos_basicos->select_rows()->fetch_object_vo();
                
                if($arrObj_dat!=null){
                    return true;
                }else{
                    return false;
                }
                
            } catch (Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
    
        /***/
        private function validate_exist_cliente_by_nombres_apellidos($n_nombres,$n_apellidos){
            try{
                $obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos();
                
                if(request::get_parameter('hdnid')!=null && request::get_parameter('hdnid')!=''){
                    $obj_dao_clientes_datos_basicos->_vo->set_id("EXP||NOT IN||".request::get_parameter('hdnid')."");
                }
                
                $obj_dao_clientes_datos_basicos->_vo->set_nombres(trim($n_nombres));
                $obj_dao_clientes_datos_basicos->_vo->set_apellidos(trim($n_apellidos));
                
                $arrObj_cli = $obj_dao_clientes_datos_basicos->select_rows()->fetch_object_vo();
                
                if($arrObj_cli!=null){
                    return true;
                }else{
                    return false;
                }
                
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /****/
        private function update_clientes_datos_basicos(){
            try{
                /**validamos que no exista otro cliente con el mismo numero de cedula*/
                if($this->validate_exist_cliente_by_identificacion(request::get_parameter('txtIdentificacion_clientes'))==true){
                    throw new \Exception("Existe otro CLIENTE con el numero de identificacion (".request::get_parameter('txtIdentificacion_clientes')."), imposible crear otro con el mismo numero de documento");
                }
                
                /**validamos que el nombre y apellido no existan**/
                if($this->validate_exist_cliente_by_nombres_apellidos(request::get_parameter('txtNombres_clientes'),request::get_parameter('txtApellidos_clientes'))){
                    throw new \Exception("Existe otro cliente con el NOMBRE(S) y APELLIDO(S) (".request::get_parameter('txtNombres_clientes').' '.request::get_parameter('txtApellidos_clientes')."), imposible crear otro con el mismo nombre");
                }
                
                $obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos();
                $obj_dao_clientes_datos_basicos->_vo->set_id(request::get_parameter('hdnid'));
                $obj_dao_clientes_datos_basicos->_vo->set_identificacion(request::get_parameter('txtIdentificacion_clientes'));
                $obj_dao_clientes_datos_basicos->_vo->set_tipo_identificacion(request::get_parameter('selTipoDocumento_clientes'));
                $obj_dao_clientes_datos_basicos->_vo->set_nombres(ucwords(strtolower(request::get_parameter('txtNombres_clientes'))));
                $obj_dao_clientes_datos_basicos->_vo->set_apellidos(ucwords(strtolower(request::get_parameter('txtApellidos_clientes'))));
                $obj_dao_clientes_datos_basicos->_vo->set_estado(request::get_parameter('selEstado_clientes'));
                /****/
                if(request::get_parameter('txtFecha_Nacimiento_clientes')!="" && request::get_parameter('txtFecha_Nacimiento_clientes_mes')!=""){
                    $strFecha_nac = @date('Y').'-'.
                                    request::get_parameter('txtFecha_Nacimiento_clientes_mes').'-'.
                                    request::get_parameter('txtFecha_Nacimiento_clientes');
                    $obj_dao_clientes_datos_basicos->_vo->set_fecha_nacimiento($strFecha_nac);
                }
                $obj_dao_clientes_datos_basicos->_vo->set_origen_referido(request::get_parameter('selOrigenReferido'));
                if(request::get_parameter('selOrigenReferido')==1){
                    /**ingresamos el referido*/
                    $obj_dao_clientes_datos_basicos->_vo->set_origen_referido_cliente_id(request::get_parameter('hdnOrReferido_1'));
                }else{
                    /**ingresamos el otro**/
                    $obj_dao_clientes_datos_basicos->_vo->set_origen_referido_otro(request::get_parameter('txtOrReferido_2'));
                }
                /***insertamos en clientes datos basicos**/
                $obj_dao_clientes_datos_basicos->update_rows();
                return $obj_dao_clientes_datos_basicos->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
    
        /****/
        private function update_clientes_datos_contactos($clientes_datos_basicos_id){
            try{
                $obj_dao_clientes_datos_contactos = new dao_clientes_datos_contactos();
                $obj_dao_clientes_datos_contactos->_vo->set_clientes_datos_basicos_id($clientes_datos_basicos_id);
                
                /**consultamos el id de la fila**/
                $arrObjCli_c = $obj_dao_clientes_datos_contactos->select_rows()->fetch_object_vo();
                
                $obj_dao_clientes_datos_contactos->new_vo();
                $obj_dao_clientes_datos_contactos->_vo->set_id($arrObjCli_c[0]->get_id());
                
                if(request::get_parameter('txtTelfono_fijo_clientes')!=null){
                    $obj_dao_clientes_datos_contactos->_vo->set_telefono_fijo(request::get_parameter('txtTelfono_fijo_clientes'));
                }
                
                if(request::get_parameter('txtTelefono_celular_clientes')!=null){
                    $obj_dao_clientes_datos_contactos->_vo->set_telefono_celular(request::get_parameter('txtTelefono_celular_clientes'));
                }
                
                if(request::get_parameter('txtTelefono_celular_whatsapp_clientes')!=null){
                    $obj_dao_clientes_datos_contactos->_vo->set_telefono_celular_whatsapp(request::get_parameter('txtTelefono_celular_whatsapp_clientes'));
                }
                
                if(request::get_parameter('txtEmail_clientes')!=null){
                    $obj_dao_clientes_datos_contactos->_vo->set_email(request::get_parameter('txtEmail_clientes'));
                }
                
                /**insertamos**/
                $obj_dao_clientes_datos_contactos->update_rows();
                
                return true;
            } catch (Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /** actualizamos los datos de clientes ubicaciones*/
        private function update_clientes_datos_ubicaciones($clientes_datos_basicos_id){
            try{
                $obj_clientes_datos_ubicaciones = new dao_clientes_datos_ubicaciones();
                $obj_clientes_datos_ubicaciones->_vo->set_clientes_datos_basicos_id($clientes_datos_basicos_id);
                
                /**consultamos el id de la fila**/
                $arrObjCli_u = $obj_clientes_datos_ubicaciones->select_rows()->fetch_object_vo();
                
                $obj_clientes_datos_ubicaciones->new_vo();
                $obj_clientes_datos_ubicaciones->_vo->set_id($arrObjCli_u[0]->get_id());
                
                if(request::get_parameter('txtDireccion_clientes')!=null){
                    $obj_clientes_datos_ubicaciones->_vo->set_direccion(request::get_parameter('txtDireccion_clientes'));
                }
                
                if(request::get_parameter('txtBarrio_clientes')!=null){
                    $obj_clientes_datos_ubicaciones->_vo->set_barrio(request::get_parameter('txtBarrio_clientes'));
                }
                
                if(request::get_parameter('hdnCiudad_clientes')!=null){
                    $obj_clientes_datos_ubicaciones->_vo->set_parametros_ciudades_id(request::get_parameter('hdnCiudad_clientes'));
                }
                
                if(request::get_parameter('selZona_clientes')!=null){
                    $obj_clientes_datos_ubicaciones->_vo->set_zona(request::get_parameter('selZona_clientes'));
                }
                
                /****/
                $obj_clientes_datos_ubicaciones->update_rows();
                
                return true;
            } catch (Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
    
        /****/
        public function delete_clientes_datosPublic(){
            try{
                $obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos();
                $obj_dao_clientes_datos_basicos->_vo->set_id(request::get_parameter('idrow'));
                $obj_dao_clientes_datos_basicos->_vo->set_estado('I');
                
                $obj_dao_clientes_datos_basicos->update_rows();
                
                utilidades::set_response(array('msj'=>'Proceso terminado correctamente'));
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
    }
}