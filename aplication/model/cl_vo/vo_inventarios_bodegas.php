<?php
namespace MiProyecto{
    class vo_inventarios_bodegas{
        private $_id;
        private $_nombre;
        private $_fecha_creacion;
        private $_creado_por; 

        private $_standar_;

        /* set */
        public function set_id($id){
                $this->_id = $id;
        }

        public function set_nombre($nombre){
                $this->_nombre = $nombre;
        }

        public function set_fecha_creacion($fecha_creacion){
                $this->_fecha_creacion = $fecha_creacion;
        }

        public function set_creado_por($creado_por){
                $this->_creado_por = $creado_por;
        }

        /**************************************************************************/
        public function set_standar_($_standar_){
                $this->_standar_ = $_standar_;
        }

        /* get */
        public function get_id(){
                return $this->_id;
        }

        public function get_nombre() {
                return $this->_nombre;
        }

        public function get_fecha_creacion(){
                return $this->_fecha_creacion;
        }

        public function get_creado_por(){
                return $this->_creado_por;
        }
        /**************************************************************************/
        public function get_standar_(){
                return $this->_standar_;
        }
    }
}