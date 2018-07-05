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

        public function __construct() {
            
        }

        public function select_rowEvent() {
            try {
                $objFacturasDatos = new fac_gestion_tesoreria();
                $objFacturasDatos->searchrowsPublic();
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
        public function select_plan_pagosEvent() {
            try {
                $objFacturasDatos = new fac_gestion_tesoreria();
                $objFacturasDatos->getPlanPagos();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

    }

}
