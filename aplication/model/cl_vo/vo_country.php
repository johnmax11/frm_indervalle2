<?php 
namespace MiProyecto{
	/* * To change this template, choose Tools | Templates * and open the template in the editor. */
	class vo_country{    
		private $_Code;    
		private $_Name;    
		private $_Continent;    
		private $_Region;    
		private $_SurfaceArea;    
		private $_IndepYear;   
		private $_Population;    
		private $_LifeExpectancy;	
		private $_GNP;	
		private $_GNPOld;	
		private $_LocalName;	
		private $_GovernmentForm;	
		private $_HeadOfState;	
		private $_Capital;	
		private $_Code2;        
		private $_standar_;        
		
		/* set */   
		public function set_Code($Code){        $this->_Code = $Code;    }    
		public function set_Name($Name){        $this->_Name = $Name;    }    
		public function set_Continent($Continent){        $this->_Continent = $Continent;    }    
		public function set_Region($Region){        $this->_Region = $Region;    }    
		public function set_SurfaceArea($SurfaceArea){        $this->_SurfaceArea = $SurfaceArea;    }    
		public function set_IndepYear($IndepYear){        $this->_IndepYear = $IndepYear;    }    
		public function set_Population($Population){        $this->_Population = $Population;    }    
		public function set_LifeExpectancy($LifeExpectancy){        $this->_LifeExpectancy = $LifeExpectancy;    }	
		public function set_GNP($GNP){        $this->_GNP = $GNP;    }	
		public function set_GNPOld($GNPOld){        $this->_GNPOld = $GNPOld;    }	
		public function set_LocalName($LocalName){        $this->_LocalName = $LocalName;    }	
		public function set_GovernmentForm($GovernmentForm){        $this->_GovernmentForm = $GovernmentForm;    }	
		public function set_HeadOfState($HeadOfState){        $this->_HeadOfState = $HeadOfState;    }	
		public function set_Capital($Capital){        $this->_Capital = $Capital;    }	
		public function set_Code2($Code2){        $this->_Code2 = $Code2;    }    
		
		/**************************************************************************/    
		public function set_standar_($_standar_){        $this->_standar_ = $_standar_;    }        
		
		/* get */   
		public function get_Code(){        return $this->_Code;    }    
		public function get_Name(){        return $this->_Name;    }    
		public function get_Continent(){        return $this->_Continent;    }    
		public function get_Region(){        return $this->_Region;    }    
		public function get_SurfaceArea(){        return $this->_SurfaceArea;    }    
		public function get_IndepYear(){        return $this->_IndepYear;    }    
		public function get_Population(){        return $this->_Population;    }   
		public function get_LifeExpectancy(){        return $this->_LifeExpectancy;    }	
		public function get_GNP(){        return $this->_GNP;    }	
		public function get_GNPOld(){        return $this->_GNPOld;    }	
		public function get_LocalName(){        return $this->_LocalName;    }	
		public function get_GovernmentForm(){        return $this->_GovernmentForm;    }	
		public function get_HeadOfState(){        return $this->_HeadOfState;    }	
		public function get_Capital(){        return $this->_Capital;    }	
		public function get_Code2(){        return $this->_Code2;    }    
		
		/**************************************************************************/    
		public function get_standar_(){        return $this->_standar_;    }
	}
}