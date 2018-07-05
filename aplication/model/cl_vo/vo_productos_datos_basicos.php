<?php
namespace MiProyecto{
    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */
    class vo_productos_datos_basicos{
        private $_id;
        private $_nombres;
        private $_descripcion;
        private $_imagen;
        private $_valor_compra;
        private $_valor_final;
        private $_productos_categorias_id;
        private $_estado;
        private $_fecha_creacion;
        private $_creado_por;
        private $_fecha_modificacion;
        private $_modificado_por;

        private $_standar_;

        /* set */
        public function set_id($id){
                $this->_id = $id;
        }

        public function set_nombres($nombres){
                $this->_nombres = $nombres;
        }

        public function set_descripcion($descripcion){
                $this->_descripcion = $descripcion;
        }

        public function set_imagen($imagen){
                $this->_imagen = $imagen;
        }

        public function set_valor_compra($valor_compra){
                $this->_valor_compra = $valor_compra;
        }

        public function set_valor_final($valor_final){
                $this->_valor_final = $valor_final;
        }

        public function set_productos_categorias_id($productos_categorias_id){
                $this->_productos_categorias_id = $productos_categorias_id;
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

        public function set_estado($estado){
                $this->_estado = $estado;
        }

        /**************************************************************************/
        public function set_standar_($_standar_){
                $this->_standar_ = $_standar_;
        }

        /* get */
        public function get_id(){
                return $this->_id;
        }

        public function get_nombres(){
                return $this->_nombres;
        }

        public function get_descripcion(){
                return $this->_descripcion;
        }

        public function get_imagen(){
                return $this->_imagen;  
        }

        public function get_valor_compra(){
                return $this->_valor_compra;
        }

        public function get_valor_final(){
                return $this->_valor_final;
        }

        public function get_productos_categorias_id(){
                return $this->_productos_categorias_id;
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

        public function get_estado(){
                return $this->_estado;
        }
        /**************************************************************************/
        public function get_standar_(){
                return $this->_standar_;
        }
    }
}