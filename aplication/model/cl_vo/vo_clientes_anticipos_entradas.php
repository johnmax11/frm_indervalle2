<?php
namespace MiProyecto{ 
    /* * To change this template, choose Tools | Templates * and open the template in the editor. */
    class vo_clientes_anticipos_entradas{    	
        private $_id;		
        private $_clientes_anticipos_id;
        private $_abonos_facturas_id;
        private $_clientes_referidos_id;
        private $_valor_entrada;
        private $_valor_usado;
        private $_estado;
        private $_origen_entrada;
        private $_creado_por;
        private $_fecha_creacion;
        private $_modificado_por;
        private $_fecha_modificacion;
        private $_standar_;	

        /* set */    
        public function set_id($id){ 		
                $this->_id = $id;    	
        }		
        public function set_clientes_anticipos_id($clientes_anticipos_id){        		
                $this->_clientes_anticipos_id = $clientes_anticipos_id;    	
        }
        public function set_abonos_facturas_id($abonos_facturas_id){        		
                $this->_abonos_facturas_id = $abonos_facturas_id;    	
        }
        public function set_clientes_referidos_id($clientes_referidos_id){        		
                $this->_clientes_referidos_id = $clientes_referidos_id;    	
        }
        public function set_valor_entrada($valor_entrada){        		
                $this->_valor_entrada = $valor_entrada;    	
        }
        public function set_valor_usado($valor_usado){        		
                $this->_valor_usado = $valor_usado;    	
        }
        public function set_estado($estado){        		
                $this->_estado = $estado;    	
        }
        public function set_origen_entrada($origen_entrada){        		
                $this->_origen_entrada = $origen_entrada;    	
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
        public function get_clientes_anticipos_id(){        		
                return $this->_clientes_anticipos_id;    	
        }
        public function get_abonos_facturas_id(){        		
                return $this->_abonos_facturas_id;    	
        }
        public function get_clientes_referidos_id(){        		
            return $this->_clientes_referidos_id;    	
        }
        public function get_valor_entrada(){        		
                return $this->_valor_entrada;    	
        }
        public function get_valor_usado(){        		
                return $this->_valor_usado;    	
        }
        public function get_estado(){        		
                return $this->_estado;    	
        }
        public function get_origen_entrada(){        		
                return $this->_origen_entrada;    	
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