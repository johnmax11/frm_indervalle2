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
                $objGestionProductos = new fac_gestion_productos();
                $objGestionProductos->get_datos_grillaPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /**/
        public function select_categoriasEvent(){
            try{
                $objGestionProductos = new fac_gestion_productos();
                $objGestionProductos->mostrar_categorias_productosPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /***/
        public function select_producto_categoriaEvent(){
            try{
                $objProductosCategorias = new fac_productos_categorias();
                $objProductosCategorias->voidBuscarCategoriaProductosByIdPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /**/
        public function create_rowEvent(){
            try{
                $objGestionProductos = new fac_gestion_productos();
                $objGestionProductos->crear_productoPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /**
         * selecciona un producto por el id del producto
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @return boolean
         */
        public function select_producto_by_idEvent(){
            try{
                $objGestionProductos = new fac_gestion_productos();
                $objGestionProductos->mostrar_producto_by_idPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /**
         * actualiza productos por el id del producto
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @return boolean
         */
        public function update_rowEvent(){
            try{
                $objGestionProductos = new fac_gestion_productos();
                $objGestionProductos->actualizar_producto_by_idPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /**
         * borra un producto por el id del producto
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @return boolean
         */
        public function delete_rowEvent(){
            try{
                $objGestionProductos = new fac_gestion_productos();
                $objGestionProductos->delete_producto_by_idPublic();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /**
         * consulta los productos por cualquier campo de autocomplete
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @return boolean
         */
        public function select_producto_autocomplete_by_fieldEvent(){
            try{
                $objGestionProductos = new fac_gestion_productos();
                $objGestionProductos->voidBuscarProductoAutocompleteByCampo();
            }catch(\Exception $e){
                return false;
            }
        }
        
        /**
         * consulta los consecutivos de los productos agrupando las diferentes
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @param null
         * @return boolean
         */
        public function select_consecutivos_agrupadosEvent(){
            try{
                $objGestionProductos = new fac_gestion_productos();
                $objGestionProductos->voidConsultarConsecutivosAgrupados();
            }catch(\Exception $e){
                return false;
            }
        }
    }
}
