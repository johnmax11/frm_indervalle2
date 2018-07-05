<?php
namespace MiProyecto{ 
	/* * To change this template, choose Tools | Templates * and open the template in the editor. */
	class vo_clientes_datos_basicos{    	
		private $_id;		
		private $_identificacion;		
		private $_tipo_identificacion;    	
		private $_nombres;	
		private $_apellidos;	
		private $_fecha_nacimiento;
		private $_estado;
		private $_origen_referido;
		private $_origen_referido_otro;
		private $_origen_referido_cliente_id;
		private $_creado_por;
		private $_fecha_creacion;    
		private $_modificado_por;    
		private $_fecha_modificacion;	
		private $_standar_;	
		
		/* set */    
		public function set_id($id){ 		
			$this->_id = $id;    	
		}		
		public function set_identificacion($identificacion){        		
			$this->_identificacion = $identificacion;
		}
		public function set_tipo_identificacion($tipo_identificacion){        		
			$this->_tipo_identificacion = $tipo_identificacion;    	
		}    	
		public function set_nombres($nombres){        		
			$this->_nombres = $nombres;    	
		}		
		public function set_apellidos($apellidos){        		
			$this->_apellidos = $apellidos;    	
		}		
		public function set_fecha_nacimiento($fecha_nacimiento){        		
			$this->_fecha_nacimiento = $fecha_nacimiento;    	
		}    	
		public function set_estado($estado){        		
			$this->_estado = $estado;    	
		}
		public function set_origen_referido($origen_referido){        		
			$this->_origen_referido = $origen_referido;    	
		}
		public function set_origen_referido_otro($origen_referido_otro){        		
			$this->_origen_referido_otro = $origen_referido_otro;    	
		}
		public function set_origen_referido_cliente_id($origen_referido_cliente_id){        		
			$this->_origen_referido_cliente_id = $origen_referido_cliente_id;    	
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
		public function get_identificacion(){        		
			return $this->_identificacion;    	
		}		
		public function get_tipo_identificacion(){        		
			return $this->_tipo_identificacion;    	
		}    	
		public function get_nombres(){        		
			return $this->_nombres;    	
		}		
		public function get_apellidos(){        		
			return $this->_apellidos;    	
		}			
		public function get_fecha_nacimiento(){        		
			return $this->_fecha_nacimiento ;    	
		}	    	
		public function get_estado(){        		
			return $this->_estado;    	
		}
		public function get_origen_referido(){        		
			return $this->_origen_referido;    	
		}
		public function get_origen_referido_otro(){        		
			return $this->_origen_referido_otro;    	
		}
		public function get_origen_referido_cliente_id(){        		
			return $this->_origen_referido_cliente_id;    	
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