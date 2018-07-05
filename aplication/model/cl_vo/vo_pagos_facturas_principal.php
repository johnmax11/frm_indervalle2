<?php 
namespace MiProyecto{
    /* * To change this template, choose Tools | Templates * and open the template in the editor. */
    class vo_pagos_facturas_principal{
        private $_id;		
        private $_facturas_datos_basicos_id;		
        private $_numero_factura;
        private $_subtotal_generado;
        private $_descuento_generado;
        private $_iva_generado;
        private $_total_pagado;
        private $_estado;
        private $_creado_por;
        private $_fecha_creacion;
        private $_modificado_por;
        private $_fecha_modificacion;

        private $_standar_;	

        /* set */    
        public function set_id($id){ 		
                $this->_id = $id;    	
        }		
        public function set_facturas_datos_basicos_id($facturas_datos_basicos_id){
                $this->_facturas_datos_basicos_id = $facturas_datos_basicos_id;    	
        }		
        public function set_numero_factura($numero_factura){        		
                $this->_numero_factura = $numero_factura;    	
        }	
        public function set_subtotal_generado($subtotal_generado){        		
                $this->_subtotal_generado = $subtotal_generado;    	
        }
        public function set_descuento_generado($descuento_generado){        		
                $this->_descuento_generado = $descuento_generado;    	
        }
        public function set_iva_generado($iva_generado){        		
                $this->_iva_generado = $iva_generado;    	
        }
        public function set_total_pagado($total_pagado){        		
                $this->_total_pagado = $total_pagado;    	
        }
        public function set_estado($estado){        		
                $this->_estado = $estado;    	
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
        public function get_facturas_datos_basicos_id(){
                return $this->_facturas_datos_basicos_id;
        }		
        public function get_numero_factura(){
                return $this->_numero_factura;    	
        }	
        public function get_subtotal_generado(){
                return $this->_subtotal_generado;    	
        }
        public function get_descuento_generado(){
                return $this->_descuento_generado;    	
        }
        public function get_iva_generado(){
                return $this->_iva_generado;    	
        }
        public function get_total_pagado(){        		
                return $this->_total_pagado;    	
        }
        public function get_estado(){      		
                return $this->_estado;
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