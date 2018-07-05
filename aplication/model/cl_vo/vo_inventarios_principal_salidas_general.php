<?php
namespace MiProyecto{
    class vo_inventarios_principal_salidas_general{

        private $_id;
        private $_facturas_datos_basicos_id;
        private $_creado_por;
        private $_fecha_creacion;

        private $_standar_; 

        /* set */
        public function set_id($id){
                $this->_id = $id;
        }

        public function set_facturas_datos_basicos_id($facturas_datos_basicos_id){
                $this->_facturas_datos_basicos_id = $facturas_datos_basicos_id;
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

        public function get_facturas_datos_basicos_id() {
                return $this->_facturas_datos_basicos_id;
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