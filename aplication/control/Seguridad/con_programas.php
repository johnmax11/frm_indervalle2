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
    /***/
    class con_programas{
        public function __construct() {}
        /**/
        public function searchrowsEvent(){
            try{
                $objFacProgramas = new fac_programas();
                $objFacProgramas->searchrowsPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        /***/
        public function addeditrowsEvent(){
            try{
                $objFacProgramas = new fac_programas();
                $objFacProgramas->addeditrowsPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        /**/
        public function delete_rowsEvent(){
            try{
                $objFacProgramas = new fac_programas();
                $objFacProgramas->deleterowsPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        /**/
        public function searchdatosrowsEvent(){
            try{
                $objFacProgramas = new fac_programas();
                $objFacProgramas->searchdatosrowsPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        /***************************************************************************/
        /***/
        public function search_modulosEvent(){
            try{
                $objFacProgramas = new fac_programas();
                $objFacProgramas->searchmodulosPublic();
            }catch(\Exception $e){
                return false;
            }   
        }
    }
}