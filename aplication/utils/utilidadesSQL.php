<?php
namespace MiProyecto{
	ini_set('memory_limit','256M');
	/*
	 * To change this template, choose Tools | Templates
	 * and open the template in the editor.
	 */

	/**
	 * Description of utilidades
	 *
	 * @author John Jairo Cortes Garcia - johnjairo1984@gmail.com
	 * @version: 1.0.0
	 * Fecha - 24-03-2012
	 */
	class utilidadesSQL{
            //put your code here
            static function get_value_field_dao($id_f,$obj_dao,$field_return='nombre',$field_c='id',$obj_vo='unique'){
                $obj_dao->new_vo();
                /***/
                eval('$obj_dao->_vo->set_'.$field_c.'($id_f);');
                $arrDatos = $obj_dao->select_rows($obj_dao->_vo)->fetch_object_vo();
                /***/
                $vr_r = null;
                if($arrDatos!=null){
                    $obj_dao->new_vo();
                    $obj_dao->_vo = $arrDatos[0];
                    if($obj_vo=='unique'){
                        /***/
                        eval('$vr_r = $obj_dao->_vo->get_'.$field_return.'();');
                    }else{
                        return $obj_dao->_vo;
                    }
                }
                return $vr_r;
            }
	}
}
