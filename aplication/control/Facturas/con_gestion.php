<?php
namespace MiProyecto {
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'] . '/aplication/views/Utils/utiVerificaInicioSession.php');

    /**
     * @author: John Jairo Cortes Garcia - johnjairo1984@gmail.com
     * @version: 3.0.0
     * Fecha - 29-11-2014
     */
    /**/
    class con_gestion {
        public function __construct() {}

        /**
         * trae las facturas creadas en estado activo
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @param void
         * @return boolean
         */
        public function select_all_facturasEvent() {
            try {
                $objFacturasDatos = new fac_facturas_datos();
                $objFacturasDatos->voidTodasFacturas();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        
        /**
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date_c 07-12-2014
         * @param void
         * @return boolean
         */
        public function create_documento_ventaEvent(){
            try {
                $objFacturasDatos = new fac_facturas_datos();
                $objFacturasDatos->voidCrearDocumentoVenta();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        
        /**
         * busca los detalles de una factura por id de factura
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 23-12-2014
         * @param null
         * @return boolean
         */
        public function search_detalles_facturaEvent(){
            try {
                $objFacturasDatos = new fac_facturas_datos();
                $objFacturasDatos->voidSearchDetallesFactura();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
    }
}
