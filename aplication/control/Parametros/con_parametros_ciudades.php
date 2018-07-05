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
    class con_parametros_ciudades{
        public function __construct() {}    
        /***/    
        public function buscar_ciudadEvent(){
            try{
                $objGestionParametrosCiudades = new fac_gestion_parametros_ciudades();
                $objGestionParametrosCiudades->buscar_ciudad_atucompletePublic();    
            }catch(Exception $ex){
                return false;
            }
        }
    }
}