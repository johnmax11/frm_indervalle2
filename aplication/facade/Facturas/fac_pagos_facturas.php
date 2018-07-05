<?php

namespace MiProyecto {

    class fac_pagos_facturas {

        function __construct() {
            
        }

        /**
         * consulta la tabla pagos facturas principal
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @return object array
         * @param string $sidx
         * @param string $sord
         * @param int $start
         * @param int $limit
         * @param array $arrParametros
         */
        public function get_pagos_factura_principal($arrParametros = array(), $sidx = null, $sord = null, $start = null, $limit = null) {
            try {
                $objDaoPagosFacturaPrincipal = new dao_pagos_facturas_principal();

                if (isset($arrParametros->facturas_datos_basicos_id)) {
                    $objDaoPagosFacturaPrincipal->_vo->set_facturas_datos_basicos_id($arrParametros->facturas_datos_basicos_id);
                }

                /*                 * execute* */
                return $objDaoPagosFacturaPrincipal->select_rows($sidx, $sord, $start, $limit)->fetch_object_vo();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * crea la cxc del documento y el plan de pagos
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 07-12-2014
         * @return boolean
         * @param type $arrParametros
         */
        public function setCrearPlanPagos($arrParametros = array()) {
            try {
                /**
                 * insertamos el registro en pagos facturas principal
                 */
                $numLastIdPFP = $this->setInsertPagosFacturasPrincipal((object) array(
                            'facturas_datos_basicos_id' => $arrParametros->facturas_datos_basicos_id,
                            'numero_factura' => $arrParametros->numero_factura,
                            'subtotal_generado' => $arrParametros->subtotal_generado,
                            'descuento_generado' => $arrParametros->descuento_generado,
                            'iva_generado' => $arrParametros->iva_generado,
                            'total_pagado' => $arrParametros->total_pagado,
                            'estado' => ($arrParametros->tipo_proceso == 'PT' ? 'C' : 'P')
                ));

                /**
                 * insertamos el plan de pagos
                 */
                $numCont = 1;
                if ($arrParametros->tipo_proceso == 'AB') {
                    $numCont = 2;
                }
                for ($i = 0; $i < $numCont; $i++) {
                    $chrEstado = '';
                    $strFechaPago = '';
                    $numVrPagado = 0;
                    $numVrAPagar = 0;
                    if ($arrParametros->tipo_proceso == 'PT') {
                        $chrEstado = 'C';
                        $strFechaPago = substr(utilidades::get_current_timestamp(), 0, 10);
                        $numVrPagado = $arrParametros->total_pagado;
                        $numVrAPagar = $arrParametros->total_pagado;
                    } else {
                        if ($arrParametros->tipo_proceso == 'AB') {
                            if ($i == 0) {
                                $chrEstado = 'C';
                                $strFechaPago = substr(utilidades::get_current_timestamp(), 0, 10);
                                $numVrPagado = $arrParametros->total_pagado;
                                $numVrAPagar = str_replace(',', '', $arrParametros->total_pagado);
                            } else {
                                $chrEstado = 'P';
                                $numVrAPagar = (str_replace(',', '', $arrParametros->total_factura) - str_replace(',', '', $arrParametros->total_pagado));
                                $strFechaPago = utilidades::get_date_sum_res_dias(substr(utilidades::get_current_timestamp(), 0, 10), 30);
                            }
                        } else {
                            if ($arrParametros->tipo_proceso == 'SA') {
                                $chrEstado = 'P';
                                $numVrAPagar = $arrParametros->total_factura;
                                $strFechaPago = utilidades::get_date_sum_res_dias(substr(utilidades::get_current_timestamp(), 0, 10), 30);
                            }
                        }
                    }
                    $numLastPlanPagosId = $this->setInsertPlanPagosFacturaPrincipal((object) array(
                                'pagos_facturas_principal_id' => $numLastIdPFP,
                                'estado' => $chrEstado,
                                'fecha_pago' => $strFechaPago,
                                'valor_pagado' => $numVrPagado,
                                'valor_pagar' => $numVrAPagar
                    ));

                    /**
                     * insertamos en entradas de plan d pagos
                     */
                    if ($chrEstado == 'C') {
                        $this->setInsertPlanPagosFacturaEntrada((object) array(
                                    'pagos_facturas_plan_pagos_id' => $numLastPlanPagosId,
                                    'abonos_facturas_id' => $arrParametros->abonos_facturas_id,
                                    'valor' => $numVrPagado,
                                    'fecha_pago' => $strFechaPago
                        ));
                    }
                }

                return $numLastIdPFP;
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * inserta en la tabla pagos facturas principal
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 07-12-2014
         * @param array $arrParametros
         * @return int
         */
        private function setInsertPagosFacturasPrincipal($arrParametros = array()) {
            try {
                $objDaoPagosFacturasPrincipal = new dao_pagos_facturas_principal();
                $objDaoPagosFacturasPrincipal->_vo->set_facturas_datos_basicos_id($arrParametros->facturas_datos_basicos_id);
                $objDaoPagosFacturasPrincipal->_vo->set_numero_factura('FA ' . str_pad($arrParametros->numero_factura, 6, '0', STR_PAD_LEFT));
                $objDaoPagosFacturasPrincipal->_vo->set_subtotal_generado(str_replace(',', '', $arrParametros->subtotal_generado));
                $objDaoPagosFacturasPrincipal->_vo->set_descuento_generado(str_replace(',', '', $arrParametros->descuento_generado));
                $objDaoPagosFacturasPrincipal->_vo->set_iva_generado(str_replace(',', '', $arrParametros->iva_generado));
                $objDaoPagosFacturasPrincipal->_vo->set_total_pagado(str_replace(',', '', $arrParametros->total_pagado));
                $objDaoPagosFacturasPrincipal->_vo->set_estado($arrParametros->estado);

                $objDaoPagosFacturasPrincipal->insert_rows();

                return $objDaoPagosFacturasPrincipal->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * inserta en la tabla plan pagos factura principal y retorna el id generado
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 07-12-2014
         * @return int
         * @param array $arrParametros
         */
        private function setInsertPlanPagosFacturaPrincipal($arrParametros = array()) {
            try {
                $objDaoPagosFacturasPlanPagos = new dao_pagos_facturas_plan_pagos();
                $objDaoPagosFacturasPlanPagos->_vo->set_pagos_facturas_principal_id($arrParametros->pagos_facturas_principal_id);
                $objDaoPagosFacturasPlanPagos->_vo->set_estado($arrParametros->estado);
                $objDaoPagosFacturasPlanPagos->_vo->set_fecha_pago($arrParametros->fecha_pago);
                $objDaoPagosFacturasPlanPagos->_vo->set_valor_pagado(str_replace(',', '', $arrParametros->valor_pagado));
                $objDaoPagosFacturasPlanPagos->_vo->set_valor_pagar(str_replace(',', '', $arrParametros->valor_pagar));

                $objDaoPagosFacturasPlanPagos->insert_rows();

                return $objDaoPagosFacturasPlanPagos->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * inserta en plan pagos entradas
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 07-12-2014
         * @param array $arrParametros
         * @return int
         */
        public function setInsertPlanPagosFacturaEntrada($arrParametros = array()) {
            try {
                $objDaoPagosFacturasPlanPagosEntradas = new dao_pagos_facturas_plan_pagos_entradas();
                $objDaoPagosFacturasPlanPagosEntradas->_vo->set_pagos_facturas_plan_pagos_id($arrParametros->pagos_facturas_plan_pagos_id);
                $objDaoPagosFacturasPlanPagosEntradas->_vo->set_abonos_facturas_id($arrParametros->abonos_facturas_id);
                $objDaoPagosFacturasPlanPagosEntradas->_vo->set_valor(str_replace(',', '', $arrParametros->valor));
                $objDaoPagosFacturasPlanPagosEntradas->_vo->set_fecha_pago($arrParametros->fecha_pago);

                $objDaoPagosFacturasPlanPagosEntradas->insert_rows();

                return $objDaoPagosFacturasPlanPagosEntradas->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * actualiza las columnas de plan de pagos
         * 
         * @author carlos andres ruales acosta <ruales2007@hotmail.com>
         * @date 19-12-2014
         * @param array $arrParametros
         * @return int
         */
        public function setUpdatePagosFacturasPlanPagos($arrParametros = array()) {
            try {
                $objDaoPagosFacturasPlanPagos = new dao_pagos_facturas_plan_pagos();
                $objDaoPagosFacturasPlanPagos->_vo->set_id($arrParametros->id);
                if (isset($arrParametros->pagos_facturas_principal_id) && $arrParametros->pagos_facturas_principal_id != '') {
                    $objDaoPagosFacturasPlanPagos->_vo->set_pagos_facturas_principal_id($arrParametros->pagos_facturas_principal_id);
                }
                if (isset($arrParametros->estado) && $arrParametros->estado != '') {
                    $objDaoPagosFacturasPlanPagos->_vo->set_estado($arrParametros->estado);
                }
                if (isset($arrParametros->fecha_pago) && $arrParametros->fecha_pago != '') {
                    $objDaoPagosFacturasPlanPagos->_vo->set_fecha_pago($arrParametros->fecha_pago);
                }
                if (isset($arrParametros->valor_pagado) && $arrParametros->valor_pagado != '') {
                    $objDaoPagosFacturasPlanPagos->_vo->set_valor_pagado(str_replace(',', '', $arrParametros->valor_pagado));
                }
                if (isset($arrParametros->valor_pagar) && $arrParametros->valor_pagar != '') {
                    $objDaoPagosFacturasPlanPagos->_vo->set_valor_pagar(str_replace(',', '', $arrParametros->valor_pagar));
                }
                $objDaoPagosFacturasPlanPagos->update_rows();

                return $objDaoPagosFacturasPlanPagos->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        /**
         * Metodo encargado de actualizar en pagos facturas principal
         * 
         * @author carlos andres ruales <ruales2007@hotmail.com>
         * @return void
         * @param array $objFacPagosFacturas
         */
        public function setUpdatePagosFacturasPrincipal($arrParametros = array()) {
            try {
                $objDaoPagosFacturasPrincipal = new dao_pagos_facturas_principal();
                $objDaoPagosFacturasPrincipal->_vo->set_id($arrParametros->id);
                if (isset($arrParametros->facturas_datos_basicos_id) && $arrParametros->facturas_datos_basicos_id != '') {
                    $objDaoPagosFacturasPrincipal->_vo->set_facturas_datos_basicos_id($arrParametros->facturas_datos_basicos_id);
                }
                if (isset($arrParametros->numero_factura) && $arrParametros->numero_factura != '') {
                    $objDaoPagosFacturasPrincipal->_vo->set_numero_factura('FA ' . str_pad($arrParametros->numero_factura, 6, '0', STR_PAD_LEFT));
                }
                if (isset($arrParametros->subtotal_generado) && $arrParametros->subtotal_generado != '') {
                    $objDaoPagosFacturasPrincipal->_vo->set_subtotal_generado(str_replace(',', '', $arrParametros->subtotal_generado));
                }
                if (isset($arrParametros->descuento_generado) && $arrParametros->descuento_generado != '') {
                    $objDaoPagosFacturasPrincipal->_vo->set_descuento_generado(str_replace(',', '', $arrParametros->descuento_generado));
                }
                if (isset($arrParametros->iva_generado) && $arrParametros->iva_generado != '') {
                    $objDaoPagosFacturasPrincipal->_vo->set_iva_generado(str_replace(',', '', $arrParametros->iva_generado));
                }
                if (isset($arrParametros->total_pagado) && $arrParametros->total_pagado != '') {
                    $objDaoPagosFacturasPrincipal->_vo->set_total_pagado(str_replace(',', '', $arrParametros->total_pagado));
                }
                if (isset($arrParametros->estado) && $arrParametros->estado != '') {
                    $objDaoPagosFacturasPrincipal->_vo->set_estado($arrParametros->estado);
                }

                $objDaoPagosFacturasPrincipal->update_rows();

                return $objDaoPagosFacturasPrincipal->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        /**
         * Comprueba si se pago la totalidad de la cxc, y si es asi, actualiza el estado
         * 
         * @author carlos andres ruales <ruales2007@hotmail.com>
         * @return boolean
         * @param array
         * @date_modified 25-12-2014
         * @author_modified john jairo cortes garcia <johnmax11@hotmail.com>
         */
        public function getComprobarPagoTotalCuenta($arrParametros = array()) {
            try {
                $obj_dao_pagos_facturas_principal = new dao_pagos_facturas_principal();
                $obj_dao_pagos_facturas_principal->_vo->set_id($arrParametros->id);
                $objVo = $obj_dao_pagos_facturas_principal->select_rows()->fetch_object_vo();

                $total = ($objVo[0]->get_subtotal_generado() + $objVo[0]->get_iva_generado());
                $totalConDescuento = $total;
                if ($totalConDescuento == $arrParametros->total_pagado) {
                    $obj_dao_pagos_facturas_principal->_vo->set_id($arrParametros->id);
                    $obj_dao_pagos_facturas_principal->_vo->set_estado('C');
                    $obj_dao_pagos_facturas_principal->update_rows();
                }
                return true;
            } catch (\Exception $ex) {
                $objDaoBase->rollback();
                new ExceptionHandler($ex->getMessage());
            }
        }
        /**
         * Comprueba si se pago la totalidad de la cuota, si es asi, se cambia de estado
         * 
         * @author carlos andres ruales <ruales2007@hotmail.com>
         * @return void
         * @param array $objFacPagosFacturas
         */
        public function getComprobarCuotaPaga($arrParametros = array()) {
            try {
                $objVo = $this->getPlanPagos((object) array(
                            "id" => $arrParametros->idPagosFacturasPrincipal
                ));
                //print_r($objVo);
                for ($i = 0; $i < count($objVo); $i++) {
                    if ($objVo[$i]->get_valor_pagado() == $objVo[$i]->get_valor_pagar()) {
                        $this->setUpdatePagosFacturasPlanPagos((object) array(
                                    'id' => $arrParametros->id,
                                    'estado' => 'C'
                        ));
                    }
                }
            } catch (\Exception $ex) {
                $objDaoBase->rollback();
                new ExceptionHandler($ex->getMessage());
            }
        }
        /**
         * retorna el plan de pagos, mediante el pagos_facturas_principal_id
         * 
         * @author carlos andres ruales <ruales2007@hotmail.com>
         * @return array $objVo
         * @param array $objFacPagosFacturas
         */
        public function getPlanPagos($arrParametros = array()) {
            try {
                $obj_dao_pagos_facturas_plan_pagos = new dao_pagos_facturas_plan_pagos();
                $obj_dao_pagos_facturas_plan_pagos->_vo->set_pagos_facturas_principal_id($arrParametros->id);
                $objVo = $obj_dao_pagos_facturas_plan_pagos->select_rows()->fetch_object_vo();

                return $objVo;
            } catch (\Exception $ex) {
                $objDaoBase->rollback();
                new ExceptionHandler($ex->getMessage());
            }
        }
    }
}