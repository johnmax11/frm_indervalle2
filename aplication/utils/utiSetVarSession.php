<?php
namespace MiProyecto{
    if(!isset($_SESSION)){
        session_start();
    }
    /**
     * Description of utisetVarSession
     *
     * @author vanessa
     */
    class utisetVarSession {
        //put your code here

        // set
        public function set_ssn_id_users($ssn_id_users){
            $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_USERS'] = $ssn_id_users;
        }
        public function set_ssn_first_name_users($ssn_first_name_users){
            $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['FIRST_NAME_USERS'] = $ssn_first_name_users;
        }
        public function set_ssn_usuario_users($ssn_usuario_users){
            $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['USUARIO_USERS'] = $ssn_usuario_users;
        }
        public function set_ssn_accesos_rol($ssn_acceso_rol){
            $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ACCESOS_ROL'] = $ssn_acceso_rol;
        }
        public function set_ssn_empresa_id($ssn_empresa_id){
            $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['EMPRESA_ID'] = $ssn_empresa_id;
        }
        public function set_ssn_oficina_id($ssn_oficina_id){
            $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['OFICINA_ID'] = $ssn_oficina_id;
        }
        public function set_ssn_empresa_nombre($ssn_empresa_nombre){
            $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['EMPRESA_NOMBRE'] = $ssn_empresa_nombre;
        }
        public function set_ssn_oficina_nombre($ssn_oficina_nombre){
            $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['OFICINA_NOMBRE'] = $ssn_oficina_nombre;
        }
        public function set_ssn_seguridad_programa_id($ssn_seguridad_programa_id){
            $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['SEGURIDAD_PROGRAMA_ID'] = $ssn_seguridad_programa_id;
        }
        public function set_ssn_estilo($ssn_estilo){
            $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ESTILO'] = $ssn_estilo;
        }
        // get
        static function get_ssn_id_users(){
            return isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_USERS']) ? $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_USERS'] : null;
        }
        static function get_ssn_first_name_users(){
            return isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['FIRST_NAME_USERS']) ? $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['FIRST_NAME_USERS'] : null;
        }
        static function get_ssn_usuario_users(){
            return isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['USUARIO_USERS']) ? $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['USUARIO_USERS'] : null;
        }
        static function get_ssn_accesos_rol(){
            return isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ACCESOS_ROL']) ? $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ACCESOS_ROL'] : null;
        }
        static function get_ssn_empresa_id(){
            return isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['EMPRESA_ID']) ? $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['EMPRESA_ID'] : null;
        }
        static function get_ssn_oficina_id(){
            return isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['OFICINA_ID']) ? $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['OFICINA_ID'] : null;
        }
        static function get_ssn_empresa_nombre(){
            return isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['EMPRESA_NOMBRE']) ? $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['EMPRESA_NOMBRE'] : null;
        }
        static function get_ssn_oficina_nombre(){
            return isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['OFICINA_NOMBRE']) ? $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['OFICINA_NOMBRE'] : null;
        }
        static function get_ssn_seguridad_programa_id(){
            return isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['SEGURIDAD_PROGRAMA_ID']) ? $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['SEGURIDAD_PROGRAMA_ID'] : null;
        }
        static function get_ssn_estilo(){
            return isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ESTILO']) ? $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ESTILO'] : null;
        }
        // unset
        static function unset_ssn_id_users(){
            unset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_USERS']);
        }
        static function unset_ssn_first_name_users(){
            unset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['FIRST_NAME_USERS']);
        }
        static function unset_ssn_usuario_users(){
            unset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['USUARIO_USERS']);
        }
        static function unset_ssn_accesos_rol(){
            unset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ACCESOS_ROL']);
        }
        static function unset_ssn_empresa_id(){
            unset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['EMPRESA_ID']);
        }
        static function unset_ssn_oficina_id(){
            unset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['OFICINA_ID']);
        }
        static function unset_ssn_empresa_nombre(){
            unset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['EMPRESA_NOMBRE']);
        }
        static function unset_ssn_oficina_nombre(){
            unset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['OFICINA_NOMBRE']);
        }
        static function unset_ssn_seguridad_programa_id(){
            unset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['SEGURIDAD_PROGRAMA_ID']);
        }
        static function unset_ssn_estilo(){
            unset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ESTILO']);
        }
    }
}
