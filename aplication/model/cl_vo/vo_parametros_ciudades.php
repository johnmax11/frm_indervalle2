<?php 
namespace MiProyecto{
	/* * To change this template, choose Tools | Templates * and open the template in the editor. */
	class vo_parametros_ciudades{    
		private $_ID;    
		private $_Name;    
		private $_CountryCode;    
		private $_District;        
		private $_standar_;        
		
		/* set */    
		public function set_ID($ID){        $this->_ID = $ID;    }    
		public function set_Name($Name){        $this->_Name = $Name;    }    
		public function set_CountryCode($CountryCode){        $this->_CountryCode = $CountryCode;    }    
		public function set_District($District){        $this->_District = $District;    }    
		
		/**************************************************************************/    
		public function set_standar_($_standar_){        $this->_standar_ = $_standar_;    }        
		
		/* get */    
		public function get_ID(){        return $this->_ID;    }    
		public function get_Name(){        return $this->_Name;    }    
		public function get_CountryCode(){        return $this->_CountryCode;    }    
		public function get_District(){        return $this->_District;    }    
		
		/**************************************************************************/    
		public function get_standar_(){        
			return $this->_standar_;    
		}
	}
}