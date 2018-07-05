<?php
namespace MiProyecto {
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'] . '/aplication/views/Utils/utiVerificaInicioSession.php');

    /**
     * @author: John Jairo Cortes Garcia - johnjairo1984@gmail.com
     * @version: 1.0.0
     * Fecha - 24-03-2012
     */
    /**/
    class con_gestion {

        public function __construct() {
            
        }

        /**/

        public function select_rows_grillaEvent() {
            try {
                $objGestionProductos = new fac_gestion_inventarios();
                $objGestionProductos->get_datos_grillaPublic();
            } catch (\Exception $e) {
                return false;
            }
        }

        /**/

        public function select_categoriasEvent() {
            try {
                $objGestionProductos = new fac_gestion_productos();
                $objGestionProductos->mostrar_categorias_productosPublic();
            } catch (\Exception $e) {
                return false;
            }
        }

        /**/

        public function create_rowEvent() {
            try {
                $objGestionProductos = new fac_gestion_inventarios();
                $objGestionProductos->insertInventarios();
            } catch (\Exception $e) {
                return false;
            }
        }

        /*         * */

        public function select_producto_by_idEvent() {
            try {
                $objGestionProductos = new fac_gestion_productos();
                $objGestionProductos->mostrar_producto_by_idPublic();
            } catch (\Exception $e) {
                return false;
            }
        }

        /**/

        public function update_rowEvent() {
            try {
                $objGestionProductos = new fac_gestion_productos();
                $objGestionProductos->actualizar_producto_by_idPublic();
            } catch (\Exception $e) {
                return false;
            }
        }

        /*         * * */

        public function delete_rowEvent() {
            try {
                $objGestionProductos = new fac_gestion_productos();
                $objGestionProductos->delete_producto_by_idPublic();
            } catch (\Exception $e) {
                return false;
            }
        }

        /*         * */

        public function select_rows_autocompleteEvent() {
            try {
                $objGestionProductos = new fac_gestion_inventarios();
                $objGestionProductos->selectProductoLike();
            } catch (\Exception $e) {
                return false;
            }
        }

//
        public function select_bodegasEvent() {
            try {
                $objGestionProductos = new fac_gestion_inventarios();
                $objGestionProductos->selectBodegas();
            } catch (\Exception $e) {
                return false;
            }
        }

//
        public function select_inventarios_detallesEvent() {
            try {
                $objGestionProductos = new fac_gestion_inventarios();
                $objGestionProductos->getInventariosDetalles();
            } catch (\Exception $e) {
                return false;
            }
        }

    }

}
