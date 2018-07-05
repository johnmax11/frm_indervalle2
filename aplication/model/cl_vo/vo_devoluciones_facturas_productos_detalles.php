<?php
namespace MiProyecto{
    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */
    class vo_devoluciones_facturas_productos_detalles{
        private $_id;
        private $_devoluciones_facturas_datos_basicos_id;
        private $_facturas_datos_productos_detalles_id;
        private $_subtotal_producto;
        private $_descuento_producto;
        private $_iva_producto;
        private $_observaciones;
        private $_creado_por;
        private $_fecha_creacion;

        private $_standar_;

        // set //
        public function set_id($id){
            $this->_id = $id;
        }
        public function set_devoluciones_facturas_datos_basicos_id($devoluciones_facturas_datos_basicos_id){
            $this->_devoluciones_facturas_datos_basicos_id = $devoluciones_facturas_datos_basicos_id;
        }
        public function set_facturas_datos_productos_detalles_id($facturas_datos_productos_detalles_id){
            $this->_facturas_datos_productos_detalles_id = $facturas_datos_productos_detalles_id;
        }
        public function set_subtotal_producto($subtotal_producto){
            $this->_subtotal_producto = $subtotal_producto;
        }
        public function set_descuento_producto($descuento_producto){
            $this->_descuento_producto = $descuento_producto;
        }
        public function set_iva_producto($iva_producto){
            $this->_iva_producto = $iva_producto;
        }
        public function set_observaciones($observaciones){
            $this->_observaciones = $observaciones;
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

        // get //
        public function get_id(){
            return $this->_id;
        }
        public function get_devoluciones_facturas_datos_basicos_id(){
            return $this->_devoluciones_facturas_datos_basicos_id;
        }
        public function get_facturas_datos_productos_detalles_id(){
            return $this->_facturas_datos_productos_detalles_id;
        }
        public function get_subtotal_producto(){
            return $this->_subtotal_producto;
        }
        public function get_descuento_producto(){
            return $this->_descuento_producto;
        }
        public function get_iva_producto(){
            return $this->_iva_producto;
        }
        public function get_observaciones(){
            return $this->_observaciones;
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
