<?php
namespace MiProyecto{
	/*
	 * To change this template, choose Tools | Templates
	 * and open the template in the editor.
	 */
	class vo_log_errores_track{
		private $_id;
		private $_log_errores_principal_id;
		private $_fecha_creacion;
		private $_creado_por;
		private $_file;
		private $_line;
		private $_function;
		private $_class;
		private $_object;
		private $_params_object;
		private $_type;
		private $_args;
		private $_sql_text;
		
		private $_standar_;
		
		// set //
		public function set_id($id){
			$this->_id = $id;
		}
		public function set_log_errores_principal_id($log_errores_principal_id){
			$this->_log_errores_principal_id = $log_errores_principal_id;
		}
		public function set_fecha_creacion($fecha_creacion){
			$this->_fecha_creacion = $fecha_creacion;
		}
		public function set_creado_por($creado_por){
			$this->_creado_por = $creado_por;
		}
		public function set_file($file){
			$this->_file = $file;
		}
		public function set_line($line){
			$this->_line = $line;
		}
		public function set_function($function){
			$this->_function = $function;
		}
		public function set_class($class){
			$this->_class = $class;
		}
		public function set_object($object){
			$this->_object = $object;
		}
		public function set_params_object($params_object){
			$this->_params_object = $params_object;
		}
		public function set_type($type){
			$this->_type = $type;
		}
		public function set_args($args){
			$this->_args = $args;
		}
		public function set_sql_text($sql_text){
			$this->_sql_text = $sql_text;
		}
		/**************************************************************************/
		public function set_standar_($_standar_){
			$this->_standar_ = $_standar_;
		}
		
		// get //
		public function get_id(){
			return $this->_id;
		}
		public function get_log_errores_principal_id(){
			return $this->_log_errores_principal_id;
		}
		public function get_fecha_creacion(){
			return $this->_fecha_creacion;
		}
		public function get_creado_por(){
			return $this->_creado_por;
		}
		public function get_file(){
			return $this->_file;
		}
		public function get_line(){
			return $this->_line;
		}
		public function get_function(){
			return $this->_function;
		}
		public function get_class(){
			return $this->_class;
		}
		public function get_object(){
			return $this->_object;
		}
		public function get_params_object(){
			return $this->_params_object;
		}
		public function get_type(){
			return $this->_type;
		}
		public function get_args(){
			return $this->_args;
		}
		public function get_sql_text(){
			return $this->_sql_text;
		}
		/**************************************************************************/
		public function get_standar_(){
			return $this->_standar_;
		}
	}
}
