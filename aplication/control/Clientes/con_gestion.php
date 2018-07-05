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
    class con_gestion{
        public function __construct(){}
        /**/
        public function select_rows_grillaEvent(){
            try{
                $objGestionClientes = new fac_gestion_clientes();
                $objGestionClientes->get_datos_grillaPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /**/
        public function search_by_campoEvent(){
            try{
                $objGestionClientes = new fac_gestion_clientes();
                $objGestionClientes->get_datos_clientes_by_nombre_autocompletePublic();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /***/
        public function create_rowEvent(){
            try{
                $objGestionClientes = new fac_gestion_clientes();
                $objGestionClientes->salvar_clientes_datos_nuevoPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /***/
        public function update_rowEvent(){
            try{
                $objGestionClientes = new fac_gestion_clientes();
                $objGestionClientes->actualizar_clientes_datosPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /***/
        public function delete_rowEvent(){
            try{
                $objGestionClientes = new fac_gestion_clientes();
                $objGestionClientes->delete_clientes_datosPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /****/
        public function select_datos_clientes_rowEvent(){
            try{
                $objGestionClientes = new fac_gestion_clientes();
                $objGestionClientes->get_clientes_datos_by_idPublic();
            }catch(\Exception $e){
                return false;
            }
        }
    }
}
