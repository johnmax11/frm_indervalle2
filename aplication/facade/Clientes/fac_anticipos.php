<?php 
namespace MiProyecto{
    class fac_anticipos{
        public $_request;
        public $_utiSsn;

        function __construct(){}
		
		/*
		 * action para buscar un producto por su nombre
		 */
		public function searchrowsPublic(){
			$this->set_filters_grilla_basPrivate($page,$limit,$sidx,$sord);
			/* get the direction */
			if(!$sidx){ 
				$sidx =1; 
			}
			/* set valores */
			$obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos();
			$obj_dao_clientes_datos_basicos->new_vo();
			$obj_dao_clientes_datos_basicos->_vo->set_estado('A');
			/** exist filter varios */
			if(isset($this->_request['nombres'])){
				$obj_dao_clientes_datos_basicos->_vo->set_nombres($this->_request['nombres']);
			}
			if(isset($this->_request['apellidos'])){
				$obj_dao_clientes_datos_basicos->_vo->set_apellidos($this->_request['apellidos']);
			}
			if(isset($this->_request['identificacion'])){
				$obj_dao_clientes_datos_basicos->_vo->set_identificacion($this->_request['identificacion']);
			}
			/* set filters */
			if(isset($this->_request['filters'])){
				utilidades::parsear_filters($obj_dao_clientes_datos_basicos->_vo,$this->_request);
			}
			/* connect to the database */
			/* sacamos los registros en estado activo*/
			$arrDatos = $obj_dao_clientes_datos_basicos->select_rows(
										$obj_dao_clientes_datos_basicos->_vo,
										null,null,null,null
						);
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
			$responce->page = $page; 
			$responce->total = $total_pages; 
			$responce->records = $count;
			/* verificamos campos fk */
			if(utilidades::verifica_campo_fk($sidx)==false){
				$arrDatos = array();
				$arrDatos = $obj_dao_clientes_datos_basicos->select_rows(
											$obj_dao_clientes_datos_basicos->_vo,
											$sidx, $sord, $start, $limit
							);
			}
			$numRows = count($arrDatos);
			for($i=0;$i<$numRows;$i++){
				$obj_vo_clientes_datos_basicos = $arrDatos[$i];
				
				/* sacamos el nombre del usuario */
				$obj_dao_usuarios = new dao_usuarios();
				$obj_dao_usuarios->new_vo();
				$obj_dao_usuarios->_vo->set_id($obj_vo_clientes_datos_basicos->get_creado_por());
				/* ejecutamos */
				$arrDatos_usuarios = $obj_dao_usuarios->select_rows($obj_dao_usuarios->_vo);
				$obj_dao_usuarios->_vo = $arrDatos_usuarios[0];
				/**/
				$responce->rows[$i]['id']=$obj_vo_clientes_datos_basicos->get_id();
				$responce->rows[$i]['cell']=array(
					$obj_vo_clientes_datos_basicos->get_id(),
					utf8_encode(ucwords(strtolower($obj_vo_clientes_datos_basicos->get_nombres()))),
					utf8_encode(ucwords(strtolower($obj_vo_clientes_datos_basicos->get_apellidos()))),
					$obj_vo_clientes_datos_basicos->get_identificacion(),
					$obj_vo_clientes_datos_basicos->get_fecha_nacimiento(),
					$obj_vo_clientes_datos_basicos->get_talla(),
					'',
					strtoupper($obj_dao_usuarios->_vo->get_usuario()),
					substr($obj_vo_clientes_datos_basicos->get_fecha_creacion(),0,10)
				);
			}
			
			utilidades::set_response($responce);
		}
		
		/***/
		private function set_filters_grilla_basPrivate(&$page,&$limit,&$sidx,&$sord){
			$page = (isset($this->_request['page'])?$this->_request['page']:1); 
			/* get the requested page */
			$limit = (isset($this->_request['rows'])?$this->_request['rows']:999999999);
			/* get how many rows we want to have into the grid */
			$sidx = (isset($this->_request['sidx'])?$this->_request['sidx']:'id');
			/* get index row - i.e. user click to sort */
			$sord = (isset($this->_request['sord'])?$this->_request['sord']:'ASC');
		}
		
