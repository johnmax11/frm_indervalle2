<?php 
namespace MiProyecto{
    class fac_inventarios{
        public $_request;
        public $_utiSsn;

        function __construct(){}

        /******/
        public function search_rotacion_inventariosPublic(){
            try{
               $this->set_filters_grilla_basPrivate($page,$limit,$sidx,$sord);
                /* get the direction */
                if(!$sidx){ 
                    $sidx =1; 
                }
                /* set valores */
                $obj_dao_inventarios_principal = new dao_inventarios_principal();

                /* set filters */
                if(isset($this->_request['filters'])){
                    utilidades::parsear_filters($obj_dao_inventarios_principal->_vo,$this->_request);
                }
                /* connect to the database */
                /* sacamos los registros en estado activo*/
                $arrDatos = $obj_dao_inventarios_principal->select_rows()->fetch_object_vo();
                $count = count($arrDatos); 
                if( $count >0 ) { 
                    $total_pages = ceil($count/$limit);
                } else {
                    $total_pages = 0;
                } 
                if ($page > $total_pages) 
                    $page=$total_pages; 
                $start = $limit*$page - $limit; 
                if($start<0){
                        $start = 0;
                }
                /* do not put $limit*($page - 1)*/
                $responce = new \stdClass();
                $responce->page = $page; 
                $responce->total = $total_pages; 
                $responce->records = $count;
                /* verificamos campos fk */
                if(utilidades::verifica_campo_fk($sidx)==false){
                    $arrDatos = array();
                    $arrDatos = $obj_dao_inventarios_principal->select_rows($sidx, $sord, $start, $limit)->fetch_object_vo();
                }
                $numRows = count($arrDatos);
                $tot_cant_inv = 0;
                $tot_vendidas = 0;
                $vr_inv = 0;
                for($i=0;$i<$numRows;$i++){
                    $obj_dao_inventarios_principal->_vo = $arrDatos[$i];

                    /***traemos la referencia y descr del producto***/
                    $obj_dao_productos_datos_basicos = new dao_productos_datos_basicos($obj_dao_inventarios_principal->_vo->get_productos_datos_basicos_id());
                    
                    /*****************************************************/
                    /**sacamos la fecha del ultimo ingreso a inventario**/
                    $obj_dao_inventarios_principal_detalles = new dao_inventarios_principal_detalles();
                    $obj_dao_inventarios_principal_detalles->_vo->set_inventarios_principal_id($obj_dao_inventarios_principal->_vo->get_id());
                    /**execute**/
                    $arr_obj_vo_det = $obj_dao_inventarios_principal->select_rows('id','DESC')->fetch_object_vo();
                    $obj_dao_inventarios_principal_detalles->_vo = $arr_obj_vo_det[0];
                    $ult_fecha_ing_inv = substr($obj_dao_inventarios_principal_detalles->_vo->get_fecha_creacion(),0,10);
                    
                    /**************************************************/
                    /**sacamos la cantidad vendida de cada producto****/
                    $obj_facturas_productos_detalles = new dao_facturas_productos_detalles();
                    $obj_facturas_productos_detalles->_vo->set_productos_datos_basicos_id($obj_dao_inventarios_principal->_vo->get_productos_datos_basicos_id());
                    $obj_facturas_productos_detalles->_vo->set_talla($obj_dao_inventarios_principal->_vo->get_talla());
                    /**execute*/
                    $obj_arr_vo_det = $obj_facturas_productos_detalles->select_rows("fecha_creacion","DESC")->fetch_object_vo();
                    $cant_vendidas = count($obj_arr_vo_det);
                    
                    $fecha_ult_creacion = '&nbsp;';
                    if($cant_vendidas>0){
                        $obj_facturas_productos_detalles->_vo = $obj_arr_vo_det[0];
                        $fecha_ult_creacion = substr($obj_facturas_productos_detalles->_vo->get_fecha_creacion(),0,10);
                    }
                    $vr_tot_inv = ($obj_dao_inventarios_principal->_vo->get_cantidad() * $obj_dao_productos_datos_basicos->_vo->get_valor_compra());
                    
                    $tot_cant_inv += $obj_dao_inventarios_principal->_vo->get_cantidad();
                    $tot_vendidas += $cant_vendidas;
                    $vr_inv += $vr_tot_inv;
                    
                    if($cant_vendidas==0){
                        $cant_vendidas = "&nbsp;";
                    }
                    
                    /**/
                    $responce->rows[$i]['id']=$obj_dao_inventarios_principal->_vo->get_id();
                    $responce->rows[$i]['cell']=array(
                        $obj_dao_inventarios_principal->_vo->get_id(),
                        $obj_dao_productos_datos_basicos->_vo->get_referencia(),
                        utf8_encode(ucwords(strtolower($obj_dao_productos_datos_basicos->_vo->get_descripcion()))),
                        $obj_dao_inventarios_principal->_vo->get_talla(),
                        $obj_dao_inventarios_principal->_vo->get_cantidad(),
                        $vr_tot_inv,
                        $ult_fecha_ing_inv,
                        $cant_vendidas,
                        $fecha_ult_creacion,
                        ($fecha_ult_creacion!='&nbsp;'?utilidades::get_diff_date($fecha_ult_creacion,@date('Y-m-d')):'&nbsp;')
                        
                    );
                } /**fin for*/
                $responce->userdata['cant_inv'] = $tot_cant_inv;
                $responce->userdata['vendidas'] = $tot_vendidas;
                $responce->userdata['vr_inv'] = $vr_inv;
                
                /***/
                utilidades::set_response(utilidades::order_matriz($responce,7,"INT","DESC"));
            } catch (\Exception $e) {
                utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
            }
        }
        
