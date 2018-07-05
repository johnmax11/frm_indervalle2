<?php 
namespace MiProyecto{
	/* * To change this template, choose Tools | Templates * and open the template in the editor. */
	class vo_abonos_facturas{
		private $_id;
		private $_valor_pagado;
		private $_valor_efectivo;
                private $_recibo_caja;
		private $_fecha_pago;
		private $_archivo_pdf;
                private $_tipo;
		private $_creado_por;
		private $_fecha_creacion;
		private $_standar_;
		
		/* set */    
		public function set_id($id){ 		
			$this->_id = $id;    	
		}
		public function set_valor_pagado($valor_pagado){        		
			$this->_valor_pagado = $valor_pagado;    	
		}
		public function set_valor_efectivo($valor_efectivo){        		
			$this->_valor_efectivo = $valor_efectivo;    	
		}
		public function set_recibo_caja($recibo_caja){        		
			$this->_recibo_caja = $recibo_caja;    	
		}
		public function set_fecha_pago($fecha_pago){        		
			$this->_fecha_pago = $fecha_pago;    	
		}
		public function set_archivo_pdf($archivo_pdf){        		
			$this->_archivo_pdf = $archivo_pdf;    	
		}
                public function set_tipo($tipo){        		
			$this->_tipo = $tipo;    	
		}
		public function set_creado_por($creado_por){        		
			$this->_creado_por = $creado_por;    	
		}    	
		public function set_fecha_creacion($fecha_creacion){        		
			$this->_fecha_creacion = $fecha_creacion;    	
		}
		/**************************************************************************/    	
		public function set_standar_($_standar_){        		
			$this->_standar_ = $_standar_;    	
		}
		
		/* get */    	
		public function get_id(){        		
			return $this->_id;    	
		}
		public function get_valor_pagado(){        		
			return $this->_valor_pagado;    	
		}
		public function get_valor_efectivo(){        		
			return $this->_valor_efectivo;    	
		}
		public function get_recibo_caja(){        		
			return $this->_recibo_caja; 	
		}
		public function get_fecha_pago(){
			return $this->_fecha_pago;    	
		}
		public function get_archivo_pdf(){        		
			return $this->_archivo_pdf;    	
		}
                public function get_tipo(){        		
			return $this->_tipo;    	
		}
		public function get_creado_por(){        		
			return $this->_creado_por;    	
		}    	
		public function get_fecha_creacion(){        		
			return $this->_fecha_creacion;    	
		}    		
		/**************************************************************************/    	
		public function get_standar_(){        		
			return $this->_standar_;    	
		}
	}
}