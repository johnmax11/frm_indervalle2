<?php 
namespace MiProyecto{
    class fac_clientes{
        public $_request;
        public $_utiSsn;

        function __construct(){}
        
        /******/
        public function search_compras_clientesPublic(){
            try{
                /**buscamos los 20 clientes que mas han comprado***/ 
               $obj_dao_facturas_datos_basicos = new dao_facturas_datos_basicos();
               $obj_dao_facturas_datos_basicos->_vo->set_tipo("F");
               $obj_vo_fac = $obj_dao_facturas_datos_basicos->select_rows()->fetch_object_vo();
               
               $arr_id_cliente = array();
               $arr_valor_c_cliente = array();
               $arr_cantidad_c_cliente = array();
               for($i=0;$i<count($obj_vo_fac);$i++){
                   $obj_dao_facturas_datos_basicos->_vo = $obj_vo_fac[$i];
                   
                   if(!in_array($obj_dao_facturas_datos_basicos->_vo->get_clientes_datos_basicos_id(),$arr_id_cliente)){
                       array_push($arr_id_cliente,$obj_dao_facturas_datos_basicos->_vo->get_clientes_datos_basicos_id());
                       array_push($arr_valor_c_cliente,(
                                    $obj_dao_facturas_datos_basicos->_vo->get_subtotal() +
                                    $obj_dao_facturas_datos_basicos->_vo->get_iva()
                               ));
                       array_push($arr_cantidad_c_cliente,1);
                   }else{
                       $ind = array_search($obj_dao_facturas_datos_basicos->_vo->get_clientes_datos_basicos_id(), $arr_id_cliente);
                       /****/
                       $arr_valor_c_cliente[$ind] += (
                                    $obj_dao_facturas_datos_basicos->_vo->get_subtotal() +
                                    $obj_dao_facturas_datos_basicos->_vo->get_iva()
                               );
                       /****/
                       $arr_cantidad_c_cliente[$ind] ++;
                   }
               }
               
               /**ordenamos los arrays de acuerdo al valor mayor comprado**/
               for($i=0;$i<count($arr_valor_c_cliente);$i++){
                   for($j=$i+1;$j<count($arr_valor_c_cliente);$j++){
                       if($arr_valor_c_cliente[$i] < $arr_valor_c_cliente[$j]){
                           /**ids***/
                           $vr_a = $arr_id_cliente[$i];
                           $arr_id_cliente[$i] = $arr_id_cliente[$j];
                           $arr_id_cliente[$j] = $vr_a;
                           /**valor fac**/
                           $vr_a = $arr_valor_c_cliente[$i];
                           $arr_valor_c_cliente[$i] = $arr_valor_c_cliente[$j];
                           $arr_valor_c_cliente[$j] = $vr_a;
                           /**contt fac**/
                           $vr_a = $arr_cantidad_c_cliente[$i];
                           $arr_cantidad_c_cliente[$i] = $arr_cantidad_c_cliente[$j];
                           $arr_cantidad_c_cliente[$j] = $vr_a;
                       }
                   }
               }
               
               $arr_return = new \stdClass();
               /**recorremos y armamos el array final de return**/
               $tot_vr_c = 0;
               $tot_ca_c = 0;
               for($i=0;$i<20;$i++){
                   
                   /**consultamos la factura mas reciente*/
                   $obj_dao_facturas_datos_basicos = new dao_facturas_datos_basicos();
                   $obj_dao_facturas_datos_basicos->_vo->set_clientes_datos_basicos_id($arr_id_cliente[$i]);
                   $arr_d_last_f = $obj_dao_facturas_datos_basicos->select_rows("id","DESC",0,1)->fetch_object();
                   /***sacamos los datos del cliente**/
                   $obj_dao_clientes_datos_basicos = new dao_clientes_datos_basicos($arr_id_cliente[$i]);
                   
                   $tot_vr_c += ($arr_valor_c_cliente[$i]);
                   $tot_ca_c += ($arr_cantidad_c_cliente[$i]);
                   
                   $arr_return->rows[$i][] = $obj_dao_clientes_datos_basicos->_vo->get_id();
                   $arr_return->rows[$i][] = utf8_encode(ucwords(strtolower($obj_dao_clientes_datos_basicos->_vo->get_nombres().' '.$obj_dao_clientes_datos_basicos->_vo->get_apellidos())));
                   $arr_return->rows[$i][] = ($obj_dao_clientes_datos_basicos->_vo->get_identificacion());
                   $arr_return->rows[$i][] = ($arr_valor_c_cliente[$i]);
                   $arr_return->rows[$i][] = ($arr_cantidad_c_cliente[$i]);
                   $arr_return->rows[$i][] = substr($arr_d_last_f->rows[0]->fecha_creacion,0,10);
               }
               $arr_return->userdata['vr_comprado'] = $tot_vr_c;
               $arr_return->userdata['cant_facturas'] = $tot_ca_c;
               
               utilidades::set_response($arr_return);
            } catch (\Exception $e) {
                utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
            }
        }
        
        /****/
        public function search_detalles_compras_clientesPublic(){
            try{
                $obj_dao_facturas_datos_basicos = new dao_facturas_datos_basicos();
                $obj_dao_facturas_datos_basicos->_vo->set_clientes_datos_basicos_id($this->_request['id_cliente']);
                $obj_dao_facturas_datos_basicos->_vo->set_tipo("F");
                
                $arr_vo_fac_e = $obj_dao_facturas_datos_basicos->select_rows()->fetch_object_vo();
                
                $arr_response = new \stdClass();
                for($i=0;$i<count($arr_vo_fac_e);$i++){
                    $obj_dao_facturas_datos_basicos->_vo = $arr_vo_fac_e[$i];
                    
                    $arr_response->rows[$i] = new \stdClass();
                    
                    $arr_response->rows[$i]->factura = $obj_dao_facturas_datos_basicos->_vo->get_numero_factura();
                    $arr_response->rows[$i]->valor_factura = number_format($obj_dao_facturas_datos_basicos->_vo->get_subtotal()+$obj_dao_facturas_datos_basicos->_vo->get_iva());
                    $arr_response->rows[$i]->fecha_creacion = substr($obj_dao_facturas_datos_basicos->_vo->get_fecha_creacion(),0,10);
                }
                
                utilidades::set_response($arr_response);
            } catch (\Exception $e) {
                utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
            }
        }
    }
}