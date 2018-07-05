<?php
namespace MiProyecto {

    class fac_abonos_facturas {

        function __construct() {
            
        }

        /**
         * insert en abonos facturas
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 07-12-2014
         * @param array $arrParametros
         * @return int
         */
        public function setInsertAbonosFacturas($arrParametros = array()) {
            try {
                $objAbonosFacturas = new dao_abonos_facturas();
                $objAbonosFacturas->_vo->set_valor_pagado(str_replace(',', '', $arrParametros->valor_pagado));
                $objAbonosFacturas->_vo->set_valor_efectivo(str_replace(',', '', $arrParametros->valor_efectivo));

                if (isset($arrParametros->recibo_caja) && $arrParametros->recibo_caja != '') {
                    $objAbonosFacturas->_vo->set_recibo_caja($arrParametros->recibo_caja);
                }
                $objAbonosFacturas->_vo->set_fecha_pago($arrParametros->fecha_pago);
                if (isset($arrParametros->archivo_pdf) && $arrParametros->archivo_pdf != '') {
                    $objAbonosFacturas->_vo->set_archivo_pdf($arrParametros->archivo_pdf);
                }
                $objAbonosFacturas->_vo->set_tipo($arrParametros->tipo);

                $objAbonosFacturas->insert_rows();

                return $objAbonosFacturas->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * inserta en la tabla abonos facturas pagos
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 07-12-2014
         * @param array $arrParametros
         * @return int
         */
        public function setInsertAbonosFacturasPagos($arrParametros = array()) {
            try {
                $objDaoAbonosFacturasPagos = new dao_abonos_facturas_pagos();
                $objDaoAbonosFacturasPagos->_vo->set_pagos_facturas_principal_id($arrParametros->pagos_facturas_principal_id);
                $objDaoAbonosFacturasPagos->_vo->set_abonos_facturas_id($arrParametros->abonos_facturas_id);

                $objDaoAbonosFacturasPagos->insert_rows();

                return $objDaoAbonosFacturasPagos->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }

        /**
         * 
         * @param type $arrParametros
         * @return type
         */
        public function getAbonosFacturasPagos($arrParametros = array()) {
            try {
                $objDaoAbonosFacturasPagos = new dao_abonos_facturas_pagos();
                if (isset($arrParametros->pagos_facturas_principal_id) && $arrParametros->pagos_facturas_principal_id != '') {
                    $objDaoAbonosFacturasPagos->_vo->set_pagos_facturas_principal_id($arrParametros->pagos_facturas_principal_id);
                }
                if (isset($arrParametros->abonos_facturas_id) && $arrParametros->abonos_facturas_id != '') {
                    $objDaoAbonosFacturasPagos->_vo->set_abonos_facturas_id($arrParametros->abonos_facturas_id);
                }
                $arrDatos=$objDaoAbonosFacturasPagos->select_rows()->fetch_object_vo();

                return $arrDatos;
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * consulta en la tabla abonos facturas
         * 
         * @author john jairo cortes garcia <johnmax11hotmail.com>
         * @date 21-12-2014
         * @param type $arrParametros
         * @return array obj_vo
         */
        public function getAbonosFacturas($arrParametros = array()){
            try{
                $objDaoAbonosFacturas = new dao_abonos_facturas();
                
                if(isset($arrParametros->fecha_pago_array) && $arrParametros->fecha_pago_array[0]!='' && $arrParametros->fecha_pago_array[1]!=''){
                    $objDaoAbonosFacturas->_vo->set_fecha_pago($arrParametros->fecha_pago_array);
                }
                if(isset($arrParametros->tipo) && $arrParametros->tipo!=""){
                    $objDaoAbonosFacturas->_vo->set_tipo($arrParametros->tipo);
                }
                
                return $objDaoAbonosFacturas->select_rows()->fetch_object_vo();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * consulta los ingresos anuales de dinero
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 21-12-2014
         * @param null
         * @return json
         */
        public function voidSearchIngresosAnuales(){
            try{
                $arrDAbon = $this->getAbonosFacturas((object)array(
                    'fecha_pago_array'=>array(request::get_parameter('anio').'-01-01',request::get_parameter('anio').'-12-31')
                ));
                
                $arr_response = new \stdClass();
                if($arrDAbon!=null){
                    $mes_c = null;$cont_mes = -1;
                    for($i=0;$i<count($arrDAbon);$i++){
                        /**totalizamos por cada mes*/
                        if($mes_c != substr($arrDAbon[$i]->get_fecha_pago(),5,2)){
                            $mes_c = substr($arrDAbon[$i]->get_fecha_pago(),5,2);
                            $cont_mes ++;
                            $arr_response->rows[$cont_mes][utilidades::get_mes_string_spanish($mes_c)] = 0;
                        }
                        $vr_factura = ($arrDAbon[$i]->get_valor_pagado());
                        
                        /***/
                        $arr_response->rows[$cont_mes][utilidades::get_mes_string_spanish($mes_c)] += $vr_factura;
                    }
                }
                utilidades::set_response($arr_response);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * buscar los ingresos de dinero mensual
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 21-12-2014
         * @param null
         * @return json
         */
        public function voidSearchIngresosMensuales(){
            try{
                $arrDMensual = $this->getAbonosFacturas((object)array(
                    'fecha_pago_array'=>array(
                            request::get_parameter('anio').'-'.request::get_parameter('mes').'-01',
                            request::get_parameter('anio').'-'.request::get_parameter('mes').'-31'
                        )
                ));
                $arr_response = new \stdClass();
                $mes_c = null;
                $cont_mes = -1;
                if($arrDMensual!=null){
                    for($i=0;$i<count($arrDMensual);$i++){
                        /**totalizamos por cada mes*/
                        if($mes_c != substr($arrDMensual[$i]->get_fecha_pago(),8,2)){
                            $mes_c = substr($arrDMensual[$i]->get_fecha_pago(),8,2);
                            $cont_mes ++;
                            $arr_response->rows[$cont_mes][$mes_c] = 0;
                        }
                        $vr_factura = ($arrDMensual[$i]->get_valor_pagado());
                        
                        /***/
                        $arr_response->rows[$cont_mes][$mes_c] += $vr_factura;
                    }
                }
                
                /****/
                utilidades::set_response($arr_response);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * consulta los abonos anuales de dinero
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 21-12-2014
         * @param null
         * @return json
         */
        public function voidSearchAbonosAnuales(){
            try{
                $arrDAbon = $this->getAbonosFacturas((object)array(
                    'fecha_pago_array'=>array(request::get_parameter('anio').'-01-01',request::get_parameter('anio').'-12-31'),
                    'tipo'=>'"EXP||IN||PA,AB"'
                ));
                $arr_response = new \stdClass();
                if($arrDAbon!=null){
                    $mes_c = null;$cont_mes = -1;
                    for($i=0;$i<count($arrDAbon);$i++){
                        /**totalizamos por cada mes*/
                        if($mes_c != substr($arrDAbon[$i]->get_fecha_pago(),5,2)){
                            $mes_c = substr($arrDAbon[$i]->get_fecha_pago(),5,2);
                            $cont_mes ++;
                            $arr_response->rows[$cont_mes][utilidades::get_mes_string_spanish($mes_c)] = 0;
                        }
                        $vr_factura = ($arrDAbon[$i]->get_valor_pagado());
                        
                        /***/
                        $arr_response->rows[$cont_mes][utilidades::get_mes_string_spanish($mes_c)] += $vr_factura;
                    }
                }
                utilidades::set_response($arr_response);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * buscar los abonos de dinero mensual
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 21-12-2014
         * @param null
         * @return json
         */
        public function voidSearchAbonosMensuales(){
            try{
                $arrDMensual = $this->getAbonosFacturas((object)array(
                    'fecha_pago_array'=>array(
                            request::get_parameter('anio').'-'.request::get_parameter('mes').'-01',
                            request::get_parameter('anio').'-'.request::get_parameter('mes').'-31'
                        ),
                    'tipo'=>'"EXP||IN||PA,AB"'
                ));
                $arr_response = new \stdClass();
                $mes_c = null;
                $cont_mes = -1;
                if($arrDMensual!=null){
                    for($i=0;$i<count($arrDMensual);$i++){
                        /**totalizamos por cada mes*/
                        if($mes_c != substr($arrDMensual[$i]->get_fecha_pago(),8,2)){
                            $mes_c = substr($arrDMensual[$i]->get_fecha_pago(),8,2);
                            $cont_mes ++;
                            $arr_response->rows[$cont_mes][$mes_c] = 0;
                        }
                        $vr_factura = ($arrDMensual[$i]->get_valor_pagado());
                        
                        /***/
                        $arr_response->rows[$cont_mes][$mes_c] += $vr_factura;
                    }
                }
                
                /****/
                utilidades::set_response($arr_response);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
    }
}