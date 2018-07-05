<?php 
namespace MiProyecto{
    /* * To change this template, choose Tools | Templates * and open the template in the editor. */
    class vo_facturas_datos_basicos{    	
        private $_id;
        private $_clientes_datos_basicos_id;
        private $_numero_factura;
        private $_subtotal;
        private $_descuento;
        private $_iva;
        private $_observaciones;
        private $_tipo;
        private $_estado;    
        private $_archivo_pdf;
        private $_creado_por;    
        private $_fecha_creacion;    
        private $_modificado_por;    
        private $_fecha_modificacion;	
        private $_standar_;	

        /* set */
        public function set_id($id){ 		
                $this->_id = $id;    	
        }
        public function set_clientes_datos_basicos_id($clientes_datos_basicos_id){
                $this->_clientes_datos_basicos_id = $clientes_datos_basicos_id;    	
        }		
        public function set_numero_factura($numero_factura){
                $this->_numero_factura = $numero_factura;    	
        }
        public function set_subtotal($subtotal){        		
                $this->_subtotal = $subtotal;    	
        }
        public function set_descuento($descuento){        		
                $this->_descuento = $descuento;    	
        }
        public function set_iva($iva){        		
                $this->_iva = $iva;    	
        }
        public function set_observaciones($observaciones){        		
                $this->_observaciones = $observaciones;    	
        }
        public function set_tipo($tipo){        		
                $this->_tipo = $tipo;    	
        }
        public function set_estado($estado){
                $this->_estado = $estado;    
        }
        public function set_archivo_pdf($archivo_pdf){
                $this->_archivo_pdf = $archivo_pdf;    
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
        public function get_clientes_datos_basicos_id(){
                return $this->_clientes_datos_basicos_id;    	
        }
        public function get_numero_factura(){
                return $this->_numero_factura;    	
        }
        public function get_subtotal(){        		
                return $this->_subtotal;    	
        }
        public function get_descuento(){
                return $this->_descuento;    	
        }
        public function get_iva(){        		
                return $this->_iva;    	
        }
        public function get_observaciones(){        		
                return $this->_observaciones;    	
        }
        public function get_tipo(){        		
                return $this->_tipo;    	
        }
        public function get_estado(){
                return $this->_estado;    	
        }
        public function get_archivo_pdf(){
                return $this->_archivo_pdf;    
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