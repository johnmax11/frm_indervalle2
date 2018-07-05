<?php
namespace MiProyecto{
    /**
     * Description of vo_usuarios
     *
     * @author John Jairo Cortes Garcia - johnjairo1984@gmail.com
     * @version: 1.0.0
     * Fecha - 24-03-2012
     */
    class vo_usuarios {
        //put your code here

        private $_id;
        private $_clave;
        private $_clave_inicial;
        private $_usuario;
        private $_nombre;
        private $_apellido;
        private $_email;
        private $_email_facebook;
        private $_estado;
        private $_estilo;
        private $_seguridad_roles_id;
        private $_ultimo_ingreso;
        private $_creado_por;
        private $_fecha_creacion;
        private $_modificado_por;
        private $_fecha_modificacion;

        private $_standar_;

        // metodos set

       public function set_id($id){
               $this->_id = $id;
       }
       public function set_clave($clave){
               $this->_clave = $clave;
       }
       public function set_clave_inicial($clave_inicial){
               $this->_clave_inicial = $clave_inicial;
       }
       public function set_usuario($usuario){
               $this->_usuario = $usuario;
       }
       public function set_nombre($nombre){
               $this->_nombre = $nombre;
       }
       public function set_apellido($apellido){
               $this->_apellido = $apellido;
       }
       public function set_email($email){
               $this->_email = $email;
       }
       public function set_email_facebook($email_facebook){
               $this->_email_facebook = $email_facebook;
       }
       public function set_estado($estado){
               $this->_estado = $estado;
       }
       public function set_estilo($estilo){
               $this->_estilo = $estilo;
       }
       public function set_seguridad_roles_id($seguridad_roles_id){
               $this->_seguridad_roles_id = $seguridad_roles_id;
       }
       public function set_ultimo_ingreso($ultimo_ingreso){
               $this->_ultimo_ingreso = $ultimo_ingreso;
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

       // metodos get //
       public function get_id(){
            return $this->_id;
       }
       public function get_clave(){
            return $this->_clave;
       }
       public function get_clave_inicial(){
            return $this->_clave_inicial;
       }
       public function get_usuario(){
            return $this->_usuario;
       }
       public function get_nombre(){
            return $this->_nombre;
       }
       public function get_apellido(){
            return $this->_apellido;
       }
       public function get_email(){
            return $this->_email;
       }
       public function get_email_facebook(){
            return $this->_email_facebook;
       }
       public function get_estado(){
            return $this->_estado;
       }
       public function get_estilo(){
            return $this->_estilo;
       }
       public function get_seguridad_roles_id(){
            return $this->_seguridad_roles_id;
       }
       public function get_ultimo_ingreso(){
            return $this->_ultimo_ingreso;
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

