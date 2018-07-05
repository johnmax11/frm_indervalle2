<?php
namespace MiProyecto{
    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */
    class vo_inventarios_principal_entradas_general{

        private $_id;
        private $_archivo_pdf;
        private $_fecha_creacion;
        private $_creado_por;

        private $_standar_;

        /* set */
        public function set_id($id){
            $this->_id = $id;
        }

        public function set_archivo_pdf($archivo_pdf){
            $this->_archivo_pdf = $archivo_pdf;
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

        public function get_archivo_pdf(){
            return $this->_archivo_pdf;
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