		/**/
		public function addeditrowsPublic(){
			try{
				$ext_bol = (
								isset($this->_request['hdnid_TabHC1'])?
								'_TabHC1':
								(isset($this->_request['hdnid_cliente'])?
								  '':''
								)
							);
				/* start transaccion */
				$obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos();
				$obj_dao_clientes_datos_contactos = new dao_clientes_datos_contactos();
				$obj_dao_clientes_datos_ubiaciones = new dao_clientes_datos_ubicaciones();
				$obj_dao_clientes_datos_basicos->begin();
				
				$obj_dao_clientes_datos_basicos->new_vo();
				/** verificamos si el cliente existe */
				$obj_dao_clientes_datos_basicos->_vo->set_id(
																(isset($this->_request['hdnid_cliente'])?
																	$this->_request['hdnid_cliente']:
																	$this->_request['hdnid'.$ext_bol])!=""?
																$this->_request['hdnid'.$ext_bol]:-1
															);
				/** execute **/
				$arrRsp = $obj_dao_clientes_datos_basicos->select_rows($obj_dao_clientes_datos_basicos->_vo);
				
				$date_tmp = utilidades::get_current_timestamp();
				
				/** insertamos en datos basicos */
				$obj_dao_clientes_datos_basicos->_vo->set_identificacion($this->_request['txtIdentificacion_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_basicos->_vo->set_tipo_identificacion($this->_request['selTipoDocumento_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_basicos->_vo->set_nombres($this->_request['txtNombres_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_basicos->_vo->set_apellidos($this->_request['txtApellidos_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_basicos->_vo->set_talla($this->_request['selTalla_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_basicos->_vo->set_fecha_nacimiento($this->_request['txtFecha_Nacimiento_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_basicos->_vo->set_fecha_corte_nomina_1($this->_request['txtFecha_corte_nomina_1_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_basicos->_vo->set_fecha_corte_nomina_2($this->_request['txtFecha_corte_nomina_2_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_basicos->_vo->set_nombre_referencia($this->_request['txtReferencia_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_basicos->_vo->set_telefono_referencia($this->_request['txtTelefono_referencia_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_basicos->_vo->set_estado($this->_request['selEstado_clientes'.$ext_bol]);
				
				/***** insetamos los datos de contacto *************/
				$obj_dao_clientes_datos_contactos->new_vo();
				$obj_dao_clientes_datos_contactos->_vo->set_telefono_fijo($this->_request['txtTelfono_fijo_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_contactos->_vo->set_telefono_celular($this->_request['txtTelefono_celular_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_contactos->_vo->set_email($this->_request['txtEmail_clientes'.$ext_bol]);
				
				/**** insertamos en datos de ubicaciones ***********/
				$obj_dao_clientes_datos_ubiaciones->new_vo();
				$obj_dao_clientes_datos_ubiaciones->_vo->set_direccion($this->_request['txtDireccion_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_ubiaciones->_vo->set_barrio($this->_request['txtBarrio_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_ubiaciones->_vo->set_zona($this->_request['selZona_clientes'.$ext_bol]);
				$obj_dao_clientes_datos_ubiaciones->_vo->set_parametros_ciudades_id($this->_request['hdnCiudad_clientes'.$ext_bol]);
				
				/**/
				if($arrRsp == null){
					/* insertamos */
					$obj_dao_clientes_datos_basicos->_vo->set_id(null);
					$obj_dao_clientes_datos_basicos->_vo->set_creado_por($this->_utiSsn->get_ssn_id_users());
					$obj_dao_clientes_datos_basicos->_vo->set_fecha_creacion($date_tmp);
					/* execute */
					$bolresult = $obj_dao_clientes_datos_basicos->insert_rows($obj_dao_clientes_datos_basicos->_vo);
					/** obtenemos el ultimo id */
					$last_clientes_id = $obj_dao_clientes_datos_basicos->get_last_insert_id();
					
					$obj_dao_clientes_datos_contactos->_vo->set_clientes_datos_basicos_id($last_clientes_id);
					$obj_dao_clientes_datos_contactos->_vo->set_creado_por($this->_utiSsn->get_ssn_id_users());
					$obj_dao_clientes_datos_contactos->_vo->set_fecha_creacion($date_tmp);
					/* execute */
					$bolresult = $obj_dao_clientes_datos_contactos->insert_rows($obj_dao_clientes_datos_contactos->_vo);
				
					$obj_dao_clientes_datos_ubiaciones->_vo->set_clientes_datos_basicos_id($last_clientes_id);
					$obj_dao_clientes_datos_ubiaciones->_vo->set_creado_por($this->_utiSsn->get_ssn_id_users());
					$obj_dao_clientes_datos_ubiaciones->_vo->set_fecha_creacion($date_tmp);
					/* execute */
					$bolresult = $obj_dao_clientes_datos_ubiaciones->insert_rows($obj_dao_clientes_datos_ubiaciones->_vo);
				}else{
					/* actualizamos */
					$obj_dao_clientes_datos_basicos->_vo->set_modificado_por($this->_utiSsn->get_ssn_id_users());
					$obj_dao_clientes_datos_basicos->_vo->set_fecha_modificacion($date_tmp);
					$bolresult = $obj_dao_clientes_datos_basicos->update_rows($obj_dao_clientes_datos_basicos->_vo);
					
					$obj_dao_clientes_datos_contactos->_vo->set_id($obj_dao_clientes_datos_basicos->_vo->get_id());
					$obj_dao_clientes_datos_contactos->_vo->set_modificado_por($this->_utiSsn->get_ssn_id_users());
					$obj_dao_clientes_datos_contactos->_vo->set_fecha_modificacion($date_tmp);
					$bolresult = $obj_dao_clientes_datos_contactos->update_rows($obj_dao_clientes_datos_contactos->_vo);
				
					$obj_dao_clientes_datos_ubiaciones->_vo->set_id($obj_dao_clientes_datos_basicos->_vo->get_id());
					$obj_dao_clientes_datos_ubiaciones->_vo->set_modificado_por($this->_utiSsn->get_ssn_id_users());
					$obj_dao_clientes_datos_ubiaciones->_vo->set_fecha_modificacion($date_tmp);
					$bolresult = $obj_dao_clientes_datos_ubiaciones->update_rows($obj_dao_clientes_datos_ubiaciones->_vo);
					
				}
				$obj_dao_clientes_datos_basicos->commit();
				/**/
				utilidades::set_response(array('msj'=>'Proceso terminado correctamente'));
			}catch(\Exception $e){
				$obj_dao_clientes_datos_basicos->rollback();
				utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
			}
			
			/* end transaccion */
			return true;
		}
		/**/
		public function deleterowsPublic(){
			try{
				/* set datos */
				$obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos();
				$obj_dao_clientes_datos_basicos->new_vo();
				$obj_dao_clientes_datos_basicos->_vo->set_id($this->_request['idrow']);
				$obj_dao_clientes_datos_basicos->_vo->set_estado('C');
				$obj_dao_clientes_datos_basicos->_vo->set_modificado_por($this->_utiSsn->get_ssn_id_users());
				$obj_dao_clientes_datos_basicos->_vo->set_fecha_modificacion(utilidades::get_current_timestamp());
				$result = $obj_dao_clientes_datos_basicos->update_rows($obj_dao_clientes_datos_basicos->_vo);
			
				utilidades::set_response(array("msj"=>"Proceso terminado correctamente"));
			}catch(\Exception $e){
				utilidades::set_response(array("msj"=>"Error: --->".__METHOD__.'--->'.$e->getMessage()),true);
			}
		}
		/**/
		public function searchdatosrowsPublic(){
			try{
				$arrResponse = array();
				
				/** buscamos los datos basicos */
				$obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos();
				$obj_dao_clientes_datos_basicos->_vo->set_id($this->_request['idrow']);
				$obj_dao_clientes_datos_basicos->_vo->set_estado("A");
				$arrResult = $obj_dao_clientes_datos_basicos->select_rows()->fetch_object_vo();
				/* validamos el cliente */
				if(count($arrResult)>0){
					$obj_dao_clientes_datos_basicos->_vo = $arrResult[0];
					
				}
				
				utilidades::set_response($arrResponse);
			}catch(\Exception $e){
				utilidades::set_response(array("msj"=>"Error: --->".__METHOD__."--->".$e->getMessage()),true);
			}
		}
		/**********************************************************************/
		/****/
		public function searchdatosrows_by_cliente_idPublic(){
                    try{
                            $arrResponse = array();

                            /** buscamos los datos basicos */
                            $obj_dao_clientes_anticipos = new dao_clientes_anticipos();
                            $obj_dao_clientes_anticipos->_vo->set_clientes_datos_basicos_id($this->_request['idrow']);
                            $arrResult = $obj_dao_clientes_anticipos->select_rows()->fetch_object_vo();
                            /* validamos el cliente */
                            if(count($arrResult)>0){
                                    $obj_dao_clientes_anticipos->_vo = $arrResult[0];

                                    $arrResponse['rows'][0]['saldo_favor'] = $obj_dao_clientes_anticipos->_vo->get_valor();
                            }else{
                                    $arrResponse['rows'][0]['saldo_favor'] = 0;
                            }

                            utilidades::set_response($arrResponse);
                    }catch(\Exception $e){
                            utilidades::set_response(array("msj"=>"Error: --->".__METHOD__."--->".$e->getMessage()),true);
                    }
		}
		
        /***/
        public function select_datos_entradas_salidasPublic(){
            try{
                    $arrResponse = array();
                    /*************************************************/
                    /** consultamos el id del cliente en anticipos */
                    $obj_dao_clientes_anticipos = new dao_clientes_anticipos();
                    /***/
                    $obj_dao_clientes_anticipos->_vo->set_clientes_datos_basicos_id($this->_request['idcliente']);
                    /**execute*/
                    $arrDUSAn = $obj_dao_clientes_anticipos->select_rows()->fetch_object_vo();

                    if(count($arrDUSAn)>0){
                        $obj_dao_clientes_anticipos->_vo = $arrDUSAn[0]; 
                        $anticipo_id = $obj_dao_clientes_anticipos->_vo->get_id();
                        /*****************************************************/
                        /** consultamos las entradas */
                        $obj_dao_clientes_anticipos_entradas = new dao_clientes_anticipos_entradas();
                        /***/
                        $obj_dao_clientes_anticipos_entradas->_vo->set_clientes_anticipos_id($anticipo_id);
                        $obj_dao_clientes_anticipos_entradas->_vo->set_valor(array(1));
                        /** execute */
                        $arrDEntradas = $obj_dao_clientes_anticipos_entradas->select_rows()->fetch_object_vo();

                        $cont = 0;
                        /*** recorremos los rows */
                        for($i=0;$i<count($arrDEntradas);$i++){
                            $obj_dao_clientes_anticipos_entradas->_vo = $arrDEntradas[$i];

                            $arrResponse['rows'][$cont]['id_ant_entradas'] = $obj_dao_clientes_anticipos_entradas->_vo->get_id();
                            $arrResponse['rows'][$cont]['fecha_creacion'] = $obj_dao_clientes_anticipos_entradas->_vo->get_fecha_creacion();
                            $arrResponse['rows'][$cont]['vr_entro'] = $obj_dao_clientes_anticipos_entradas->_vo->get_valor();
                            $arrResponse['rows'][$cont]['vr_salio'] = $obj_dao_clientes_anticipos_entradas->_vo->get_valor_usado();
                            $arrResponse['rows'][$cont]['estado'] = $obj_dao_clientes_anticipos_entradas->_vo->get_estado();
                            switch($obj_dao_clientes_anticipos_entradas->_vo->get_origen_entrada()){
                                case "F":
                                    $str_or = 'Abono a Factura';
                                    break;
                                case "R":
                                    $str_or = 'Referido';
                                    break;
                                case "P":
                                    $str_or = 'Abono a Pedido';
                                    break;
                                case "S":
                                    $str_or = 'Abono a Separado';
                                    break;
                                case "I":
                                    $str_or = 'Premio Sig. Compra';
                                    break;
                                case "N":
                                    $str_or = 'Premio Cli. Nuevo';
                                    break;
                            }
                            $arrResponse['rows'][$cont]['tipo'] = $str_or;
                            /***/
                            $obj_dao_usuarios = new dao_usuarios();
                            $obj_dao_usuarios->_vo->set_id($obj_dao_clientes_anticipos_entradas->_vo->get_creado_por());
                            /* execute**/
                            $arrDUser = $obj_dao_usuarios->select_rows()->fetch_object_vo();
                            $obj_dao_usuarios->_vo = $arrDUser[0];


                            $arrResponse['rows'][$cont]['creado_por'] = $obj_dao_usuarios->_vo->get_usuario();

                            $cont ++;
                        }


                        //echo print_r($arrResponse,true);
                        /** ordenamos la matriz por la fecha */
                        $arrResponse = utilidades::order_matriz($arrResponse,"fecha_creacion","TIM",'DESC');
                    } /** inf if count **/

                    utilidades::set_response($arrResponse);
                }catch(Exception $e){
                    utilidades::set_response(array("msj"=>"Error: --->".__METHOD__."--->".$e->getMessage()),true);
                }
        }
    }
}