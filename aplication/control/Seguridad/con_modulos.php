<?php
namespace MiProyecto{
    if(!isset($_SESSION)){
        session_start();
    }
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/views/Utils/utiVerificaInicioSession.php');
    /**
     * @author: John Jairo Cortes Garcia - johnjairo1984@gmail.com
     * @version: 1.0.0
     * Fecha - 24-03-2012
     */
    /**/
    class con_modulos{
        public function __construct() {}
        /**/
        public function searchrowsEvent(){
            try{
                $objModulos = new fac_modulos();
                $objModulos->searchrowsPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        /***/
        public function create_rowEvent(){
            try{
                $objModulos = new fac_modulos();
                $objModulos->addeditrowsPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        /**/
        public function delete_rowsEvent(){
            try{
                $objModulos = new fac_modulos();
                $objModulos->deleterowsPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        /**/
        public function searchdatosrowsEvent(){
            try{
                $objModulos = new fac_modulos();
                $objModulos->searchdatosrowsPublic();
            }catch(\Exception $e){
                return false;
            }
        }
    }
}