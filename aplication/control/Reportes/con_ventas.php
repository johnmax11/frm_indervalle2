<?php
namespace MiProyecto {
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'] . '/aplication/views/Utils/utiVerificaInicioSession.php');

    /**
     * @author: John Jairo Cortes Garcia - johnjairo1984@gmail.com
     * @version: 3.0.0
     * Fecha - 20-12-2014
     */
    /**/
    class con_ventas {
        public function __construct() {}

        /**
         * trae los datos de reprotes de ventas anuales
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 20-12-2014
         * @param void
         * @return boolean
         */
        public function search_ventas_anualesEvent() {
            try {
                $objFacturasDatos = new fac_facturas_datos();
                $objFacturasDatos->voidSearchVentasAnuales();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        
        /**
         * trae los datos de reportes mensuales
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 21-12-2014
         * @param void
         * @return boolean
         */
        public function search_ventas_mensualesEvent(){
            try {
                $objFacturasDatos = new fac_facturas_datos();
                $objFacturasDatos->voidSearchVentasMensuales();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        
        /**
         * buscca los ingresos anuales d dinero
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 21-12-2014
         * @param null
         * @return boolean
         */
        public function search_ingresos_anualesEvent(){
            try {
                $objAbonosFacturas = new fac_abonos_facturas();
                $objAbonosFacturas->voidSearchIngresosAnuales();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        
        /**
         * buscca los ingresos mensuales d dinero
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 21-12-2014
         * @param null
         * @return boolean
         */
        public function search_ventas_mensuales_ingresosEvent(){
            try {
                $objAbonosFacturas = new fac_abonos_facturas();
                $objAbonosFacturas->voidSearchIngresosMensuales();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        
        /**
         * consulta los abonos anuales
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 21-12-2014
         * @param null
         * @return boolean
         */
        public function search_separados_anualesEvent(){
            try {
                $objAbonosFacturas = new fac_abonos_facturas();
                $objAbonosFacturas->voidSearchAbonosAnuales();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        
        /**
         * consulta los abonos mensuales
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 21-12-2014
         * @param null
         * @return boolean
         */
        public function search_ventas_mensuales_separadosEvent(){
            try {
                $objAbonosFacturas = new fac_abonos_facturas();
                $objAbonosFacturas->voidSearchAbonosMensuales();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
    }
}
