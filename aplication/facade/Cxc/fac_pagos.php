<?php

namespace MiProyecto {

    class fac_pagos {

        function __construct() {
            
        }

        /**
         * Se encarga de armar la matris de datos para la grilla principal  
         * 
         * @author Carlos andres ruales <ruales2007@hotmail.com>
         * @param null
         * @return json
         */
        public function voidTodasFacturasPendientes() {
            try {
                $this->setFiltersGrillaBasPrivate($page, $limit, $sidx, $sord);
                /* get the direction */
                if (!$sidx) {
                    $sidx = 1;
                }
                /** consultamos los datos del cliente* */
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
                $arrDatos = $this->getPagosFacturasPrincipal((object) array(
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

                $arrDatos = array();
                /* verificamos campos fk */
                if (utilidades::verifica_campo_fk($sidx) == false) {
                    $arrDatos = $this->getPagosFacturasPrincipal((object) array(
                                'fecha_array' => array($fecha_ini, $fecha_fin)
                            ), $sidx, $sord, $start, $limit);
                }
                $numRows = count($arrDatos);
                $numTotal = 0;
                $numTotalSaldo = 0;
                $numTotalPagado = 0;
                for ($i = 0; $i < $numRows; $i++) {
                    /**
                     * sacamos el usuario creador
                     */
                    $objVoFacturasDatosBasicos = new dao_facturas_datos_basicos($arrDatos[$i]->get_facturas_datos_basicos_id());
                    $objVoClientesDatosBasicos = new dao_clientes_datos_basicos($objVoFacturasDatosBasicos->_vo->get_clientes_datos_basicos_id());
                    //
                    $objDaoUsuarios = new dao_usuarios($arrDatos[$i]->get_creado_por());
                    $totalFactura = $arrDatos[$i]->get_subtotal_generado() + $arrDatos[$i]->get_iva_generado() - $arrDatos[$i]->get_descuento_generado();
                    $numTotal+=$totalFactura;
                    $totalPagado = $arrDatos[$i]->get_total_pagado();
                    $numTotalPagado+=$totalPagado;
                    $saldo = $totalFactura - $totalPagado;
                    $numTotalSaldo+=$saldo;
                    $responce->rows[$i]['id'] = $arrDatos[$i]->get_id();
                    $responce->rows[$i]['cell'] = array(
                        $arrDatos[$i]->get_id(),
                        ucwords($objVoClientesDatosBasicos->_vo->get_nombres()) . " " . ucwords($objVoClientesDatosBasicos->_vo->get_apellidos()),
                        $objVoClientesDatosBasicos->_vo->get_identificacion(),
                        $arrDatos[$i]->get_numero_factura(),
                        $totalFactura,
                        $totalPagado,
                        $saldo,
                        "",
                        strtoupper($objDaoUsuarios->_vo->get_usuario()),
                        substr($arrDatos[$i]->get_fecha_creacion(), 0, 10)
                    );
                }
                $responce->userdata['total'] = $numTotal;
                $responce->userdata['total_pagado'] = $numTotalPagado;
                $responce->userdata['saldo'] = $numTotalSaldo;
                utilidades::set_response($responce);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * se encarga de inicializar las variables que serviran para la grilla
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @return void
         * @param number $page
         * @param number $limit
         * @param string $sidx
         * @param string $sord
         */
        private function setFiltersGrillaBasPrivate(&$page, &$limit, &$sidx, &$sord) {
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

        /**
         * Se encarga de traer los documentos que tienen saldos pendientes
         * 
         * @author carlos andres ruales <ruales2007@hotmail.com>
         * @return array
         * @param string $sidx
         * @param string $sord
         * @param int $start
         * @param int $limit
         * @param array $arrParametros
         */
        public function getPagosFacturasPrincipal($arrParametros = array(), $sidx = null, $sord = null, $start = null, $limit = null) {
            try {
                $objDaoPagosFacturasPrincipal = new dao_pagos_facturas_principal();

                /*                 * */
                $objDaoPagosFacturasPrincipal->_vo->set_estado('P');

                /* set filters */

                if (isset($arrParametros->fecha_array) && $arrParametros->fecha_array[0] != '' && $arrParametros->fecha_array[1] != '') {
                    $objDaoPagosFacturasPrincipal->_vo->set_fecha_creacion($arrParametros->fecha_array);
                }


                if (request::get_parameter('filters') != null) {
                    utilidades::parsear_filters($objDaoPagosFacturasPrincipal->_vo);
                }

                return $objDaoPagosFacturasPrincipal->select_rows($sidx, $sord, $start, $limit)->fetch_object_vo();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * Metodo principal que llama a otro metodo para traer el plan de pagos
         * 
         * @author carlos andres ruales <ruales2007@hotmail.com>
         * @return json
         * @param void
         */
        public function voidPlanPagos() {
            try {
                if (request::get_parameter("id") == '' || request::get_parameter("id") == null) {
                    throw new \Exception('Ocurrio un problema al traer el plan de pagos');
                }
                $arrResponse = $this->getPlanPagos(request::get_parameter("id"));

                utilidades::set_response(array(
                    $arrResponse
                ));
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * Metodo que arma la estructura del response para el json, ademas de buscar el plan de pagos
         * 
         * @author carlos andres ruales <ruales2007@hotmail.com>
         * @return array
         * @param int $id
         */
        private function getPlanPagos($id) {
            try {
                $arrResponse = null;
                $acumSaldo = 0;
                $objFacPagosFacturas = new \MiProyecto\fac_pagos_facturas();
                $objVo = $objFacPagosFacturas->getPlanPagos((object) array(
                            "id" => $id
                ));
                for ($i = 0; $i < count($objVo); $i++) {
                    $acumSaldo+=$objVo[$i]->get_valor_pagar() - $objVo[$i]->get_valor_pagado();
                    $arrResponse['id'][$i] = $objVo[$i]->get_id();
                    $arrResponse['pagos_facturas_principal_id'][$i] = $objVo[$i]->get_pagos_facturas_principal_id();
                    $arrResponse['estado'][$i] = $objVo[$i]->get_estado();
                    $arrResponse['fecha'][$i] = $objVo[$i]->get_fecha_pago();
                    $arrResponse['valor_pagado'][$i] = number_format($objVo[$i]->get_valor_pagado());
                    $arrResponse['saldo'][$i] = number_format($objVo[$i]->get_valor_pagar() - $objVo[$i]->get_valor_pagado());
                }

                $arrResponse->userdata['footsaldo'] = $acumSaldo;
                //$responce->userdata['total_pagado'] = $numTotalPagado;
                //$responce->userdata['saldo'] = $numTotalSaldo;

                return $arrResponse;
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * Metodo principal que se encarga de realizar todo la logica para el pago de las cuotas
         * 
         * @author carlos andres ruales <ruales2007@hotmail.com>
         * @return json
         * @param void
         */
        public function setPagoCuota() {
            try {
                $acumPagado = 0;
                $objDaoBase = new dao_base();
                $objDaoBase->begin();
                $objFacPagosFacturas = new \MiProyecto\fac_pagos_facturas();
                if (request::get_parameter("contadorCuotas") == '' || request::get_parameter("contadorCuotas") == null) {
                    throw new \Exception('Ocurrio un problema con el indice de cuotas');
                } else {
                    for ($i = 0; $i <= request::get_parameter("contadorCuotas"); $i++) {
                        if (request::get_parameter("hdn_idPlan" . $i) == '' || request::get_parameter("hdn_idPlan" . $i) == null) {
                            throw new \Exception('Ocurrio un problema con uno los indices de pagos');
                        }
                        $objVo = $obj_dao_pagos_facturas_plan_pagos = new dao_pagos_facturas_plan_pagos(request::get_parameter("hdn_idPlan" . $i));
                        //
                        $this->updatePlanPagos($objFacPagosFacturas, $i, $objVo);
                        //
                        $valorPagado = str_replace(",", "", request::get_parameter("txt_pago" . $i));
                        $acumPagado+=$valorPagado;
                        //
                    }
                    //
                    if (request::get_parameter("hdnPagosFacturasPrincipal") == '' || request::get_parameter("hdnPagosFacturasPrincipal") == null) {
                        throw new \Exception('Ocurrio un problema con la clave de pagos principal');
                    }
                    $objVoFacturasPrincipal = $obj_dao_pagos_facturas_plan_pagos = new dao_pagos_facturas_principal(request::get_parameter("hdnPagosFacturasPrincipal"));
                    $this->updatePagosFacturasPrincipal($objFacPagosFacturas, $objVoFacturasPrincipal, request::get_parameter("hdnPagosFacturasPrincipal"), $acumPagado);

                    //
                    $objFacAbonosFacturas = new \MiProyecto\fac_abonos_facturas();
                    //inserta un registro en abonos facturas
                    $numUltIdAboFact = $objFacAbonosFacturas->setInsertAbonosFacturas((object) array(
                                'valor_pagado' => $acumPagado,
                                'valor_efectivo' => $acumPagado,
                                'tipo' => 'AB',
                                'fecha_pago' => substr(utilidades::get_current_timestamp(), 0, 10)
                    ));
                    for ($i = 0; $i <= request::get_parameter("contadorCuotas"); $i++) {
                        $this->insertPagosFacturasEntradas($objFacPagosFacturas, $numUltIdAboFact, $i);
                        $objFacPagosFacturas->getComprobarCuotaPaga((object) array(
                                    "id" => request::get_parameter("hdn_idPlan" . $i),
                                    "idPagosFacturasPrincipal" => request::get_parameter("hdnPagosFacturasPrincipal")
                        ));
                    }
                    //ingresa un registro en abonos facturas pagos
                    $objFacAbonosFacturas->setInsertAbonosFacturasPagos((object) array(
                                'pagos_facturas_principal_id' => request::get_parameter("hdnPagosFacturasPrincipal"),
                                'abonos_facturas_id' => $numUltIdAboFact
                    ));
                }
                $objFacPagosFacturas->getComprobarPagoTotalCuenta((object) array(
                            "id" => request::get_parameter("hdnPagosFacturasPrincipal"),
                            "total_pagado" => $acumPagado + $objVoFacturasPrincipal->_vo->get_total_pagado()
                ));
                $objDaoBase->commit();

                utilidades::set_response(array(
                    "msj" => "El proceso termino correctamente"
                ));
            } catch (\Exception $ex) {
                $objDaoBase->rollback();
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * Metodo encargado de actualizar las cuotas del del cxc
         * 
         * @author carlos andres ruales <ruales2007@hotmail.com>
         * @return void
         * @param array $objFacPagosFacturas
         * @param int $i
         * @param array $objVo
         */
        private function updatePlanPagos($objFacPagosFacturas, $i, $objVo) {
            try {
                if (request::get_parameter("txt_pago" . $i) != '' && request::get_parameter("txt_pago" . $i) != null) {
                    $objFacPagosFacturas->setUpdatePagosFacturasPlanPagos((object) array(
                                'id' => request::get_parameter("hdn_idPlan" . $i),
                                'valor_pagado' => str_replace(",", "", request::get_parameter("txt_pago" . $i)) + $objVo->_vo->get_valor_pagado()
                    ));
                }
            } catch (\Exception $ex) {
                $objDaoBase->rollback();
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * Metodo encargado de actualizar en pagos facturas principal con la sumatorio de el valor de las cuotas pagadas
         * 
         * @author carlos andres ruales <ruales2007@hotmail.com>
         * @return void
         * @param array $objFacPagosFacturas
         * @param array $objVo
         * @param int $idPagosFacturasPrincipal
         * @param int $acumPagado
         */
        private function updatePagosFacturasPrincipal($objFacPagosFacturas, $objVo, $idPagosFacturasPrincipal, $acumPagado) {
            try {
                $objFacPagosFacturas->setUpdatePagosFacturasPrincipal((object) array(
                            'id' => $idPagosFacturasPrincipal,
                            'total_pagado' => $acumPagado + $objVo->_vo->get_total_pagado()
                ));
            } catch (\Exception $ex) {
                $objDaoBase->rollback();
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * Metodo encargado de ingresar en pagos facturas entradas por cada cuota
         * 
         * @author carlos andres ruales <ruales2007@hotmail.com>
         * @return void
         * @param array $objFacPagosFacturas
         * @param int $numUltIdAboFact
         * @param int $i
         */
        private function insertPagosFacturasEntradas($objFacPagosFacturas, $numUltIdAboFact, $i) {
            try {
                if (request::get_parameter("txt_pago" . $i) != '' && request::get_parameter("txt_pago" . $i) != null) {
                    $objVo = $obj_dao_pagos_facturas_plan_pagos = new dao_pagos_facturas_plan_pagos(request::get_parameter("hdn_idPlan" . $i));
                    $objFacPagosFacturas->setInsertPlanPagosFacturaEntrada((object) array(
                                'pagos_facturas_plan_pagos_id' => request::get_parameter("hdn_idPlan" . $i),
                                'abonos_facturas_id' => $numUltIdAboFact,
                                'valor' => request::get_parameter("txt_pago" . $i),
                                'fecha_pago' => utilidades::get_current_timestamp()
                    ));
                }
            } catch (\Exception $ex) {
                $objDaoBase->rollback();
                new ExceptionHandler($ex->getMessage());
            }
        }

    }

}