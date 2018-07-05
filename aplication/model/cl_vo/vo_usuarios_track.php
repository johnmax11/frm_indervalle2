<?php
namespace MiProyecto{
	/**
	 * Description of vo_usuarios
	 *
	 * @author John Jairo Cortes Garcia - johnjairo1984@gmail.com
	 * @version: 1.0.0
	 * Fecha - 24-03-2012
	 */
	class vo_usuarios_track {
		//put your code here
		
		private $_id;
		private $_url_track;
		private $_direccion_ip;
		private $_seguridad_programas_id;
		private $_creado_por;
		private $_fecha_creacion;
		
		private $_standar_;
		
		// metodos set
		
	   public function set_id($id){
		   $this->_id = $id;
	   }
	   public function set_url_track($url_track){
		   $this->_url_track = $url_track;
	   }
	   public function set_direccion_ip($direccion_ip){
		   $this->_direccion_ip = $direccion_ip;
	   }
	   public function set_seguridad_programas_id($seguridad_programas_id){
		   $this->_seguridad_programas_id = $seguridad_programas_id;
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
	   
	   // metodos get //
	   public function get_id(){
		   return $this->_id;
	   }
	   public function get_url_track(){
		   return $this->_url_track;
	   }
	   public function get_direccion_ip(){
		   return $this->_direccion_ip;
	   }
	   public function get_seguridad_programas_id(){
		   return $this->_seguridad_programas_id;
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
