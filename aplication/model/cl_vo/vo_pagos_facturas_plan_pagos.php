<?php 
namespace MiProyecto{
    /* * To change this template, choose Tools | Templates * and open the template in the editor. */
    class vo_pagos_facturas_plan_pagos{    	
        private $_id;
        private $_pagos_facturas_principal_id;
        private $_estado;   
        private $_fecha_pago; 
        private $_valor_pagado;
        private $_valor_pagar;
        private $_creado_por;
        private $_fecha_creacion;
        private $_modificado_por;
        private $_fecha_modificacion;
        private $_standar_;	

        /* set */
        public function set_id($id){ 		
                $this->_id = $id;    	
        }
        public function set_pagos_facturas_principal_id($pagos_facturas_principal_id){
                $this->_pagos_facturas_principal_id = $pagos_facturas_principal_id;    	
        }
        public function set_estado($estado){
                $this->_estado = $estado;    
        }
        public function set_fecha_pago($fecha_pago){
                $this->_fecha_pago = $fecha_pago;    
        }
        public function set_valor_pagado($valor_pagado){
                $this->_valor_pagado = $valor_pagado;    
        }
        public function set_valor_pagar($valor_pagar){
                $this->_valor_pagar= $valor_pagar;   
        }
        public function set_creado_por($creado_por){
                $this->_creado_por = $creado_por;    	
        }    	
        public function set_fecha_creacion($fecha_creacion){
                $this->_fecha_creacion = $fecha_creacion;    	
        } 
        public function set_modificado_por($modificado_por){
                $this->_modificado_por = $modificado_por;    	
        }    	
        public function set_fecha_modificacion($fecha_modificacion){
                $this->_fecha_modificacion = $fecha_modificacion;    	
        }


        /**************************************************************************/
        public function set_standar_($_standar_){
                $this->_standar_ = $_standar_;    	
        }        	

        /* get */    	
        public function get_id(){ 
                return $this->_id;    	
        }
        public function get_pagos_facturas_principal_id(){
                return $this->_pagos_facturas_principal_id;    	
        }

        public function get_estado(){
                return $this->_estado;    	
        }

        public function get_fecha_pago(){
                return $this->_fecha_pago;
        }

        public function get_valor_pagado(){
                return $this->_valor_pagado;   
        }

        public function get_valor_pagar(){
                return $this->_valor_pagar;   
        }	

        public function get_creado_por(){
                return $this->_creado_por;    	
        }    
        public function get_fecha_creacion(){ 
                return $this->_fecha_creacion;    	
        }
        public function get_modificado_por(){
                return $this->_modificado_por;    	
        }    	
        public function get_fecha_modificacion(){
                return $this->_fecha_modificacion;    	
        }


        /**************************************************************************/
        public function get_standar_(){
            return $this->_standar_;    	
        }
    }
}