<?php 
namespace MiProyecto{
    class fac_facturas_datos{
        function __construct(){}
        
        /**
         * Se encarga de armar la matrix de datos para la grilla principal
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @param null
         * @return json
         */
        public function voidTodasFacturas(){
            try{
                $this->setFiltersGrillaBasPrivate($page, $limit, $sidx, $sord);
                /* get the direction */
                if (!$sidx) {
                    $sidx = 1;
                }
                $fecha_ini='';
                $fecha_fin='';
                /**filtros**/
                switch(request::get_parameter('selTipo_rango')){
                    case 'M':
                        $fecha_ini = @date('Y').'-'.request::get_parameter('selMes_filtro_tab1').'-01';
                        $fecha_fin = @date('Y').'-'.request::get_parameter('selMes_filtro_tab1').'-31';
                        break;
                    case 'A':
                        $fecha_ini = request::get_parameter('selAnio_filtro_tab1').'-01-01';
                        $fecha_fin = request::get_parameter('selAnio_filtro_tab1').'-12-31';
                        break;
                    case 'R':
                        $fecha_ini = request::get_parameter('txtFechaDe_filtro_tab1');
                        $fecha_fin = request::get_parameter('txtFechaHasta_filtro_tab1');
                        break;
                }
                
                /** consultamos los datos del cliente* */
                $arrDatos = $this->getFacturasDatosBasicos((object)array(
                    'fecha_array'=>array($fecha_ini,$fecha_fin)
                ));
                $count = count($arrDatos);
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                }
                if ($page > $total_pages)
                    $page = $total_pages;
                $start = $limit * $page - $limit;
                if ($start < 0) {
                    $start = 0;
                }
                /* do not put $limit*($page - 1) */
                $responce = new \stdClass();
                $responce->page = $page;
                $responce->total = $total_pages;
                $responce->records = $count;
                
                $arrDatos = array();
                /* verificamos campos fk */
                if(utilidades::verifica_campo_fk($sidx)==false){
                    $arrDatos = $this->getFacturasDatosBasicos((object)array(
                            'fecha_array'=>array($fecha_ini,$fecha_fin)
                        ),$sidx,$sord, $start, $limit);
                }
                $numRows = count($arrDatos);
                $numTotal = 0;
                $numTotalSaldo = 0;
                for($i=0;$i<$numRows;$i++){
                    /**
                     * sacamos los datos del cliente
                     */
                    $objDaoClientesDatosBasicos = new dao_clientes_datos_basicos($arrDatos[$i]->get_clientes_datos_basicos_id());
                    /**
                     * sacamos los datos del saldo pendiente por pagar
                     */
                    $objFacPagosFacturas = new \MiProyecto\fac_pagos_facturas();
                    $arrDatosPagos = $objFacPagosFacturas->get_pagos_factura_principal(
                                            (object)array(
                                                'facturas_datos_basicos_id'=>$arrDatos[$i]->get_id()
                                            )
                                        );
                    $numValorSaldo = (($arrDatos[$i]->get_subtotal() + $arrDatos[$i]->get_iva()) - $arrDatosPagos[0]->get_total_pagado());
                    
                    $numTotalSaldo += $numValorSaldo;
                    $numTotal += ($arrDatos[$i]->get_subtotal() + $arrDatos[$i]->get_iva());
                    /**
                     * sacamos el usuario creador
                     */
                    $objDaoUsuarios = new dao_usuarios($arrDatos[$i]->get_creado_por());
                    
                    $responce->rows[$i]['id'] = $arrDatos[$i]->get_id();
                    $responce->rows[$i]['cell']=array(
                        $arrDatos[$i]->get_id(),
                        ($objDaoClientesDatosBasicos->_vo->get_nombres().' '.$objDaoClientesDatosBasicos->_vo->get_apellidos()),
                        $objDaoClientesDatosBasicos->_vo->get_identificacion(),
                        $arrDatos[$i]->get_numero_factura(),
                        ($arrDatos[$i]->get_subtotal() + $arrDatos[$i]->get_iva()),
                        $numValorSaldo,
                        strtoupper($objDaoUsuarios->_vo->get_usuario()),
                        substr($arrDatos[$i]->get_fecha_creacion(),0,10)
                    );
                }
                $responce->userdata['total'] = $numTotal;
                $responce->userdata['saldo'] = $numTotalSaldo;
                utilidades::set_response($responce);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * se encarga de inicializar las variables que serviran para la grilla
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @return void
         * @param number $page
         * @param number $limit
         * @param string $sidx
         * @param string $sord
         */
        private function setFiltersGrillaBasPrivate(&$page, &$limit, &$sidx, &$sord) {
            try {
                $page = (request::get_parameter('page') != null ? request::get_parameter('page') : 1);
                /* get the requested page */
                $limit = (request::get_parameter('rows') != null ? request::get_parameter('rows') : 999999999);
                /* get how many rows we want to have into the grid */
                $sidx = (request::get_parameter('sidx') != null ? request::get_parameter('sidx') : 'id');
                /* get index row - i.e. user click to sort */
                $sord = (request::get_parameter('sord') != null ? request::get_parameter('sord') : 'ASC');
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * Se encarga e consultar en la tabla facturas datos basicos
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @return array
         * @param string $sidx
         * @param string $sord
         * @param int $start
         * @param int $limit
         * @param array $arrParametros
         */
        public function getFacturasDatosBasicos($arrParametros=array(),$sidx = null, $sord = null, $start = null, $limit = null){
            try{
                $objDaoFacturasDatosBasicos = new dao_facturas_datos_basicos();
                
                /***/
                $objDaoFacturasDatosBasicos->_vo->set_estado('A');
                $objDaoFacturasDatosBasicos->_vo->set_tipo('F');
                
                if(isset($arrParametros->fecha_array) && $arrParametros->fecha_array[0]!='' && $arrParametros->fecha_array[1]!=''){
                    $objDaoFacturasDatosBasicos->_vo->set_fecha_creacion($arrParametros->fecha_array);
                }
                
                /* set filters */
                if(request::get_parameter('filters')!=null){
                    utilidades::parsear_filters($objDaoFacturasDatosBasicos->_vo);
                }
                
                return $objDaoFacturasDatosBasicos->select_rows($sidx,$sord,$start,$limit)->fetch_object_vo();
                
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * Crear un documento
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 07-12-2014
         * @param void
         * @return json
         */
        public function voidCrearDocumentoVenta(){
            $objDaoBase = new dao_base();
            $objDaoBase->begin();
            try{
                /**
                 * sacamos el consecutivo de la factura
                 */
                $objDaoParametros = new \MiProyecto\fac_parametros();
                $arrDatosPar = $objDaoParametros->getParametros(
                    (object)array(
                        'id'=>1
                ));
                
                /**
                 * actualizamos el consecutivo de  parametros
                 */
                $objDaoParametros->setUpdateParametros((object)array(
                    'id'=>1,
                    'consecutivo_factura'=>($arrDatosPar[0]->get_consecutivo_factura()+1)
                ));
                
                /**
                 * insertamos en facturas datos basicos
                 */
                $last_fac_id = $this->setInsertarFacturasDatosBasicos(
                    (object)array(
                        'clientes_datos_basicos_id'=>request::get_parameter('hdnIdentificacion_cliente'),
                        'numero_factura'=>'FA '.str_pad($arrDatosPar[0]->get_consecutivo_factura(),6,'0',STR_PAD_LEFT),
                        'subtotal'=>0,
                        'descuento'=>0,
                        'iva'=>0,
                        'observaciones'=>request::get_parameter('tarObservaciones'),
                        'tipo'=>(request::get_parameter('radTipoProceso')),
                ));
                
                /**
                 * insertamos el detalle del documento
                 */
                $numTotalFactura = 0;
                $numTotalDcto = 0;
                for($i=0;$i<(int)request::get_parameter('hdnContRowsDetalle');$i++){
                    if(request::get_parameter('txtReferenciaDetalle-'.$i)==null){
                        continue;
                    }
                    $this->setInsertarFacturasDatosProductosDetalles((object)array(
                        'facturas_datos_basicos_id'=>$last_fac_id,
                        'productos_datos_basicos_id'=>request::get_parameter('hdn_txtReferenciaDetalle-'.$i),
                        'total_item'=>(
                                    (str_replace(',','',request::get_parameter('txtPrecioVenta-'.$i))*str_replace(',','',request::get_parameter('txtCantidad-'.$i))) - 
                                    str_replace(',','',request::get_parameter('txtDctoProducto-'.$i))
                                ),
                        'descuento'=>str_replace(',','',request::get_parameter('txtDctoProducto-'.$i)),
                        'cantidad'=>str_replace(',','',request::get_parameter('txtCantidad-'.$i))
                    ));
                    $numTotalFactura += (
                                (
                                (str_replace(',','',request::get_parameter('txtPrecioVenta-'.$i))*str_replace(',','',request::get_parameter('txtCantidad-'.$i))) - 
                                str_replace(',','',request::get_parameter('txtDctoProducto-'.$i))
                                )
                                
                            );
                    $numTotalDcto += str_replace(',','',request::get_parameter('txtDctoProducto-'.$i));
                }
                
                /**
                 * insertamos en abonos facturas
                 */
                $objFacAbonosFacturas = new \MiProyecto\fac_abonos_facturas();
                $numUltIdAboFact = $objFacAbonosFacturas->setInsertAbonosFacturas((object)array(
                    'valor_pagado'=>(request::get_parameter('radFormaPago')=='PT'?$numTotalFactura:(request::get_parameter('radFormaPago')=='AB'?request::get_parameter('txtValorAbono'):0)),
                    'valor_efectivo'=>(request::get_parameter('radFormaPago')=='PT'?$numTotalFactura:(request::get_parameter('radFormaPago')=='AB'?request::get_parameter('txtValorAbono'):0)),
                    'fecha_pago'=>substr(utilidades::get_current_timestamp(),0,10),
                    'tipo'=>(request::get_parameter('radFormaPago')=='PT'?"PT":"PA"),
                ));
                
                /**
                 * insertamos el plan de pagos y cxc
                 */
                $objFacPagosFacturas = new \MiProyecto\fac_pagos_facturas();
                $numLastIdPFP = $objFacPagosFacturas->setCrearPlanPagos((object)array(
                    'facturas_datos_basicos_id'=>$last_fac_id,
                    'numero_factura'=>$arrDatosPar[0]->get_consecutivo_factura(),
                    'subtotal_generado'=>($numTotalFactura/1.16),
                    'descuento_generado'=>$numTotalDcto,
                    'iva_generado'=>($numTotalFactura-($numTotalFactura/1.16)),
                    'total_pagado'=>(request::get_parameter('radFormaPago')=='PT'?$numTotalFactura:(request::get_parameter('radFormaPago')=='AB'?str_replace(',','',request::get_parameter('txtValorAbono')):0)),
                    'tipo_proceso'=>request::get_parameter('radFormaPago'),
                    'total_factura'=>$numTotalFactura,
                    'abonos_facturas_id'=>$numUltIdAboFact
                ));
                
                /**
                 * insertamos en abonos_facturas_pagos
                 */
                $objFacAbonosFacturas->setInsertAbonosFacturasPagos((object)array(
                    'pagos_facturas_principal_id'=>$numLastIdPFP,
                    'abonos_facturas_id'=>$numUltIdAboFact
                ));
                
                /**
                 * actualizamos los valores del documento
                 */
                $this->setUpdateFacturasDatosBasicos((object)array(
                    'id'=>$last_fac_id,
                    'subtotal'=>($numTotalFactura/1.16),
                    'descuento'=>$numTotalDcto,
                    'iva'=>($numTotalFactura-($numTotalFactura/1.16))
                ));
                
                /**
                 * gestionamos los inventarios
                 */
                $facGestionInventarios = new \MiProyecto\fac_gestion_inventarios();
                $facGestionInventarios->gestion_inventarios_facturacion((object)array(
                    'facturas_datos_basicos_id'=>$last_fac_id
                ));
                
                $objDaoBase->commit();
                
                /***/
                utilidades::set_response(array(
                    'msj'=>'Proceso terminado correctamente',
                    'total_documento'=>(float)$numTotalFactura,
                    'total_dinero'=>(float)str_replace(',','',request::get_parameter('txtDineroRecibido'))
                ));
            } catch (\Exception $ex) {
                $objDaoBase->rollback();
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * se encarga de insertar un registros en la tabla facturas datos basicos
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 07-12-2014
         * @param array $arrParametros
         * @return int
         */
        public function setInsertarFacturasDatosBasicos($arrParametros){
            try{
                $objDaoFacturasDatosBasicos = new dao_facturas_datos_basicos();
                $objDaoFacturasDatosBasicos->_vo->set_clientes_datos_basicos_id($arrParametros->clientes_datos_basicos_id);
                $objDaoFacturasDatosBasicos->_vo->set_numero_factura($arrParametros->numero_factura);
                $objDaoFacturasDatosBasicos->_vo->set_subtotal($arrParametros->subtotal);
                $objDaoFacturasDatosBasicos->_vo->set_descuento($arrParametros->descuento);
                $objDaoFacturasDatosBasicos->_vo->set_iva($arrParametros->iva);
                if(isset($arrParametros->observaciones) && $arrParametros->observaciones!=''){
                    $objDaoFacturasDatosBasicos->_vo->set_observaciones($arrParametros->observaciones);
                }
                $objDaoFacturasDatosBasicos->_vo->set_tipo($arrParametros->tipo);
                $objDaoFacturasDatosBasicos->_vo->set_estado("A");
                $objDaoFacturasDatosBasicos->_vo->set_tipo($arrParametros->tipo);
                
                $objDaoFacturasDatosBasicos->insert_rows();
                
                return $objDaoFacturasDatosBasicos->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * inserta en la tabla facturas datos productos detalles
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 07-12-2014
         * @return int
         * @param type $arrParametros
         */
        public function setInsertarFacturasDatosProductosDetalles($arrParametros){
            try{
                $objDaoFacturasDatosProductosDetalles = new dao_facturas_datos_productos_detalles();
                
                $objDaoFacturasDatosProductosDetalles->_vo->set_facturas_datos_basicos_id($arrParametros->facturas_datos_basicos_id);
                $objDaoFacturasDatosProductosDetalles->_vo->set_productos_datos_basicos_id($arrParametros->productos_datos_basicos_id);
                $objDaoFacturasDatosProductosDetalles->_vo->set_cantidad($arrParametros->cantidad);
                
                /**subtotal-iva-total*/
                $numSubTotal = (($arrParametros->total_item)/1.16);
                $numIva = (($arrParametros->total_item)-(($arrParametros->total_item)/1.16));
                $objDaoFacturasDatosProductosDetalles->_vo->set_subtotal_producto(str_replace(',','',$numSubTotal));
                $objDaoFacturasDatosProductosDetalles->_vo->set_descuento_producto(str_replace(',','',$arrParametros->descuento));
                $objDaoFacturasDatosProductosDetalles->_vo->set_iva_producto(str_replace(',','',$numIva));
                
                if(isset($arrParametros->observaciones_descuento_producto) && $arrParametros->observaciones_descuento_producto!=''){
                    $objDaoFacturasDatosProductosDetalles->_vo->set_observaciones_descuento_producto($arrParametros->observaciones_descuento_producto);
                }
                if(isset($arrParametros->observaciones) && $arrParametros->observaciones!=''){
                    $objDaoFacturasDatosProductosDetalles->_vo->set_observaciones($arrParametros->observaciones);
                }
                
                /**consultamos el precio de compra*/
                $objFacGestionProductos = new \MiProyecto\fac_gestion_productos();
                $arrDProd = $objFacGestionProductos->get_productos_datos_basicos(null,null,null,null,"A",(object)array(
                    'id'=>$arrParametros->productos_datos_basicos_id
                ));
                
                $objDaoFacturasDatosProductosDetalles->_vo->set_precio_compra($arrDProd!=null?$arrDProd[0]->get_valor_compra():$arrParametros->total_item);
                $objDaoFacturasDatosProductosDetalles->_vo->set_estado("A");
                
                $objDaoFacturasDatosProductosDetalles->insert_rows();
                
                return $objDaoFacturasDatosProductosDetalles->get_last_insert_id();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
    
        /**
         * Actualiza la tabla factura datos basicos
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 07-12-2014
         * @param array $arrParametros
         * @return boolean
         */
        public function setUpdateFacturasDatosBasicos($arrParametros=array()){
            try{
                $objDaoFacturasDatosBasicos = new dao_facturas_datos_basicos();
                $objDaoFacturasDatosBasicos->_vo->set_id($arrParametros->id);
                
                if(isset($arrParametros->subtotal) && $arrParametros->subtotal!=''){
                    $objDaoFacturasDatosBasicos->_vo->set_subtotal($arrParametros->subtotal);
                }
                if(isset($arrParametros->descuento) && $arrParametros->descuento!=''){
                    $objDaoFacturasDatosBasicos->_vo->set_descuento($arrParametros->descuento);
                }
                if(isset($arrParametros->iva) && $arrParametros->iva!=''){
                    $objDaoFacturasDatosBasicos->_vo->set_iva($arrParametros->iva);
                }
                
                $objDaoFacturasDatosBasicos->update_rows();
                
                return true;
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * consulta los daatos de facturas datos productos
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @date 20-12-2014
         * @param type $arrParametros
         * @return array
         */
        public function getFacturasDatosProductos($arrParametros=array()){
            try{
                $objDaoFacturasProductosDetalles = new dao_facturas_datos_productos_detalles();
                
                if(isset($arrParametros->facturas_datos_basicos_id) && $arrParametros->facturas_datos_basicos_id!=''){
                    $objDaoFacturasProductosDetalles->_vo->set_facturas_datos_basicos_id($arrParametros->facturas_datos_basicos_id);
                }
                
                return $objDaoFacturasProductosDetalles->select_rows()->fetch_object_vo();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * busca las ventas anuales para reportes
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @param null
         * @return json array
         */
        public function voidSearchVentasAnuales(){
            try{
                /**buscamos los datos anuales**/
                $arrDFac_an = $this->getFacturasDatosBasicos((object)array(
                    'fecha_array'=>array(request::get_parameter('anio').'-01-01 00:00:00',request::get_parameter('anio').'-12-31 23:59:59')
                ),"fecha_creacion","ASC");
                $arr_response = new \stdClass();
                if($arrDFac_an!=null){
                    
                    $mes_c = null;
                    $cont_mes = -1;
                    for($i=0;$i<count($arrDFac_an);$i++){
                        /**buscamos en pagos facturas si ya se hizo paga en su totalidad la factura***/
                        $objFacPagosFacturas = new \MiProyecto\fac_pagos_facturas();
                        $arrDPagosFPrin = $objFacPagosFacturas->get_pagos_factura_principal((object)array(
                            'facturas_datos_basicos_id'=>$arrDFac_an[$i]->get_id()
                        ));
                        if($arrDPagosFPrin[0]->get_estado()=='P'){
                            continue;
                        }
                        /**totalizamos por cada mes*/
                        if($mes_c != substr($arrDFac_an[$i]->get_fecha_creacion(),5,2)){
                            $mes_c = substr($arrDFac_an[$i]->get_fecha_creacion(),5,2);
                            $cont_mes ++;
                            $arr_response->rows[$cont_mes][utilidades::get_mes_string_spanish($mes_c)] = 0;
                            $arr_response->rows[$cont_mes]["comprado"] = 0;
                            $arr_response->rows[$cont_mes]["ganado"] = 0;
                        }
                        $vr_factura = ($arrDFac_an[$i]->get_subtotal() + $arrDFac_an[$i]->get_iva());
                        
                        /*******************************************************/
                        /**sacamos los datos de los productos*/
                        $arrDDeta = $this->getFacturasDatosProductos((object)array(
                            'facturas_datos_basicos_id'=>$arrDFac_an[$i]->get_id()
                        ));
                        $tot_comprado = 0;
                        if($arrDDeta!=null){
                            for($j=0;$j<count($arrDDeta);$j++){
                                /*******************************************************/
                                /** sacamos los datos del producto ****/
                                $objFacProductosDatosBasicos = new \MiProyecto\fac_gestion_productos();
                                $arrDProducDatBas = $objFacProductosDatosBasicos->get_productos_datos_basicos(
                                        null,null,null,null,'A',
                                        (object)array(
                                            'id'=>$arrDDeta[$j]->get_productos_datos_basicos_id()
                                        )
                                );
                                $tot_comprado += $arrDProducDatBas[0]->get_valor_compra();
                            }
                        }
                        
                        /***/
                        $arr_response->rows[$cont_mes][utilidades::get_mes_string_spanish($mes_c)] += $vr_factura;
                        $arr_response->rows[$cont_mes]["comprado"] += $tot_comprado;
                        $arr_response->rows[$cont_mes]["ganado"] += (
                                    $vr_factura -
                                    $tot_comprado
                                );
                    }
                }
                utilidades::set_response($arr_response);
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * buscar ventas mensuales
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @param null
         * @return json
         */
        public function voidSearchVentasMensuales(){
            try{
                $arrDMensual = $this->getFacturasDatosBasicos((object)array(
                    'fecha_array'=>array(request::get_parameter('anio').'-'.request::get_parameter('mes').'-01',
                                         request::get_parameter('anio').'-'.request::get_parameter('mes').'-31'
                                        ),
                    'estado'=>'A',
                    'tipo'=>'F'
                ),"fecha_creacion","ASC");
                /****/
                $arr_response = new \stdClass();
                $mes_c = null;
                $cont_mes = -1;
                if($arrDMensual!=null){
                    for($i=0;$i<count($arrDMensual);$i++){
                        /**buscamos en pagos facturas si ya se hizo paga en su totalidad la factura***/
                        $objFacPagosFacturas = new \MiProyecto\fac_pagos_facturas();
                        $arrDPagosFPrin = $objFacPagosFacturas->get_pagos_factura_principal((object)array(
                            'facturas_datos_basicos_id'=>$arrDMensual[$i]->get_id()
                        ));
                        if($arrDPagosFPrin[0]->get_estado()=='P'){
                            continue;
                        }
                        
                        /**totalizamos por cada mes*/
                        if($mes_c != substr($arrDMensual[$i]->get_fecha_creacion(),8,2)){
                            $mes_c = substr($arrDMensual[$i]->get_fecha_creacion(),8,2);
                            $cont_mes ++;
                            $arr_response->rows[$cont_mes][$mes_c] = 0;
                            $arr_response->rows[$cont_mes]["comprado"] = 0;
                            $arr_response->rows[$cont_mes]["ganado"] = 0;
                        }
                        $vr_factura = ($arrDMensual[$i]->get_subtotal() + $arrDMensual[$i]->get_iva());
                        
                        
                        /*******************************************************/
                        /**sacamos los datos de los productos*/
                        $arrDDeta = $this->getFacturasDatosProductos((object)array(
                            'facturas_datos_basicos_id'=>$arrDMensual[$i]->get_id()
                        ));
                        $tot_comprado = 0;
                        if($arrDDeta!=null){
                            for($j=0;$j<count($arrDDeta);$j++){
                                /*******************************************************/
                                /** sacamos los datos del producto ****/
                                $objFacProductosDatosBasicos = new \MiProyecto\fac_gestion_productos();
                                $arrDProducDatBas = $objFacProductosDatosBasicos->get_productos_datos_basicos(
                                        null,null,null,null,'A',
                                        (object)array(
                                            'id'=>$arrDDeta[$j]->get_productos_datos_basicos_id()
                                        )
                                );
                                $tot_comprado += $arrDProducDatBas[0]->get_valor_compra();
                            }
                        }
                        /***/
                        $arr_response->rows[$cont_mes][$mes_c] += $vr_factura;
                        $arr_response->rows[$cont_mes]["comprado"] += $tot_comprado;
                        $arr_response->rows[$cont_mes]["ganado"] += (
                                    $vr_factura -
                                    $tot_comprado
                                );
                    }
                }
                
                /****/
                utilidades::set_response($arr_response);
            }catch(\Exception $e){
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * busca los detalles o items de una factura
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @param null
         * @date 28-12-2014
         * @return json
         */
        public function voidSearchDetallesFactura(){
            try{
                $arrDatosFacturaProd = $this->getFacturasDatosProductos((object)array(
                    'facturas_datos_basicos_id'=>request::get_parameter('id_factura')
                ));
                
                $arrResponse = new \stdClass();
                if(count($arrDatosFacturaProd)>0){
                    for($i=0;$i<count($arrDatosFacturaProd);$i++){
                        $arrResponse->rows[$i] = new \stdClass();
                        $arrResponse->rows[$i]->id = $arrDatosFacturaProd[$i]->get_id();
                        
                        /**sacamos la referencia del producto*/
                        $objFacGestionProductos = new \MiProyecto\fac_gestion_productos();
                        $arrDproductos = $objFacGestionProductos->get_productos_datos_basicos(
                                    null,null,null,null,'A',
                                    (object)array(
                                        'id'=>$arrDatosFacturaProd[$i]->get_productos_datos_basicos_id()
                                    )
                                );
                        
                        $arrResponse->rows[$i]->referencia = $arrDproductos[0]->get_nombres();
                        $arrResponse->rows[$i]->descripcion = $arrDproductos[0]->get_descripcion();
                        $arrResponse->rows[$i]->cantidad = $arrDatosFacturaProd[$i]->get_cantidad();
                        $arrResponse->rows[$i]->total = number_format(
                                                ($arrDatosFacturaProd[$i]->get_subtotal_producto() + $arrDatosFacturaProd[$i]->get_iva_producto())
                                            );
                    }
                }
                utilidades::set_response($arrResponse);
            }catch(\Exception $e){
                new ExceptionHandler($ex->getMessage());
            }
        }
    }
}