<?php
namespace MiProyecto{
    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */
    class vo_parametros{
        private $_id;
        private $_bd;
        private $_multioficina;	
        private $_consecutivo_factura;
        private $_creado_por;
        private $_fecha_creacion;
        private $_modificado_por;
        private $_fecha_modificacion;

        private $_standar_;

        // set //
        public function set_id($id){
            $this->_id = $id;
        }
        public function set_bd($bd){
            $this->_bd = $bd;
        }
        public function set_multioficina($multioficina){
            $this->_multioficina = $multioficina;
        }
        public function set_consecutivo_factura($consecutivo_factura){
            $this->_consecutivo_factura = $consecutivo_factura;
        }
        public function set_creado_por($creado_por){
                $this->_creado_por = $creado_por;
        }
        public function set_fecha_creacion($fecha_creacion){
                $this->_fecha_creacion = $fecha_creacion;
        }
        public function set_modificado_por($modificado_por){
                $this->_modificado_por = $modificado_por;
        }
        public function set_fecha_modificacion($fecha_modificacion){
                $this->_fecha_modificacion = $fecha_modificacion;
        }
        /**************************************************************************/
        public function set_standar_($_standar_){
                $this->_standar_ = $_standar_;
        }

        // get //
        public function get_id(){
                return $this->_id;
        }
        public function get_bd(){
                return $this->_bd;
        }
        public function get_multioficina(){
                return $this->_multioficina;
        }
        public function get_consecutivo_factura(){       
                return $this->_consecutivo_factura;
        }
        public function get_creado_por(){
                return $this->_creado_por;
        }
        public function get_fecha_creacion(){
                return $this->_fecha_creacion;
        }
        public function get_modificado_por(){
                return $this->_modificado_por;
        }
        public function get_fecha_modificacion(){
                return $this->_fecha_modificacion;
        }
        /**************************************************************************/
        public function get_standar_(){
                return $this->_standar_;
        }
    }
}