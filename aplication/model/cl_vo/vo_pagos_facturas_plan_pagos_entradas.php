<?php
namespace MiProyecto{
    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */
    class vo_pagos_facturas_plan_pagos_entradas{
        private $_id;
        private $_pagos_facturas_plan_pagos_id;
        private $_abonos_facturas_id;
        private $_valor;
        private $_fecha_pago;
        private $_creado_por;
        private $_fecha_creacion;

        private $_standar_;

        /* set */
        public function set_id($id){
                $this->_id = $id;
        }
        public function set_pagos_facturas_plan_pagos_id($pagos_facturas_plan_pagos_id){
                $this->_pagos_facturas_plan_pagos_id = $pagos_facturas_plan_pagos_id;
        }

        public function set_abonos_facturas_id($abonos_facturas_id){
                $this->_abonos_facturas_id = $abonos_facturas_id;
        }

        public function set_valor($valor){
                $this->_valor = $valor;
        }

        public function set_fecha_pago($fecha_pago){
                $this->_fecha_pago = $fecha_pago;
        }

        public function set_creado_por($creado_por){
                $this->_creado_por = $creado_por;
        }

        public function set_fecha_creacion($fecha_creacion){
                $this->_fecha_creacion = $fecha_creacion;
        }


        /**************************************************************************/
        public function set_standar_($_standar_){
                $this->_standar_ = $_standar_;
        }

        /* get */
        public function get_id(){
                return $this->_id;
        }

        public function get_pagos_facturas_plan_pagos_id(){
                return $this->_pagos_facturas_plan_pagos_id;
        }

        public function get_abonos_facturas_id(){
                return $this->_abonos_facturas_id;
        }

        public function get_valor(){
                return $this->_valor;
        }

        public function get_fecha_pago(){
                return $this->_fecha_pago;
        }

        public function get_creado_por(){
                return $this->_creado_por;
        }

        public function get_fecha_creacion(){
                return $this->_fecha_creacion;
        }



        /**************************************************************************/
        public function get_standar_(){
                return $this->_standar_;
        }
    }
}