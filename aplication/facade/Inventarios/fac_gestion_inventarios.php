<?php
namespace MiProyecto {
    class fac_gestion_inventarios {
        function __construct() {}
        /*
         * action para buscar un producto por su nombre
         */
        public function get_datos_grillaPublic(){
            try {
                $this->set_filters_grilla_basPrivate($page, $limit, $sidx, $sord);
                /* get the direction */
                if (!$sidx) {
                    $sidx = 1;
                }
                /*                 * consultamos los datos del cliente* */
                $arrDatos = $this->get_inventarios_principal();
                $count = count($arrDatos);
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                }
                if ($page > $total_pages)
                    $page = $total_pages;
                $start = $limit * $page - $limit;
                if ($start < 0) {
                    $start = 0;
                }
                /* do not put $limit*($page - 1) */
                $responce = new \stdClass();
                $responce->page = $page;
                $responce->total = $total_pages;
                $responce->records = $count;
                /* verificamos campos fk */
                /*
                  if (utilidades::verifica_campo_fk($sidx) == false) {
                  $arrDatos = array();
                  $arrDatos = $this->get_clientes_datos_basicos($sidx, $sord, $start, $limit);
                  }
                 * 
                 */
                $numRows = count($arrDatos);
                $obj_dao_inventarios_principal = new dao_inventarios_principal();

                for ($i = 0; $i < $numRows; $i++) {
                    $obj_dao_inventarios_principal->new_vo();
                    $obj_dao_inventarios_principal->_vo = $arrDatos[$i];

                    /* sacamos el nombre del usuario */
                    $obj_dao_usuarios = new dao_usuarios($obj_dao_inventarios_principal->_vo->get_creado_por());
                    $objVoProductosDatosBasicos = $obj_dao_productos_datos_basicos = new dao_productos_datos_basicos($obj_dao_inventarios_principal->_vo->get_productos_datos_basicos_id());

                    $responce->rows[$i]['id'] = $obj_dao_inventarios_principal->_vo->get_id();
                    $responce->rows[$i]['cell'] = array(
                        $obj_dao_inventarios_principal->_vo->get_id(),
                        $objVoProductosDatosBasicos->_vo->get_nombres(),
                        $objVoProductosDatosBasicos->_vo->get_descripcion(),
                        $obj_dao_inventarios_principal->_vo->get_cantidad(),
                        strtoupper($obj_dao_usuarios->_vo->get_usuario()),
                        substr($obj_dao_inventarios_principal->_vo->get_fecha_creacion(), 0, 10)
                    );
                }


                utilidades::set_response($responce);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /*         * */
        private function get_inventarios_principal($sidx = null, $sord = null, $start = null, $limit = null) {
            try {
                /* set valores */
                $obj_dao_clientes_datos_basicos = new dao_inventarios_principal();

                /** exist filter varios */
                if (request::get_parameter('id') != null) {
                    $obj_dao_clientes_datos_basicos->_vo->set_id(request::get_parameter('id'));
                }
                if (request::get_parameter('productos_datos_basicos_id') != null) {
                    $obj_dao_clientes_datos_basicos->_vo->set_productos_datos_basicos_id(request::get_parameter('productos_datos_basicos_id'));
                }
                if (request::get_parameter('cantidad') != null) {
                    $obj_dao_clientes_datos_basicos->_vo->set_cantidad(request::get_parameter('cantidad'));
                }
                if (request::get_parameter('creado_por') != null) {
                    $obj_dao_clientes_datos_basicos->_vo->set_creado_por(request::get_parameter('creado_por'));
                }
                /* set filters */
                if (request::get_parameter('filters') != null) {
                    utilidades::parsear_filters($obj_dao_clientes_datos_basicos->_vo);
                }
                /* sacamos los registros en estado activo */
                return $obj_dao_clientes_datos_basicos->select_rows($sidx, $sord, $start, $limit)->fetch_object_vo();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        private function set_filters_grilla_basPrivate(&$page, &$limit, &$sidx, &$sord) {
            try {
                $page = (request::get_parameter('page') != null ? request::get_parameter('page') : 1);
                /* get the requested page */
                $limit = (request::get_parameter('rows') != null ? request::get_parameter('rows') : 999999999);
                /* get how many rows we want to have into the grid */
                $sidx = (request::get_parameter('sidx') != null ? request::get_parameter('sidx') : 'id');
                /* get index row - i.e. user click to sort */
                $sord = (request::get_parameter('sord') != null ? request::get_parameter('sord') : 'ASC');
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**/
        public function addeditrowsPublic() {
            try {
                /*                 * */
                $obj_dao_inventarios_principal = new dao_inventarios_principal();
                $obj_dao_inventarios_principal->begin();

                $date_time = @date('Y-m-d H:i:s');

                /*                 * insertamos en general* */
                $last_gen_id = $this->insertar_inventarios_principal_generalPrivate($date_time);
                if (!$last_gen_id) {
                    throw new \Exception("Ingresando el general entradas");
                }

                /*                 * recorremos todas las filas* */
                for ($i = 0; $i < (int) ($this->_request['hdnglobalfilas']); $i++) {
                    if (!isset($this->_request['hdntextdivBodyAddEdit_dinamic' . $i . '0'])) {
                        continue;
                    }
                    /*                     * verificamps si el producto existe en inv principal */
                    $obj_dao_inventarios_principal->_vo->set_productos_datos_basicos_id($this->_request['hdntextdivBodyAddEdit_dinamic' . $i . '0']);
                    $obj_dao_inventarios_principal->_vo->set_talla($this->_request['selectdivBodyAddEdit_dinamic' . $i . '1']);
                    /*                     * * */
                    $arrD_vo = $obj_dao_inventarios_principal->select_rows()->fetch_object_vo();

                    if ($arrD_vo == null) {
                        /*                         * creamos el registro en inventarios principal* */
                        $obj_dao_inventarios_principal->_vo->set_creado_por($this->_utiSsn->get_ssn_id_users());
                        $obj_dao_inventarios_principal->_vo->set_fecha_creacion($date_time);
                        $obj_dao_inventarios_principal->_vo->set_cantidad($this->_request['numberdivBodyAddEdit_dinamic' . $i . '2']);
                        /*                         * execute */
                        $obj_dao_inventarios_principal->insert_rows();
                        $last_inventarios_id = $obj_dao_inventarios_principal->get_last_insert_id();
                    } else {
                        $obj_dao_inventarios_principal->_vo = $arrD_vo[0];
                        $last_inventarios_id = $obj_dao_inventarios_principal->_vo->get_id();
                        $cantidad_inv = $obj_dao_inventarios_principal->_vo->get_cantidad();
                        /*                         * actualizamos el inventarios principal* */
                        $obj_dao_inventarios_principal->new_vo();
                        $obj_dao_inventarios_principal->_vo->set_id($last_inventarios_id);
                        $obj_dao_inventarios_principal->_vo->set_cantidad($cantidad_inv + (int) $this->_request['numberdivBodyAddEdit_dinamic' . $i . '2']);
                        $obj_dao_inventarios_principal->_vo->set_modificado_por($this->_utiSsn->get_ssn_id_users());
                        $obj_dao_inventarios_principal->_vo->set_fecha_modificacion($date_time);
                        /*                         * execute* */
                        $obj_dao_inventarios_principal->update_rows();
                    }

                    /*                     * insertamos en detalles por cantidad* */
                    for ($j = 0; $j < (int) $this->_request['numberdivBodyAddEdit_dinamic' . $i . '2']; $j++) {
                        /*                         * *insrt en detalle* */
                        $last_inv_det_id = $this->insertar_inventarios_principal_detallesPrivate($date_time, $last_inventarios_id);
                        if (!$last_inv_det_id) {
                            throw new \Exception("Ingresando en inventarios principal detalles");
                        }

                        /*                         * insertamos en entradas detalles* */
                        if (!$this->insertar_inventarios_entradasPrivate($date_time, $last_gen_id, $last_inv_det_id)) {
                            throw new \Exception("Ingresando en inventarios entradas");
                        }
                    } /*                     * * */
                }/*                 * fin for principal* */

                $obj_dao_inventarios_principal->commit();

                utilidades::set_response(array('msj' => 'Proceso terminado correctamente'));
            } catch (\Exception $e) {
                $obj_dao_inventarios_principal->rollback();
                utilidades::set_response(array('msj' => 'Error: ---> ' . __METHOD__ . '--->' . $e->getMessage()), true);
            }

            /* end transaccion */
            return true;
        }

        /*         * */
        private function insertar_inventarios_principal_detallesPrivate($date_time, $last_inventarios_id) {
            try {
                $obj_dao_inventarios_principal_detalles = new dao_inventarios_principal_detalles();
                $obj_dao_inventarios_principal_detalles->_vo->set_inventarios_principal_id($last_inventarios_id);
                $obj_dao_inventarios_principal_detalles->_vo->set_inventarios_bodegas_id(1);
                $obj_dao_inventarios_principal_detalles->_vo->set_estado("A");
                $obj_dao_inventarios_principal_detalles->_vo->set_creado_por($this->_utiSsn->get_ssn_id_users());
                $obj_dao_inventarios_principal_detalles->_vo->set_fecha_creacion($date_time);
                /*                 * execute* */
                $obj_dao_inventarios_principal_detalles->insert_rows();
                return $obj_dao_inventarios_principal_detalles->get_last_insert_id();
            } catch (\Exception $e) {
                return false;
            }
        }

        /*         * * */
        private function insertar_inventarios_principal_generalPrivate($date_time) {
            try {
                $obj_dao_inventarios_principal_entradas_general = new dao_inventarios_principal_entradas_general();
                $obj_dao_inventarios_principal_entradas_general->_vo->set_creado_por($this->_utiSsn->get_ssn_id_users());
                $obj_dao_inventarios_principal_entradas_general->_vo->set_fecha_creacion($date_time);
                /*                 * execute* */
                $obj_dao_inventarios_principal_entradas_general->insert_rows();
                return $obj_dao_inventarios_principal_entradas_general->get_last_insert_id();
            } catch (\Exception $e) {
                return false;
            }
        }

        /*         * * */
        private function insertar_inventarios_entradasPrivate($date_time, $last_gen_id, $last_inv_det_id) {
            try {
                /*                 * */
                $obj_dao_inventarios_principal_entradas = new dao_inventarios_principal_entradas();
                $obj_dao_inventarios_principal_entradas->_vo->set_inventarios_principal_entradas_general_id($last_gen_id);
                $obj_dao_inventarios_principal_entradas->_vo->set_inventarios_principal_detalles_id($last_inv_det_id);
                $obj_dao_inventarios_principal_entradas->_vo->set_creado_por($this->_utiSsn->get_ssn_id_users());
                $obj_dao_inventarios_principal_entradas->_vo->set_fecha_creacion($date_time);

                /*                 * *execute */
                $obj_dao_inventarios_principal_entradas->insert_rows();

                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        /*         * * */
        public function search_detalles_cantidadPublic() {
            try {
                /*                 * */
                $obj_dao_inventarios_principal_detalles = new dao_inventarios_principal_detalles();
                $obj_dao_inventarios_principal_detalles->_vo->set_inventarios_principal_id($this->_request['id_inv']);
                $obj_dao_inventarios_principal_detalles->_vo->set_estado("A");
                /*                 * execute */
                $arr_vo = $obj_dao_inventarios_principal_detalles->select_rows()->fetch_object_vo();

                $arr_response = new \stdClass();

                if ($arr_vo != null) {
                    for ($i = 0; $i < count($arr_vo); $i++) {
                        $obj_dao_inventarios_principal_detalles->_vo = $arr_vo[$i];

                        $arr_response->rows[$i] = new \stdClass();

                        /*                         * sacamos datos de del inventarios principal* */
                        $obj_dao_inventarios_principal = new dao_inventarios_principal($obj_dao_inventarios_principal_detalles->_vo->get_inventarios_principal_id());

                        /*                         * sacamos los datos del producto* */
                        $obj_dao_productos_datos_basicos = new dao_productos_datos_basicos($obj_dao_inventarios_principal->_vo->get_productos_datos_basicos_id());

                        /*                         * *sacamos el nombre del usuario */
                        $obj_dao_usuarios = new dao_usuarios($obj_dao_inventarios_principal_detalles->_vo->get_creado_por());

                        /*                         * sacamos el nombre de la bodega* */
                        $obj_dao_inventarios_bodegas = new dao_inventarios_bodegas($obj_dao_inventarios_principal_detalles->_vo->get_inventarios_bodegas_id());

                        $arr_response->rows[$i]->referencia = $obj_dao_productos_datos_basicos->_vo->get_referencia();
                        $arr_response->rows[$i]->talla = $obj_dao_inventarios_principal->_vo->get_talla();
                        $arr_response->rows[$i]->bodega = $obj_dao_inventarios_bodegas->_vo->get_nombre();
                        $arr_response->rows[$i]->ingreso = $obj_dao_inventarios_principal_detalles->_vo->get_fecha_creacion();
                        $arr_response->rows[$i]->creado_por = strtoupper($obj_dao_usuarios->_vo->get_usuario());
                        $arr_response->rows[$i]->ult_modificacion = $obj_dao_inventarios_principal_detalles->_vo->get_fecha_modificacion();
                    }
                }
                utilidades::set_response($arr_response);
            } catch (\Exception $e) {
                $obj_dao_inventarios_principal->rollback();
                utilidades::set_response(array('msj' => 'Error: ---> ' . __METHOD__ . '--->' . $e->getMessage()), true);
            }
        }

        public function selectProductoLike() {
            try {
                //$objVo= new \MiProyecto\fac_gestion_productos;
                $obj_dao_productos_datos_basicos = new dao_productos_datos_basicos();
                $obj_dao_productos_datos_basicos->_vo->set_nombres("EXP||LIKE||%" . request::get_parameter('term') . "%");
                $obj_dao_productos_datos_basicos->_vo->set_estado("A");
                $arrVo = $obj_dao_productos_datos_basicos->select_rows()->fetch_object_vo();

                $arr_response = new \stdClass();
                for ($i = 0; $i < count($arrVo); $i++) {
                    $obj_dao_productos_datos_basicos->_vo = $arrVo[$i];
                    $arr_response->$i = new \stdClass();
                    $arr_response->$i->id = $obj_dao_productos_datos_basicos->_vo->get_id();
                    $arr_response->$i->label = $obj_dao_productos_datos_basicos->_vo->get_nombres();
                    $arr_response->$i->descripcion = strtoupper(utf8_encode($obj_dao_productos_datos_basicos->_vo->get_descripcion()));
                }

                utilidades::set_response($arr_response);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function selectBodegas() {
            try {
                $arrResponse = new \stdClass();
                $obj_dao_inventarios_bodegas = new dao_inventarios_bodegas();
                $arrObj = $obj_dao_inventarios_bodegas->select_rows()->fetch_object_vo();
                //echo "<pre>".print_r($arrObj);

                for ($i = 0; $i < count($arrObj); $i++) {
                    $arrResponse->rows[$i] = new \stdClass();
                    $arrResponse->rows[$i]->id = $arrObj[$i]->get_id();
                    $arrResponse->rows[$i]->nombre = $arrObj[$i]->get_nombre();
                }

                utilidades::set_response($arrResponse);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        public function insertInventarios() {
            try {
                $obj_dao_base = new dao_base();
                $obj_dao_base->begin();
                if (is_null(request::get_parameter("contador"))) {
                    throw new \Exception("Ocurrio un problema al momento de crear varias filas");
                }
                $lastIdEntradasGeneral = $this->setInsertInventariosPrincipalEntradasGeneral();
                for ($i = 0; $i <= request::get_parameter("contador"); $i++) {
                    if (!is_null(request::get_parameter("hdntxtReferencia" . $i))) {
                        if (request::get_parameter("hdntxtReferencia" . $i) == "") {
                            throw new \Exception("Ocurrio un problema con el codigo del producto, Asegurese de haber tomado uno de la lista");
                        }
                        request::set_request("productos_datos_basicos_id", request::get_parameter("hdntxtReferencia" . $i));
                        $objVo = $this->get_inventarios_principal();
                        if (!is_null($objVo)) {
                            $this->setUpdateInventariosPrincipal($objVo[0]->get_id(), request::get_parameter("txtCantidad" . $i)
                            );
                            $lastId = $objVo[0]->get_id();
                        } else {
                            $lastId = $this->setInsertInventariosPrincipal(
                                    request::get_parameter("hdntxtReferencia" . $i), request::get_parameter("txtCantidad" . $i)
                            );
                        }

                        if (request::get_parameter("selInventariosBodegas" . $i) == "") {
                            throw new \Exception("Ocurrio un error con las bodegas");
                        }
                        for ($z = 0; $z < request::get_parameter("txtCantidad" . $i); $z++) {
                            $lastIdInventariosDetalles = $this->setInsertInventariosPrincipalDetalles(
                                    $lastId, request::get_parameter("selInventariosBodegas" . $i), request::get_parameter("txtCantidad" . $i)
                            );
                            $this->setInsertInventariosPrincipalEntradas($lastIdEntradasGeneral, $lastIdInventariosDetalles);
                        }
                    }
                }

                $obj_dao_base->commit();
                utilidades::set_response(array("msj" => "El proceso termino correctamente"));
            } catch (\Exception $ex) {
                $obj_dao_base->rollback();
                new ExceptionHandler($ex->getMessage());
            }
        }

        private function setInsertInventariosPrincipal($idProductosDatosBasicos, $cantidad = null) {
            try {

                $obj_dao_inventarios_principal = new dao_inventarios_principal();
                $obj_dao_inventarios_principal->_vo->set_productos_datos_basicos_id($idProductosDatosBasicos);
                $obj_dao_inventarios_principal->_vo->set_cantidad($cantidad);
                $obj_dao_inventarios_principal->insert_rows();
                return $obj_dao_inventarios_principal->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        private function setInsertInventariosPrincipalDetalles($inventariosPrincipalId, $inventariosBodegasId, $cantidad) {
            try {
                $obj_dao_inventarios_principal_detalles = new dao_inventarios_principal_detalles();
                $obj_dao_inventarios_principal_detalles->_vo->set_inventarios_principal_id($inventariosPrincipalId);
                $obj_dao_inventarios_principal_detalles->_vo->set_inventarios_bodegas_id($inventariosBodegasId);
                $obj_dao_inventarios_principal_detalles->insert_rows();
                return $obj_dao_inventarios_principal_detalles->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        private function setInsertInventariosPrincipalEntradasGeneral() {
            try {
                $obj_dao_inventarios_principal_entradas_general = new dao_inventarios_principal_entradas_general();
                $obj_dao_inventarios_principal_entradas_general->_vo->set_archivo_pdf("null");
                $obj_dao_inventarios_principal_entradas_general->insert_rows();
                return $obj_dao_inventarios_principal_entradas_general->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        private function setInsertInventariosPrincipalEntradas($lastIdEntradasGeneral, $lastIdInventariosDetalles) {
            try {
                $obj_dao_inventarios_principal_entradas = new dao_inventarios_principal_entradas();
                $obj_dao_inventarios_principal_entradas->_vo->set_inventarios_principal_entradas_general_id($lastIdEntradasGeneral);
                $obj_dao_inventarios_principal_entradas->_vo->set_inventarios_principal_detalles_id($lastIdInventariosDetalles);
                $obj_dao_inventarios_principal_entradas->insert_rows();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        private function setUpdateInventariosPrincipal($id, $cantidad,$oper="+") {
            try {
                $objVo = $obj_dao_inventarios_principal = new dao_inventarios_principal($id);
                $obj_dao_inventarios_principal = new dao_inventarios_principal();
                $obj_dao_inventarios_principal->_vo->set_id($id);
                if($oper=="+"){
                    $obj_dao_inventarios_principal->_vo->set_cantidad($cantidad + $objVo->_vo->get_cantidad());
                }else{
                    $obj_dao_inventarios_principal->_vo->set_cantidad($objVo->_vo->get_cantidad() - $cantidad);
                }
                $obj_dao_inventarios_principal->update_rows();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        public function getInventariosDetalles() {
            try {
                $arr_response = new \stdClass();
                $obj_dao_inventarios_principal_detalles = new dao_inventarios_principal_detalles();
                if (request::get_parameter("idInventariosPrincipal") == null || request::get_parameter("idInventariosPrincipal") == "") {
                    throw new \Exception("Ocurrio un problema con el codigo, puede volver a intentar seleccionandolo de nuevo");
                }
                $obj_dao_inventarios_principal_detalles->_vo->set_inventarios_principal_id(request::get_parameter("idInventariosPrincipal"));
                $arrObjVo = $obj_dao_inventarios_principal_detalles->select_rows()->fetch_object_vo();
                $objStdClass = $this->getParametrosGrid($arrObjVo);

                for ($i = 0; $i < count($arrObjVo); $i++) {
                    $obj_dao_inventarios_principal_detalles->_vo = $arrObjVo[$i];
                    //
                    $objStdClass->rows[$i]['id'] = $obj_dao_inventarios_principal_detalles->_vo->get_id();
                    $objStdClass->rows[$i]['Bodega'] = $this->getInventarioBodega($obj_dao_inventarios_principal_detalles->_vo->get_inventarios_bodegas_id());
                    $objStdClass->rows[$i]['Creacion'] = substr($obj_dao_inventarios_principal_detalles->_vo->get_fecha_creacion(), 0, 10);

                }

                return utilidades::set_response($objStdClass);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        private function getInventarioBodega($idBodega) {
            try {
                $objVo = $obj_dao_inventarios_bodegas = new dao_inventarios_bodegas($idBodega);
                return $objVo->_vo->get_nombre();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        private function getParametrosGrid($arrDatos) {
            try {
                $this->set_filters_grilla_basPrivate($page, $limit, $sidx, $sord);
                /* get the direction */
                if (!$sidx) {
                    $sidx = 1;
                }
                $count = count($arrDatos);
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                }
                if ($page > $total_pages)
                    $page = $total_pages;
                $start = $limit * $page - $limit;
                if ($start < 0) {
                    $start = 0;
                }
                /* do not put $limit*($page - 1) */
                $responce = new \stdClass();
                $responce->page = $page;
                $responce->total = $total_pages;
                $responce->records = $count;

                return $responce;
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * Se encarga de descar del inventario los items vendios en facturacion
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 23-12-2014
         * @param null
         * @return boolean
         */
        public function gestion_inventarios_facturacion($arrParametros=array()){
            try {
                /**cremos el registro en salidas general*/
                $ultIdSalGeneral = $this->setInsertInventariosPrincipalSalidasGeneral((object)array(
                    'facturas_datos_basicos_id'=>$arrParametros->facturas_datos_basicos_id
                ));
                
                /**recorremos los items a decontar del inventario**/
                for($i=0;$i<(int)request::get_parameter('hdnContRowsDetalle');$i++){
                    if(!request::get_parameter('txtReferenciaDetalle')==null){
                        continue;
                    }
                    
                    /**descontamos o ingresamos por primera vez el registro en inventarios principal**/
                    /****consultamos el inventario del producto************/
                    request::set_request("productos_datos_basicos_id",request::get_parameter("hdn_txtReferenciaDetalle-".$i));
                    $arrDatosInvPrin = $this->get_inventarios_principal();
                    if($arrDatosInvPrin==null){
                        /**creamos el registro d 1 vez d este producto**/
                        $ultIdInvPrin = $this->setInsertInventariosPrincipal(request::get_parameter("hdn_txtReferenciaDetalle-".$i),0);
                    }else{
                        $ultIdInvPrin = $arrDatosInvPrin[0]->get_id();
                        /**cambiamos d estado el detalle del inventario**/
                        for($j=0;$j<(int)request::get_parameter("txtCantidad-".$i);$j++){
                            /**sacamos el detalle para cambiarlo de estado**/
                            $arrDInvDetalle = $this->getInventariosDetalleByParameter((object)array(
                                'estado'=>'A',
                                'inventarios_principal_id'=>$ultIdInvPrin
                            ),'id','ASC',0,1);
                            $_SESSION['querys_cache']['sql'] = array();
                            $_SESSION['querys_cache']['result'] = array();
                            if($arrDInvDetalle!=null){
                                $this->setUpdateInventariosPrincipalDetalles((object)array(
                                    'estado'=>'S',
                                    'id'=>$arrDInvDetalle[0]->get_id()
                                ));

                                /**insertamos en salidas de detalles**/
                                $this->setInsertInventariosPrincipalSalidas((object)array(
                                    'inventarios_principal_salidas_general_id'=>$ultIdSalGeneral,
                                    'inventarios_principal_detalles_id'=>$arrDInvDetalle[0]->get_id()
                                ));
                            }
                        }
                    }
                    /**actualizamos el registro de este producto**/
                    $this->setUpdateInventariosPrincipal($ultIdInvPrin,request::get_parameter("txtCantidad-".$i),"-");
                }
                
                return true;
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * inserta en inventarios principal salidas general
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 23-12-2014
         * @param array $arrParametros
         * @return int
         */
        private function setInsertInventariosPrincipalSalidasGeneral($arrParametros=array()){
            try {
                /**cremos el registro en salidas general*/
                $objDaoInventariosPrincipalSalidasGeneral = new dao_inventarios_principal_salidas_general();
                
                $objDaoInventariosPrincipalSalidasGeneral->_vo->set_facturas_datos_basicos_id($arrParametros->facturas_datos_basicos_id);
                $objDaoInventariosPrincipalSalidasGeneral->insert_rows();
                
                return $objDaoInventariosPrincipalSalidasGeneral->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * actualiza la table inventarios principal detalles
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 23-12-2014
         * @param array $arrParametros
         * @return boolean
         */
        private function setUpdateInventariosPrincipalDetalles($arrParametros = array()){
            try {
                $objDaoInventariosPrincipalDetalles = new dao_inventarios_principal_detalles();
                
                $objDaoInventariosPrincipalDetalles->_vo->set_id($arrParametros->id);
                $objDaoInventariosPrincipalDetalles->_vo->set_estado($arrParametros->estado);
                
                $objDaoInventariosPrincipalDetalles->update_rows();
                
                return true;
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * consulta la tabla inventarios principal detalles por parametros
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.comn>
         * @date 23-12-2014
         * @param array $arrParametros
         * @return json
         */
        private function getInventariosDetalleByParameter($arrParametros = array(),$sidx = null, $sord = null, $start = null, $limit = null){
            try {
                $objDaoInventariosPrincipalDetalles = new dao_inventarios_principal_detalles();
                if(isset($arrParametros->inventarios_principal_id) && $arrParametros->inventarios_principal_id!=""){
                    $objDaoInventariosPrincipalDetalles->_vo->set_inventarios_principal_id($arrParametros->inventarios_principal_id);
                }
                if(isset($arrParametros->estado) && $arrParametros->estado!=""){
                     $objDaoInventariosPrincipalDetalles->_vo->set_estado($arrParametros->estado);
                }
                
                return $objDaoInventariosPrincipalDetalles->select_rows($sidx,$sord,$start,$limit)->fetch_object_vo();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * inserta en la tabla inventarios principal salidas
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 23-12-2014
         * @param array $arrParametros
         * @return int
         */
        private function setInsertInventariosPrincipalSalidas($arrParametros=array()){
            try {
                $objDaoInventariosPrincipalSalidas = new dao_inventarios_principal_salidas();
                
                $objDaoInventariosPrincipalSalidas->_vo->set_inventarios_principal_salidas_general_id($arrParametros->inventarios_principal_salidas_general_id);
                $objDaoInventariosPrincipalSalidas->_vo->set_inventarios_principal_detalles_id($arrParametros->inventarios_principal_detalles_id);
                
                $objDaoInventariosPrincipalSalidas->insert_rows();
                
                return $objDaoInventariosPrincipalSalidas->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
    }

}