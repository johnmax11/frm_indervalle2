<?php
namespace MiProyecto{
    /**
     * Description of vo_miscelaneos
     *
     * @author user
     */
    class vo_parametros_miscelaneos {
        private $_id;
        private $_tabla;
        private $_descripcion;
        private $_parametros;

        private $_standar_;

        // set methods //
        public function set_id($id){
                $this->_id = $id;
        }
        public function set_tabla($tabla){
                $this->_tabla = $tabla;
        }
        public function set_descripcion($descripcion){
                $this->_descripcion = $descripcion;
        }
        public function set_parametros($parametros){
                $this->_parametros = $parametros;
        }
        /**************************************************************************/
        public function set_standar_($_standar_){
                $this->_standar_ = $_standar_;
        }

        // get methods //
        public function get_id(){
                return $this->_id;
        }
        public function get_tabla(){
                return $this->_tabla;
        }
        public function get_descripcion(){
                return $this->_descripcion;
        }
        public function get_parametros(){
                return $this->_parametros;
        }
        /**************************************************************************/
        public function get_standar_(){
                return $this->_standar_;
        }
    }
}
