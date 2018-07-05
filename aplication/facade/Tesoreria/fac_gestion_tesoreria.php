<?php

namespace MiProyecto {

    //require_once ($strPath . '/aplication/utils/utiRequire_once_all.php');

    class fac_gestion_tesoreria {

        public $_request;
        public $_utiSsn;

        function __construct() {
            
        }

        /*
         * action para buscar un producto por su nombre
         */

        public function searchrowsPublic() {
            try {
                $this->set_filters_grilla_basPrivate($page, $limit, $sidx, $sord);
                /* get the direction */
                if (!$sidx) {
                    $sidx = 1;
                }

                $fecha_ini = '';
                $fecha_fin = '';
                /*                 * filtros* */
                switch (request::get_parameter('selTipo_rango')) {
                    case 'M':
                        $fecha_ini = @date('Y') . '-' . request::get_parameter('selMes_filtro_tab1') . '-01';
                        $fecha_fin = @date('Y') . '-' . request::get_parameter('selMes_filtro_tab1') . '-31';
                        break;
                    case 'A':
                        $fecha_ini = request::get_parameter('selAnio_filtro_tab1') . '-01-01';
                        $fecha_fin = request::get_parameter('selAnio_filtro_tab1') . '-12-31';
                        break;
                    case 'R':
                        $fecha_ini = request::get_parameter('txtFechaDe_filtro_tab1');
                        $fecha_fin = request::get_parameter('txtFechaHasta_filtro_tab1');
                        break;
                }

                /** consultamos los datos del cliente* */
                $arrDatos = $this->getAbonosFacturas((object) array(
                            'fecha_array' => array($fecha_ini, $fecha_fin)
                ));
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
                $arrDatos = array();
                /* verificamos campos fk */

                if (utilidades::verifica_campo_fk($sidx) == false) {
                    $arrDatos = $this->getAbonosFacturas((object) array(
                                'fecha_array' => array($fecha_ini, $fecha_fin)
                            ), $sidx, $sord, $start, $limit);
                }

                $numRows = count($arrDatos);
                $numTotal = 0;
                $numTotalSaldo = 0;
                $numTotalPagado = 0;
                $objAbonosFacturasPagos = new \MiProyecto\fac_abonos_facturas();
                for ($i = 0; $i < $numRows; $i++) {
                    //
                    $objVoAbonosFacturasPagos = $objAbonosFacturasPagos->getAbonosFacturasPagos((object) array(
                                "abonos_facturas_id" => $arrDatos[$i]->get_id()
                    ));
                    //
                    $objVoPagosFacturasPrincipal = new dao_pagos_facturas_principal($objVoAbonosFacturasPagos[0]->get_pagos_facturas_principal_id());
                    $objVoFacturasDatosBasicos = new dao_facturas_datos_basicos($objVoPagosFacturasPrincipal->_vo->get_facturas_datos_basicos_id());
                    $objVoClientesDatosBasicos = new dao_clientes_datos_basicos($objVoFacturasDatosBasicos->_vo->get_clientes_datos_basicos_id());

//print_r($objVoPagosFacturasPrincipal);
                    $objDaoUsuarios = new dao_usuarios($arrDatos[$i]->get_creado_por());
                    $responce->rows[$i]['id'] = $arrDatos[$i]->get_id();
                    $numTotal+=(int) $arrDatos[$i]->get_valor_pagado();
                    $responce->rows[$i]['cell'] = array(
                        $arrDatos[$i]->get_id(),
                        "RCA " . str_pad($arrDatos[$i]->get_id(), 6, 0, STR_PAD_LEFT),
                        (int) $arrDatos[$i]->get_valor_pagado(),
                        ucwords($objVoClientesDatosBasicos->_vo->get_nombres()) . " " . ucwords($objVoClientesDatosBasicos->_vo->get_apellidos()),
                        $objVoClientesDatosBasicos->_vo->get_identificacion(),
                        ($arrDatos[$i]->get_tipo() == 'AB') ? ("Abono (" . $objVoPagosFacturasPrincipal->_vo->get_numero_factura() . ")") : ("Pago total (" . $objVoPagosFacturasPrincipal->_vo->get_numero_factura() . ")"),
                        strtoupper($objDaoUsuarios->_vo->get_usuario()),
                        substr($arrDatos[$i]->get_fecha_creacion(), 0, 10),
                        $objVoPagosFacturasPrincipal->_vo->get_id()
                    );
                }
                $responce->userdata['valor_pagado'] = $numTotal;
                utilidades::set_response($responce);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /*         * */

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

        /*         * */

        public function search_detalles_facturaPublic() {
            try {
                $obj_dao_abonos_facturas_pagos = new dao_abonos_facturas_pagos();
                $obj_dao_abonos_facturas_pagos->_vo->set_abonos_facturas_id($this->_request['id_documento']);
                /*                 * execute* */
                $arrDAbo_e = $obj_dao_abonos_facturas_pagos->select_rows()->fetch_object();

                /*                 * *sacamos el id d la factura* */
                $obj_dao_pagos_facturas_principal = new dao_pagos_facturas_principal($arrDAbo_e->rows[0]->pagos_facturas_principal_id);

                /*                 * sacamos el detalle de la factura* */
                $obj_dao_facturas_productos_detalles = new dao_facturas_productos_detalles();
                $obj_dao_facturas_productos_detalles->_vo->set_facturas_datos_basicos_id($obj_dao_pagos_facturas_principal->_vo->get_facturas_datos_basicos_id());
                /*                 * execute* */
                $arrDDeta_e = $obj_dao_facturas_productos_detalles->select_rows()->fetch_object_vo();

                $arr_response = new \stdClass();
                if ($arrDDeta_e != null) {
                    for ($i = 0; $i < count($arrDDeta_e); $i++) {
                        $obj_dao_facturas_productos_detalles->_vo = $arrDDeta_e[$i];

                        $arr_response->rows[$i] = new \stdClass();

                        /*                         * sacamos los datos del producto* */
                        $obj_dao_productos_datos_basicos = new dao_productos_datos_basicos($obj_dao_facturas_productos_detalles->_vo->get_productos_datos_basicos_id());

                        $arr_response->rows[$i]->referencia = $obj_dao_productos_datos_basicos->_vo->get_referencia();
                        $arr_response->rows[$i]->descripcion = $obj_dao_productos_datos_basicos->_vo->get_descripcion();

                        $arr_response->rows[$i]->valor_compra = number_format($obj_dao_facturas_productos_detalles->_vo->get_precio_compra());
                        $arr_response->rows[$i]->valor_venta = number_format($obj_dao_facturas_productos_detalles->_vo->get_subtotal_producto() + $obj_dao_facturas_productos_detalles->_vo->get_iva_producto());

                        $arr_response->rows[$i]->ganancia = number_format(str_replace(',', '', $arr_response->rows[$i]->valor_venta) - str_replace(',', '', $arr_response->rows[$i]->valor_compra));
                    }
                }

                utilidades::set_response($arr_response);
            } catch (\Exception $e) {
                utilidades::set_response(array("msj" => "Error: --->" . __METHOD__ . "--->" . $e->getMessage()), true);
            }
        }

        /**
         * retorna el vo de abonos_facturas
         * 
         * @author carlos andres ruales acosta <ruales2007@hotmail.com>
         * @date 20-12-2014
         * @param void
         * @return array
         */
        public function getAbonosFacturas($arrParametros = array(), $sidx = null, $sord = null, $start = null, $limit = null) {
            try {
                $objDaoAbonosFacturasPagos = new dao_abonos_facturas();

                if (isset($arrParametros->fecha_array) && $arrParametros->fecha_array[0] != '' && $arrParametros->fecha_array[1] != '') {
                    $objDaoAbonosFacturasPagos->_vo->set_fecha_creacion($arrParametros->fecha_array);
                }

                if (request::get_parameter('filters') != null) {
                    utilidades::parsear_filters($objDaoAbonosFacturasPagos->_vo);
                }
                $arrDatos = $objDaoAbonosFacturasPagos->select_rows($sidx, $sord, $start, $limit)->fetch_object_vo();

                return $arrDatos;
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        public function getPlanPagos() {
            try {
                if (request::get_parameter('id') == '' || request::get_parameter('id') == null) {
                    throw new \Exception("Ocurrio un error con el id de la factura");
                }
                $responce = new \stdClass();
                $objPlanPagos = new \MiProyecto\fac_pagos_facturas();
                $arrDatos = $objPlanPagos->getPlanPagos((object) array(
                            "id" => request::get_parameter('id')
                ));

                $acumPagado = 0;

                for ($i = 0; $i < count($arrDatos); $i++) {
                    $acumPagado+=$arrDatos[$i]->get_valor_pagado();
                    $responce->rows[$i]['id'] = $arrDatos[$i]->get_id();
                    $responce->rows[$i]['estado'] = $arrDatos[$i]->get_estado();
                    $responce->rows[$i]['A Pagar'] = number_format($arrDatos[$i]->get_valor_pagar());
                    $responce->rows[$i]['Pagado'] = number_format($arrDatos[$i]->get_valor_pagado());
                    $responce->rows[$i]['Saldo'] = number_format($arrDatos[$i]->get_valor_pagar() - $arrDatos[$i]->get_valor_pagado());
                }

                utilidades::set_response($responce);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

    }

}
