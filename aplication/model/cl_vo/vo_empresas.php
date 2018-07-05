<?php
namespace MiProyecto{
    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */
    class vo_empresas{
        private $_id;
        private $_nombre;
        private $_direccion;
        private $_estado;
        private $_creado_por;
        private $_fecha_creacion;
        private $_modificado_por;
        private $_fecha_modificacion;

        private $_standar_;

        // set //
        public function set_id($id){
                $this->_id = $id;
        }
        public function set_nombre($nombre){
                $this->_nombre = $nombre;
        }
        public function set_direccion($direccion){
                $this->_direccion = $direccion;
        }
        public function set_estado($estado){
                $this->_estado = $estado;
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
        public function get_nombre(){
                return $this->_nombre;
        }
        public function get_direccion(){
                return $this->_direccion;
        }
        public function get_estado(){
                return $this->_estado;
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
