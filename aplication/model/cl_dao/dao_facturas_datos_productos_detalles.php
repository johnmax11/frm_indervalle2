<?php
namespace MiProyecto{ 
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/BD/clConexion.php');
    /** * Description of dao_clientes_productos_detalles * 
    * @author John Jairo Cortes Garcia - johnjairo1984@gmail.com 
    * @version: 1.0.0 * Fecha - 24-03-2012 
    */
    class dao_facturas_datos_productos_detalles extends Conexion{    	
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
            /***/
            $this->new_vo();

            if($id!=null){
                /***/
                $this->process_select_rows_by_id($id);
            }
        }

        /**/
        public function new_vo(){
            eval('$this->_vo = new '.__NAMESPACE__.'\vo_'.substr(utilidades::get_str_name_table(__CLASS__),4).';');
        }
        /**/    	
        public function select_rows($sidx=null, $sord=null, $start=null, $limit=null){        		
            $this->_bolResultado = false;        		
            $this->_strSQL = $this->select(
                                utilidades::get_array_cols(__CLASS__),
                                utilidades::get_str_name_table(__CLASS__),
                                utilidades::get_array_assoc($this->_vo,utilidades::get_str_name_table(__CLASS__))
                        );
            /* verificamos las condiciones extras*/        		
            if($sidx!=null && $sord !=null){
                $this->_strSQL .= $this->sql_extra_grilla_order_by($sidx, $sord);        		
            }
            /* verificamos las condiciones extras*/				
            if($limit!=null){				
                $this->_strSQL .= $this->sql_extra_grilla_limit($start, $limit);				
            }		
            try {
                return $this;
            } catch (\Exception $e){            			
                throw new \Exception('Caught exception: ---> '.__METHOD__.' ---> '.  $e->getMessage(). "\n");
            }
        } 
        
        /**
         * 
         * @param type $campos
         * @return \MiProyecto\dao_productos_datos_basicos
         * @throws \Exception
         */
        public function group_by($campos=null){
            try{
                if($campos!=null){
                    /**verificamos si tiene order by y limit*/
                    $pos = strrpos($this->_strSQL,"ORDER");
                    $str_tmp = "";
                    if($pos !== false){
                        $str_tmp = substr($this->_strSQL,$pos);
                        $this->_strSQL = substr($this->_strSQL,0,$pos);
                        
                    }
                    $this->_strSQL .= $this->group_by_sql($campos." ".$str_tmp);
                }
                return $this;
            } catch (Exception $e) {
                throw new \Exception('Caught exception: ---> '.__METHOD__.' ---> '.  $e->getMessage(). "\n");
            }
        }

        /***/
        public function update_rows($vo=null){
            try {
                $_vo = $vo;
                if($vo==null){
                    $_vo = $this->_vo;
                }

                $this->_bolResultado = false;
                $this->_strSQL = $this->update(substr(utilidades::get_str_name_table(__CLASS__),4),utilidades::get_array_assoc($_vo,utilidades::get_str_name_table(__CLASS__)),$_vo->get_id());

                $this->_bolResultado = $this->fnQuery($this->_strSQL);
                if(!$this->_bolResultado){
                    throw new \Exception("<br/>Error: Ejecutando el query ---> ".__METHOD__);
                }
                return $this->_bolResultado;
            } catch (\Exception $e){
                throw new \Exception('Caught exception ---> '.__METHOD__.' ---> '.  $e->getMessage(). "\n");
            }
        }

        /***/
        public function insert_rows($vo=null){
            try {
                $_vo = $vo;
                if($vo==null){
                    $_vo = $this->_vo;
                }

                $this->_bolResultado = false;
                $this->_strSQL = $this->insert(substr(utilidades::get_str_name_table(__CLASS__),4),utilidades::get_array_assoc($_vo,utilidades::get_str_name_table(__CLASS__)));

                $this->_bolResultado = $this->fnQuery($this->_strSQL);
                if(!$this->_bolResultado){
                    throw new \Exception("<br/>Error: Ejecutando el query ---> ".__METHOD__);
                }
                return $this->_bolResultado;
            } catch (\Exception $e){
                throw new \Exception('Caught exception ---> '.__METHOD__.' ---> '.  $e->getMessage(). "\n");
            }
        }

        /**************************************************************************/
        /**************************************************************************/

        /***/
        private function process_select_rows_by_id($id){
            try {
                $this->_id = $id;
                /***/
                $this->_vo->set_id($id);
                $arrDResp = $this->select_rows()->fetch_object_vo();
                $this->_vo = $arrDResp[0];
            } catch (\Exception $e){
                throw new \Exception('Caught exception ---> '.__METHOD__.' ---> '.  $e->getMessage(). "\n");
            }
        }

        /***/
        public function fetch_object_vo(){
            try{
                $this->_arrDatos = null;  
                $this->_arr_res_obj = null;

                $this->_bolResultado = $this->fnQuery($this->_strSQL,'OBJECT',$this->_arrDatos);            			
                if(!$this->_bolResultado){                				
                    throw new \Exception("<br/>Error: Ejecutando el query ---> ".__METHOD__);                				
                    return false;            			
                }            			
                if(count($this->_arrDatos)>0){                				
                    $this->_arr_res_obj = utilidades::set_parsear_objeto_vo_nativo($this->_arrDatos,substr(utilidades::get_str_name_table(__CLASS__),4)); 
                }else{
                    $this->_arr_res_obj = null;            			
                }
                return $this->_arr_res_obj;
            } catch (\Exception $e){
                throw new \Exception('Caught exception: ---> '.__METHOD__.' ---> '.  $e->getMessage(). "\n");
            }
        }

        /***/
        public function fetch_object($tipo='object'){
            try{
                $this->_arrDatos = null;  
                $this->_arr_res_obj = null;
                $this->_bolResultado = $this->fnQuery($this->_strSQL,'OBJECT',$this->_arrDatos);            			
                if(!$this->_bolResultado){                				
                    throw new \Exception("<br/>Error: Ejecutando el query ---> ".__METHOD__);                				
                    return false;            			
                }            			
                if(count($this->_arrDatos)>0){                				
                    $this->_arr_res_obj = utilidades::set_parsear_objeto_vo_nativo($this->_arrDatos,substr(utilidades::get_str_name_table(__CLASS__),4)); 
                }else{
                    $this->_arr_res_obj = null;            			
                }
                
                $arr_r = new \stdClass();
                if(!is_null($this->_arr_res_obj)){
                    $metodos_clase = get_class_methods(__NAMESPACE__.'\vo_'.substr(utilidades::get_str_name_table(__CLASS__),4));
                    /***/
                    for($i=0;$i<count($this->_arr_res_obj);$i++){
                        $this->new_vo();
                        $this->_vo = $this->_arr_res_obj[$i];
                        /**set datos*/
                        $arr_r->rows[$i] = new \stdClass(); 
                        foreach($metodos_clase as $key => $valor){
                            if(substr($valor,0,3)=='get' && !is_null(eval('return $this->_vo->'.$valor.'();'))){
                                eval('$arr_r->rows[$i]->'.substr($valor,4).' = $this->_vo->'.$valor.'();');     
                            }
                        } 
                    }
                }
                return $arr_r;
            } catch (\Exception $e){
                throw new \Exception('Caught exception: ---> '.__METHOD__.' ---> '.  $e->getMessage(). "\n");
            }
        }
    }
}