        /***/
        private function set_filters_grilla_basPrivate(&$page,&$limit,&$sidx,&$sord){
            $page = (isset($this->_request['page'])?$this->_request['page']:1); 
            /* get the requested page */
            $limit = (isset($this->_request['rows'])?$this->_request['rows']:999999999);
            /* get how many rows we want to have into the grid */
            $sidx = (isset($this->_request['sidx'])?$this->_request['sidx']:'id');
            /* get index row - i.e. user click to sort */
            $sord = (isset($this->_request['sord'])?$this->_request['sord']:'ASC');
        }
        
        /******/
        public function search_detalles_inventarios_by_itemPublic(){
            try{
                $obj_dao_inventarios_principal_detalles = new dao_inventarios_principal_detalles();
                $obj_dao_inventarios_principal_detalles->_vo->set_inventarios_principal_id($this->_request['id_item_inventario']);
                /**execute**/
                $arr_vo_deta = $obj_dao_inventarios_principal_detalles->select_rows()->fetch_object_vo();
                
                $arr_return = new \stdClass();
                if($arr_vo_deta!=null){
                    for($i=0;$i<count($arr_vo_deta);$i++){
                        $obj_dao_inventarios_principal_detalles->_vo = $arr_vo_deta[$i];
                        
                        $arr_return->rows[$i] = new \stdClass();
                        
                        /***bodega**/
                        $obj_dao_inventarios_bodegas = new dao_inventarios_bodegas($obj_dao_inventarios_principal_detalles->_vo->get_inventarios_bodegas_id());
                        
                        $arr_return->rows[$i]->bodega = $obj_dao_inventarios_bodegas->_vo->get_nombre();
                        $arr_return->rows[$i]->fecha_ingreso = $obj_dao_inventarios_principal_detalles->_vo->get_fecha_creacion();
                    }
                }
                
                utilidades::set_response($arr_return);
            } catch (\Exception $e) {
                utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
            }
        }
        
        /****/
        public function search_vendidas_by_itemPublic(){
            try{
                /**sacamos los datos del prodcuto**/
                $obj_dao_inventarios_principal = new dao_inventarios_principal($this->_request['id_item_inventario']);
                
                /**buscamos las factuas del item**/
                $obj_dao_facturas_productos_detalles = new dao_facturas_productos_detalles();
                $obj_dao_facturas_productos_detalles->_vo->set_productos_datos_basicos_id($obj_dao_inventarios_principal->_vo->get_productos_datos_basicos_id());
                /**execute**/
                $arr_obj_vo = $obj_dao_facturas_productos_detalles->select_rows()->fetch_object_vo();
                
                $arr_return = new \stdClass();
                if($arr_obj_vo!=null){
                    for($i=0;$i<count($arr_obj_vo);$i++){
                        $obj_dao_facturas_productos_detalles->_vo = $arr_obj_vo[$i];
                        
                        $arr_return->rows[$i] = new \stdClass();
                        
                        /**consultamos los datos de las facturas**/
                        $obj_dao_facturas_datos_basicos = new dao_facturas_datos_basicos($obj_dao_facturas_productos_detalles->_vo->get_facturas_datos_basicos_id());
                        
                        /**consultamos el cliente***/
                        $obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos($obj_dao_facturas_datos_basicos->_vo->get_clientes_datos_basicos_id());
                        
                        $arr_return->rows[$i]->cliente = utf8_encode(ucwords(strtolower($obj_dao_clientes_datos_basicos->_vo->get_nombres().' '.$obj_dao_clientes_datos_basicos->_vo->get_apellidos())));
                        $arr_return->rows[$i]->numero_factura = $obj_dao_facturas_datos_basicos->_vo->get_numero_factura();
                        $arr_return->rows[$i]->fecha_creacion = substr($obj_dao_facturas_datos_basicos->_vo->get_fecha_creacion(),0,10);
                        
                    }
                }
                
                utilidades::set_response($arr_return);
            } catch (\Exception $e) {
                utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
            }
        }
    }
}