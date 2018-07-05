<?php
namespace MiProyecto{
	/*
	 * To change this template, choose Tools | Templates
	 * and open the template in the editor.
	 */
	class vo_seguridad_roles_accesos{
		private $_id;
		private $_visible;
		private $_insertar;
		private $_seleccionar;
		private $_actualizar;
		private $_borrar;
		private $_eventos;
		private $_seguridad_roles_id;
		private $_seguridad_modulos_id;
		private $_seguridad_programas_id;
		private $_creado_por;
		private $_fecha_creacion;
		private $_modificado_por;
		private $_fecha_modificacion;
		
		private $_standar_;
		
		// set //
		public function set_id($id){
			$this->_id = $id;
		}
		public function set_seguridad_roles_id($seguridad_roles_id){
			$this->_seguridad_roles_id = $seguridad_roles_id;
		}
		public function set_seguridad_modulos_id($seguridad_modulos_id){
			$this->_seguridad_modulos_id = $seguridad_modulos_id;
		}
		public function set_seguridad_programas_id($seguridad_programas_id){
			$this->_seguridad_programas_id = $seguridad_programas_id;
		}
		public function set_visible($visible){
			$this->_visible = $visible;
		}
		public function set_insertar($insertar){
			$this->_insertar = $insertar;
		}
		public function set_seleccionar($seleccionar){
			$this->_seleccionar = $seleccionar;
		}
		public function set_actualizar($actualizar){
			$this->_actualizar = $actualizar;
		}
		public function set_borrar($borrar){
			$this->_borrar = $borrar;
		}
		public function set_eventos($eventos){
			$this->_eventos = $eventos;
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
		
		// get //
		public function get_id(){
			return $this->_id;
		}
		public function get_seguridad_roles_id(){
			return $this->_seguridad_roles_id;
		}
		public function get_seguridad_modulos_id(){
			return $this->_seguridad_modulos_id;
		}
		public function get_seguridad_programas_id(){
			return $this->_seguridad_programas_id;
		}
		public function get_visible(){
			return $this->_visible;
		}
		public function get_insertar(){
			return $this->_insertar;
		}
		public function get_seleccionar(){
			return $this->_seleccionar;
		}
		public function get_actualizar(){
			return $this->_actualizar;
		}
		public function get_borrar(){
			return $this->_borrar;
		}
		public function get_eventos(){
			return $this->_eventos;
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
