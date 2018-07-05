<?php
namespace MiProyecto{
    /**
     * Description of dao_base
     *
     * @author vanessa-port
     */
    class dao_base extends Conexion{
        /*put your code here*/
        public
            $_bolResultado,
            $_r_obj_mat = true,
            $_arrDatos = array(),
            $_arr_res_obj = array(),
            $_vo;
        private $_strSQL;
        /***/
        function __construct($id=null){
            parent::Conexion();
        }
    }
}