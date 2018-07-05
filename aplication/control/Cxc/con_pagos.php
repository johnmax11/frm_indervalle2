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
    class con_pagos {
        public function __construct() {}

        /**
         * Trae las facturas que tienen saldos pendientes
         * 
         * @author Carlos andres ruales <ruales2007@hotmail.com>
         * @param void
         * @return boolean
         */
        public function select_all_facturas_pendientesEvent() {
            try {
                $objFacturasDatos = new fac_pagos();
                $objFacturasDatos->voidTodasFacturasPendientes();
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
        public function select_plan_pagosEvent(){
            try {
                $objFacturasDatos = new fac_pagos();
                $objFacturasDatos->voidPlanPagos();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        
        public function insert_pago_cuotaEvent(){
            try {
                $objFacturasDatos = new fac_pagos();
                $objFacturasDatos->setPagoCuota();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
    }
}
